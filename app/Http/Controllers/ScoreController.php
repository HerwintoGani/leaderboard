<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HistorySubmitScore;
use App\Models\Leaderboard;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;

class ScoreController extends Controller
{
    //
    public function scoreInsert(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'level' => 'required|digits:1',
            'score' => 'required|digits_between:1,20'
        ]);
        DB::beginTransaction();
        $model = new HistorySubmitScore();
        $model->user_id = $request->user_id;
        $model->level = $request->level;
        $model->score = $request->score;
        $model->save();

        // Compare Leaderboard Score
        $leaderboard = Leaderboard::where('user_id',$request->user_id)
                                    ->first();
        if($request->score > $leaderboard->score)
        {
            $leaderboard->score_id = $model->id;
            $leaderboard->save();
        }
        
        $user = User::where('id',$request->user_id)->first();
        DB::commit();
        $data = [
            'message' => 'Score Submitted',
            'score' => $model->score,
            'user' => $user,
            'result' => true
        ];
        return $data;
    }
    public function scoreInsertBulk(Request $request)
    {
        // Debugging/initial setup purpose if not using seeder, RUN ONLY LEADERBOARD TABLE IS EMPTY
        // Truncate leaderboard table if want to insert more data/run the function again
        
        DB::beginTransaction();
        $list = [];
        for($i = 1; $i <= 10000; $i++)
        {
            for($j = 0; $j <= 100; $j++)
            {
                $data = [
                    'user_id' => $i,
                    'level' => random_int(0, 9),
                    'score' => random_int(1, 10000000)
                ];
                array_push($list, $data);
            }
        }

        // Bulk Insert
        $list = array_chunk($list, 698);
        foreach($list as $chunks)
        {
            $model = new HistorySubmitScore();
            $model->insert($chunks);
        }

        // Set Leaderboard
        for($i = 1; $i <= 10000; $i++)
        {
            $leaderboard = new Leaderboard();
            $leaderboard->user_id = $i;
            $leaderboard->score_id = HistorySubmitScore::select('id')
                                                        ->where('user_id', $i)
                                                        ->where('score', HistorySubmitScore::where('user_id',$i)->max('score'))
                                                        ->first()->id;
            $leaderboard->save();
        }
        DB::commit();
        $data = [
            'message' => 'Data Injected',
            'result' => true
        ];
        return $data;
    }
    
    public function callApi(Request $request)
    {
        $timestamp = Carbon::now()->timestamp;
        $nonce = bin2hex(random_bytes(10));
        $secretKey = 'ABC123xyz';
        $signature = "$nonce$timestamp$secretKey";
        $header = [
            'Accept' => "application/json",
            'X-Nonce' => $nonce,
            'X-API-Signature' => hash('sha256',$signature)
        ];
        $post_data = [
            'timestamp' => $timestamp
        ];
        $url = 'https://unisync.alphagames.my.id/api/assessment';
        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => $header,
            'json' => $post_data
        ]);
        $body = json_decode($response->getBody());
        return $body;
    }
}
