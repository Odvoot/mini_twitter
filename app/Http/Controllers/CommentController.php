<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Comment;

class CommentController extends Controller
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

    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'tweet_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please Try Again validator'
                ]);
        }

        $comment = new Comment;
        $comment->user_id = auth()->id();
        $comment->comment = $request->comment;
        $comment->tweet_id = $request->tweet_id;
        $saved = $comment->save();
        if ($saved) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'name' => auth()->user()->name,
                'posted_at' => $comment->created_at->format('d M H:i'),
                'message' => 'Success'
                ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Please Try Again.'
            ]);

    }


}
