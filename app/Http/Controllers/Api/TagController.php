<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $tag = Tag::all();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $tag,
        ]);
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
        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'data' => $tag,
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

        $tag = Tag::findOrFail($id);

        $data = $request->validate([
            "name" => "nullable|string",

        ]);

        $tag->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Tag::where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully',
        ]);
    }
}
