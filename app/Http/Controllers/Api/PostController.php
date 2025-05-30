<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

$rawPosts = DB::table('posts')
    ->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
    ->leftJoin('taggables', function ($join) {
        $join->on('taggables.taggable_id', '=', 'posts.id')
            ->where('taggables.taggable_type', '=', 'App\Models\Post');
    })
    ->leftJoin('tags', 'taggables.tag_id', '=', 'tags.id')
    ->select(
        'posts.id',
        'posts.name',
        'posts.description',
        'posts.image',
        'categories.name as category_name',
        'tags.name as tag_name'
    )
    ->get();


$grouped = collect($rawPosts)->groupBy('id')->map(function ($items) {
    $first = $items->first();
    return [
        'id' => $first->id,
        'name' => $first->name,
        'description' => $first->description,
        'image' => $first->image,
        'category_name' => $first->category_name,
        'tag_name' => $items->pluck('tag_name')->filter()->unique()->values(),
    ];
})->values();

return PostResource::collection($grouped);

}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            "name" => "required",
            "description" => "required",
            "image" => "required|mimes:jpg,jpeg,png"
        ]);


        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/uploads/images/'), $imageName);
        }


        $post = Post::create([
            "name" => $request->name,
            "description" => $request->description,
            "image" => $imageName,
            "category_id" => $request->category_id,

        ]);

        $tags = $request->tags;
        $post->morphTags()->attach($tags);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
        ],201);
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
        $post = Post::findOrFail($id);
        $data['image'] = $post->image;

        $data = $request->validate([
            "name" => "required",
            "description" => "required",
            "image" => "nullable|mimes:jpg,jpeg,png"
        ]);


        if ($request->hasFile('image')) {

            $oldImagePath = public_path('/uploads/images/' . $post->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/images/'), $imageName);
            $data['image'] = $imageName;
        }


        $post->update($data);
        $tags = $request->tags;
        $post->morphTags()->sync($tags);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $post = Post::findOrFail($id);

        $post->morphTags()->detach();
        Post::where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ],201);
    }
}
