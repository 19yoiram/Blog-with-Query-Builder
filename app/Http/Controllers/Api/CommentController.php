<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // $comment = Comment::with('post')->get();
        $rawComments = DB::table('comments')
            ->leftJoin('posts', 'comments.post_id', '=', 'posts.id')
            ->select(
                'comments.id',
                'comments.name',
                'comments.comment',
                'posts.name as post_name'
            )
            ->get();


        $grouped = collect($rawComments)->groupBy('id')->map(function ($items) {
            $first = $items->first();
            return [
                'id' => $first->id,
                'name' => $first->name,
                'comment' => $first->comment,
                'post_name' => $first->post_name,
            ];
        })->values();

        return CommentResource::collection($grouped);

        // return CommentResource::collection($comment);
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
    
        if(!empty($comment)){
        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
        ],201);
    }
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

        if(!empty($comment)){
            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
            ],201);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
        $comment = Comment::findOrFail($id);
        Comment::where('id', $id)->delete();

       if(!empty($comment)){
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ],201);
    }
    }
}
