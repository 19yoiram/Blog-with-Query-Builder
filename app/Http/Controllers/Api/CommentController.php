<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $comment = Comment::with('post')->get();
        return CommentResource::collection($comment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            "name" => "required",
            "comment" => "nullable|string",

        ]);

        $comment = Comment::create([
            "name" => $request->name,
            "comment" => $request->comment,
            "post_id" => $request->post_id,
        ]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $comment = Comment::findOrFail($id);

         $request->validate([
            "name" => "required",
            "comment" => "nullable|string",

        ]);
        
        $comment->update([
            'name'=>$request->name,
            'comment'=>$request->comment,
            'post_id'=>$request->post_id
        ]);

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //$category = Category::findOrFail($id);
        $comment = Comment::findOrFail($id);
        Comment::where('id', $id)->delete();

        return new CommentResource($comment);
    }
}
