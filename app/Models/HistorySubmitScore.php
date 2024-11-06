<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class HistorySubmitScore extends Model
{
    use HasFactory;
    protected $table = 't_history_submit_score';

    public function getLeaderboard(){
        
    }
}
