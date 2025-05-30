<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = DB::table('categories')->get();
        return CategoryResource::collection($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            "name" => "required",
            "description" => "required",

        ]);
        $category = Category::create([
            "name" => $request->name,
            "description" => $request->description,
        ]);

        // return new CategoryResource($category);
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
        ], 201);
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
        $category = Category::findOrFail($id);

        $data = $request->validate([
            "name" => "required",
            "description" => "required",

        ]);

        $category->update($data);

        //   return new CategoryResource($category);
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Category::findOrFail($id);
        Category::where('id', $id)->delete();

        //  return new CategoryResource($category);
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ], 201);
    }
}
