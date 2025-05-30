<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $tag = DB::table('tags')->get();
        return TagResource::collection($tag);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'nullable|string'
        ]);
        $tag = Tag::create([
            "name" => $request->name,

        ]);
        return new TagResource($tag);
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

        $tag = Tag::findOrFail($id);

        $data = $request->validate([
            "name" => "nullable|string",

        ]);

        $tag->update($data);

        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $tag = Tag::findOrFail($id);
        Tag::where('id', $id)->delete();
        return new TagResource($tag);
    }
}
