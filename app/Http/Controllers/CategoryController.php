<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required"
        ]);

        $category = Category::create($validatedData); 

        return response()->json([
            "message" => "Category berhasil dibuat",
            "category" => [
                'name' => $category->name
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        if(!$category) {
            return response()->json(['message' => 'Category tidak ditemukan'], 400);
        }

        return response()->json([
            "message" => "Category berhasil ditemukan",
            "category" => [
                'name' => $category->name
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $category->update($request->all());

        return response()->json([
            "message" => "Category berhasil diupdate",
            "category" => [
                'name' => $category->name
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if(!$category) {
            return response()->json(["message" => "Category tidak ditemukan"]);
        }

        $category->delete();

        return response()->json([
            "message" => "Category berhasil dihapus",
            "category" => [
                'name' => $category->name
            ]
        ], 200);
    }
}
