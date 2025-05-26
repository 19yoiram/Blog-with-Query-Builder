<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $posts = Post::with('category')->get();

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $posts,
        ]);
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
            'data' => $post,
        ]);
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
        // dd($request->tags);
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
            'data' => $post,
        ]);
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
        ]);
    }
}
