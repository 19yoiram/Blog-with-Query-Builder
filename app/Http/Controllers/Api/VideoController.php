<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $video = Video::with('morphTags')->get();
        return VideoResource::collection($video);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            "title" => "required",
            "link" => "required",

        ]);
        $video = Video::create([
            "title" => $request->title,
            "link" => $request->link,
        ]);
        $tags = $request->tags;
        $video->morphTags()->attach($tags);

        return new VideoResource($video);
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
        $video = Video::findOrFail($id);

        $data = $request->validate([
            "title" => "required",
            "link" => "required",

        ]);

        $video->update($data);
        $tags = $request->tags;
        $video->morphTags()->sync($tags);


        return new VideoResource($video);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $video = Video::findOrFail($id);
        $video->morphTags()->detach();

        Video::where('id', $id)->delete();

        return new VideoResource($video);
    }
}
