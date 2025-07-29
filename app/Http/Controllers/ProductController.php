<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.product.index');
    }

    public function data()
    {
        $auth = auth('api')->user();
        $user = User::find($auth['id']);

        $org = $user->organizations;

        $categories = Category::where('organization_id', $org[0]['id'])
            ->get();

        $products = Product::with('category')
            ->whereHas('category', function ($query) use ($org) {
                $query->where('organization_id', $org[0]['id']);
            })
            ->latest()
            ->get();

        return response()->json([
            'user' => $user,
            'organizations' => $org,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'categoryId' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url'
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : $request->image_url;

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->categoryId,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'categoryId' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url'
        ]);
        $product = Product::findOrFail($request->id);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        } elseif ($request->image_url) {
            $imagePath = $request->image_url;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->categoryId,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diubah',
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $product = Product::findOrFail($request->id);
        if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
