<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use App\User;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $followed_list = User::followed();
        $my_id = auth()->id() ? auth()->id() : 0;
        $raw_q = 'CASE WHEN f.followed_user IS NULL AND user_id != '. $my_id .' THEN 1 ELSE 2 END AS is_friend';
        $tweets = Tweet::with(['user:id,name','comment.user:id,name'])
        ->leftJoin('followers as f', function ($join) {
            $join->on('tweets.user_id', '=', 'f.followed_user')->where('f.followed_from', auth()->id());
        })
        ->select('tweets.*','f.followed_user', DB::raw($raw_q))
        ->orderBy('is_friend', 'DESC')
        ->orderBy('tweets.created_at', 'DESC')
        ->simplePaginate(10);
        $user = auth()->user();
        if($request->ajax()){
            return response()->json([
                'success' => true, 
                'next_page' => $tweets->nextPageUrl(), 
                'auth' => auth()->check(),
                'tweets' => $tweets], 
                200);
        }
        return view('home', compact('tweets', 'user', 'followed_list'));
    }
}
