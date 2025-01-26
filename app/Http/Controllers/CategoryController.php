<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'statrus' => '200',
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:225']);

        $category = Category::create($request->all());

        return response()->json([
            'status' => '201',
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return response()->json([
                'status' => '404',
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'status' => '200',
            'message' => 'Category retrieved successfully',
            'data' => $category
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return response()->json([
                'status' => '404',
                'message' => 'Category not found'
            ], 404);
        }

        $category->update($request->all());

        return response()->json([
            'status' => '200',
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }

    function destroy($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return response()->json([
                'status' => '404',
                'message' => 'Category not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
