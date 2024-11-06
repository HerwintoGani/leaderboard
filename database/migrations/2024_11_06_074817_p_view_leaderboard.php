<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $procedure = "CREATE PROCEDURE `p_view_leaderboard`(IN `page` INTEGER, IN `take` INTEGER)
                        BEGIN
                            DECLARE perPage int;
                            DECLARE pages int;
                            SET perPage = take;
                            SET pages = page * take;
                            
                            CREATE OR REPLACE TEMPORARY TABLE temp_leaderboard
                            select `users`.`name`, `t_history_submit_score`.`user_id`, `t_history_submit_score`.`level`, `t_history_submit_score`.`score` 
                            from `t_leaderboard` 
                            inner join `users` on `users`.`id` = `user_id` 
                            inner join `t_history_submit_score` on `t_history_submit_score`.`id` = `score_id`
                            order by `t_history_submit_score`.`score` desc;
                            
                            set @var = 0;
                            CREATE OR REPLACE TEMPORARY TABLE leaderboard_ranking
                            select 
                            (@var := @var+1) as ranking,
                            l.*
                            from temp_leaderboard l;
                            
                            select * 
                            from leaderboard_ranking
                            limit perPage offset pages;
                        END";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
