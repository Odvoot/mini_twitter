<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Follower;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class FollowerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function follow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'followed_user' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->get('followed_user')
                ]);
        }

        try {
            $row = Follower::where('followed_user', $request->followed_user)->where('followed_from', auth()->id())->delete();
            if($row == 0){
                $follower =   new Follower;
                $follower->followed_user = $request->followed_user;
                $follower->followed_from = auth()->id();
                $follower->save();
            } 
            
            return response()->json([
                'success' => true,
                'message' => 'Success'
                ]);

            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
                ]);
        }
        
        

        //followed_from
    }
}
