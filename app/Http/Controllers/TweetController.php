<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Tweet;

class TweetController extends Controller
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

    /**
     * post a tweet.
     *
     * @return bool
     */

    public function tweet(Request $request)
    {
        //return response()->json($request->all(), 200);
        $validator = Validator::make($request->all(), [
            'tweet' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please Try Again'
                ]);
        }

        $tweet = new Tweet();
        $tweet->tweet = $request->tweet;
        $tweet->user_id = auth()->id();
        $saved = $tweet->save();
        if($saved){
            return response()->json([
                'success' => true,
                'tweet' => $tweet,
                'posted_at' => $tweet->created_at->format('d M H:i'),
                'name' => auth()->user()->name,
                'message' => 'Success'
                ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Please Try Again.'
            ]);

    }
}
