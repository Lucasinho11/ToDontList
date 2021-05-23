<?php

namespace App\Http\Controllers;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function index(Request $request)
    {
        
        $posts = Posts::where('user_id', $request->user()->id)->get();
        return response()->json([
            $posts
        ], 200);
        
    }
    public function show(Request $request, $id)
    {
        
        $post = Posts::find($id);
        if($request->user()->id != $post->user_id){
            return response()->json([
                "message"=> "Accès interdit!"
            ], 403);
        }
        return response()->json([
            $post
        ], 200);
       
        
    }


    public function create(Request $request)
    {
        if(!$request->user()->id){
            return response()->json([
                "message"=> "Accès interdit! Veuillez vous login"
            ], 403);
        }
        $request->validate([
            'title' => 'required|title',
            'body' => 'required|body',
        ]);

        $post = Posts::create([
            'tile' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user()->id
        ]);
        return response()->json([
            $post
        ], 200);
     }


    //     $token = $user->createToken($request->device_name)->plainTextToken;

    //     return response()->json([
    //         "token" => $token,
    //         "name" => $user->name,
    //         "email" => $user->email,
    //         "created_at" => $user->created_at
    //     ], 200);
    // }
}