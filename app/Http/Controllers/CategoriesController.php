<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //cek apakah user sesuai
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid'
            ], 401);
        }
        //ambil categoris yang ada sesuai id user
        $categories = Category::where('user_id', $user->id)->get();

        //return response
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriesRequest $request)
    {
        //cek apakah user sesuai
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid'
            ], 401);
        }

        //validasi
        $validated = $request->validated();

        $category = Category::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'type' => $validated['type']
        ]);

        //return response
        return response()->json([
            'status' => 'success',
            'message' => 'category berhasil dibuat',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //cek apakah user sesuai
        if ($category->user_id !=  auth('api')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'unauthorize'
            ], 403);
        }

        return response()->json([
            "status" => 'success',
            "data" => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriesRequest $request, Category $category)
    {
        //cek apakah user benar
        if ($category->user_id != auth('api')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'unauthorize'
            ], 403);
        }

        $validated = $request->validated();

        $category->update($validated);
        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //cek apakah user benar
        if ($category->user_id != auth('api')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'unauthorize'
            ], 403);
        }

        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'category berhasil di hapus'
        ]);
    }
}
