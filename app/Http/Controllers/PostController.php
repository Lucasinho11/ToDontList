<?php

namespace App\Http\Controllers;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PostController extends Controller
{
    public function index(Request $request)
    {
        
        $posts = Posts::where('user_id', $request->user()->id)->orderBy('id', 'desc')->get();
        return response()->json([
            $posts
        ], 201);
        
    }
    public function show(Request $request, $id)
    {
        
        $post = Posts::find($id);
        if (!$post) {
            return response()->json(['message' => "Not Found"], 404);
        }

        if ($post->user_id != $request->user()->id) {
            return response()->json(["message" => "Forbidden"], 403);
        }
        
        return response()->json([
            $post
        ], 200);
       
        
    }


    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $post = Posts::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id'=>$request->user()->id,
        ]);

        return response()->json([
            $post
        ], 201);
     }

     public function update(Request $request, $id)
    {

        $post = Posts::find($id);
        if($request->user()->id != $post->user_id){
            return response()->json([
                "message"=> "Accès interdit!"
            ], 403);
        }
        $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $post_updated = Posts::where('id', $id)->update([
            'title' => $request->title,
            'body'=> $request->body 
            ]);
        return response()->json([
            "success"=>true
        ], 200);
       
     }
     public function delete(Request $request, $id)
    {

        $post = Posts::find($id);
        if(!$post){
            return response()->json([
                "message"=> "Tache innexistante"
            ], 404);
        }
        if($request->user()->id != $post->user_id){
            return response()->json([
                "message"=> "Accès interdit!"
            ], 403);
        }
        $post_deleted = Posts::where('id', $id)->delete();
        return response()->json([
            "success"=>true
        ], 200);
       
     }



}