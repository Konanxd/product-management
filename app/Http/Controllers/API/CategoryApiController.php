<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends Controller
{
    
    public function index(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|integer|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $organizationId = $request->organization_id;

        if (!$user->organizations()->find($organizationId)) {
            return response()->json(['message' => 'Akses ditolak. Anda bukan anggota dari organisasi ini.'], 403);
        }
        $categories = Category::where('organization_id', $organizationId)->get();

        return response()->json($categories, 200);
    }

    /**
     * 
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'organization_id' => 'required|integer|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $organizationId = $request->organization_id;

        if (!$user->organizations()->find($organizationId)) {
            return response()->json(['message' => 'Akses ditolak. Anda bukan anggota dari organisasi ini.'], 403);
        }
        
        $request->validate([
             'name' => 'unique:categories,name,NULL,id,organization_id,' . $organizationId
        ]);

        $category = Category::create([
            'name' => $request->name,
            'organization_id' => $organizationId,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    /**
     *
     */
    public function show($id)
    {
        $user = auth()->user();
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
        }

        if (!$user->organizations()->find($category->organization_id)) {
            return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk melihat kategori ini.'], 403);
        }

        return response()->json($category, 200);
    }

    /**
     * 
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
        }
        if (!$user->organizations()->find($category->organization_id)) {
            return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengubah kategori ini.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',id,organization_id,' . $category->organization_id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category->update(['name' => $request->name]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category
        ], 200);
    }

    /**
     *
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
        }

        if (!$user->organizations()->find($category->organization_id)) {
            return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk menghapus kategori ini.'], 403);
        }

        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
    }
}