<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    //
    public function showLeaderboard(Request $request)
    {
        if(isset($request->username)){
            $username = $request->username;
            $model = DB::select("CALL p_view_leaderboard_search('$username')");
        }
        else {
            $request->validate([
                'page' => 'required|integer|gt:0',
                'take' => 'required|integer|gt:0'
            ]);
            $page = ($request->page - 1);
            $take = $request->take;
            $model = DB::select("CALL p_view_leaderboard($page, $take)");
        }

        $data = [
            'message' => 'Leaderboard data retrieved',
            'result' => TRUE,
            'data' => $model
        ];
        return $data;
    }
}
