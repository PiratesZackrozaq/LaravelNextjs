<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Categories::all();
        return response()->json([
            'status' => 'success gets all categories',
            'code' => 200,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $category = Categories::create([
            'title' => $request->title,
        ]);

        return response()->json([
            'status' => 'Category created successfully',
            'code' => 201,
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Failed get by id',
                'code' => 404,
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'Success get by id',
            'code' => 200,
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Failed get by id',
                'code' => 404,
                'data' => null
            ], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return response()->json([
            'status' => 'Success update category',
            'code' => 200,
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Failed get by id',
                'code' => 404,
                'data' => null
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'Success delete category',
            'code' => 200,
            'data' => null
        ]);
    }
}
