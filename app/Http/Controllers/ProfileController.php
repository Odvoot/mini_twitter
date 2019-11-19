<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;

class ProfileController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $tweets = Tweet::with(['user:id,name','comment.user:id,name'])
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'DESC')
        ->simplePaginate(5);
        $tweet_count = Tweet::where('user_id', auth()->id())->count();
        $user = auth()->user();
        return view('profile', compact('tweets','user', 'tweet_count'));
    }
}
