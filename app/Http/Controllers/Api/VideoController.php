<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $video = Video::all();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $video,
        ]);

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

        return response()->json([
            'success' => true,
            'message' => 'Video created successfully',
            'data' => $video,
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
         $video = Video::findOrFail($id);

        $data = $request->validate([
            "title" => "required",
            "link" => "required",

        ]);

        $video->update($data);


        return response()->json([
            'success' => true,
            'message' => 'Video updated successfully',
            'data' => $video,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Video::where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully',
        ]);
    }
}
