<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tweet;
use App\User;

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
        $tweets = Tweet::with(['user:id,name','comment.user:id,name'])
        ->orderBy('created_at', 'DESC')
        ->simplePaginate(5);
        if($request->ajax()){
            return response()->json([
                'success' => true, 
                'next_page' => $tweets->nextPageUrl(), 
                'auth' => auth()->check(), 
                'tweets' => $tweets], 
                200);
        }
        return view('home', compact('tweets'));
    }
}
