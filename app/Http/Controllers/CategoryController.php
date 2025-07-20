<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.category.index');
    }

    public function create()
    {
        return view('pages.category.create');
    }

    public function edit()
    {
        return view('pages.category.edit');
    }

    public function data()
    {
        $auth = auth('api')->user();
        $user = User::find($auth['id']);

        $org = $user->organizations;

        $categories = Category::where('organization_id', $org[0]['id'])
            ->withSum('products as total_stock', 'stock')
            ->get();

        return response()->json([
            'user' => $user,
            'organizations' => $org,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'orgId' => 'required|string',
        ]);

        Category::create([
            'name' => $request->name,
            'organization_id' => $request->orgId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($request->id);
        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diubah',
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
