<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function showUser(Request $request)
    {
        $model = new User();

        if(isset($request->user_id)){
            $request->validate([
                'user_id' => 'required|integer'
            ]);
            $model = $model->where('id',$request->user_id);
        }
        $model = $model->get();
        return $model;
    }
}
