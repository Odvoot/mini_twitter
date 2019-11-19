<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Tweet;
use App\User;

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
     * Show Profile
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $tweets = Tweet::with(['user:id,name','comment.user:id,name'])
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'DESC')
        ->get();
        $tweet_count = $tweets->count();
        $user = auth()->user();
        return view('profile', compact('tweets','user', 'tweet_count'));
    }

    /**
     * Upload Image
     *
     * @return object
     */

    public function upload_photo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|max:1024'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->get('photo')
                ]);
        }
        $user = auth()->user();
        if ($request->hasFile('photo')) {
            $ext = $request->photo->getClientOriginalExtension();
            $path = Storage::putFileAs(
                'img', 
                $request->file('photo'), 
                'image_'.$user->id.'_'.time().'.'.$ext
            );
            $user->photo = $path;
        }
        $user->save();
        return response()->json([
            'success' => true,
            'path' => url($user->photo),
            'message' => 'Saved'
            ]);
    }
}
