<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Traits\ApiResponseTrait;

class CommentController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $rawComments = DB::table('comments')
                ->leftJoin('posts', 'comments.post_id', '=', 'posts.id')
                ->select(
                    'comments.id',
                    'comments.name',
                    'comments.comment',
                    'posts.name as post_name'
                )
                ->get();

            if ($rawComments->isEmpty()) {
                return $this->notFoundResponse('No comments found');
            }

            $grouped = collect($rawComments)->groupBy('id')->map(function ($items) {
                $first = $items->first();
                return [
                    'id' => $first->id,
                    'name' => $first->name,
                    'comment' => $first->comment,
                    'post_name' => $first->post_name,
                ];
            })->values();

            return $this->successResponse('Comments retrieved successfully', CommentResource::collection($grouped));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch comments: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required",
                "comment" => "nullable|string",
            ]);

            Comment::create([
                "name" => $request->name,
                "comment" => $request->comment,
                "post_id" => $request->post_id,
            ]);

            return $this->successResponse('Comment created successfully', null, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create comment: ' . $e->getMessage(), 500);
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
        try {
            $request->validate([
                "name" => "required",
                "comment" => "nullable|string",
            ]);

            $comment = Comment::findOrFail($id);

            $comment->update([
                'name' => $request->name,
                'comment' => $request->comment,
                'post_id' => $request->post_id
            ]);

            return $this->successResponse('Comment updated successfully', null, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update comment: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $comment = Comment::findOrFail($id);
        Comment::where('id', $id)->delete();

        if (!empty($comment)) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully',
            ], 201);
        }
    }
}
