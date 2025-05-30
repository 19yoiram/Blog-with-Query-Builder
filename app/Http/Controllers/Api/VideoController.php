<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // $video = Video::with('morphTags')->get();
        $video = DB::table('videos')
            ->leftJoin('taggables', function ($join) {
                $join->on('taggables.taggable_id', '=', 'videos.id')
                    ->where('taggables.taggable_type', '=', 'App\Models\Video');
            })
            ->leftJoin('tags', 'taggables.tag_id', '=', 'tags.id')
            ->select(
                'videos.id',
                'videos.title',
                'videos.link',
                'tags.name as tag_name'
            )
            ->get();


        $grouped = collect($video)->groupBy('id')->map(function ($items) {
            $first = $items->first();
            return [
                'id' => $first->id,
                'title' => $first->title,
                'link' => $first->link,
                'tag_name' => $items->pluck('tag_name')->filter()->unique()->values(),
            ];
        })->values();

        return VideoResource::collection($grouped);
        // return VideoResource::collection($video);
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

        return response()->json([
            'success' => true,
            'message' => 'Video created successfully',
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
        $video = Video::findOrFail($id);

        $data = $request->validate([
            "title" => "required",
            "link" => "required",

        ]);

        $video->update($data);
        $tags = $request->tags;
        $video->morphTags()->sync($tags);

        return response()->json([
            'success' => true,
            'message' => 'Video updated successfully',
        ],201);
        // return new VideoResource($video);
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

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully',
        ],201);
    }
}
