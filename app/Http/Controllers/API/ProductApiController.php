<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductApiController extends Controller
{
    /**
     * Menampilkan daftar produk untuk sebuah organisasi.
     * Logika diubah: produk dicari melalui kategori yang dimiliki organisasi.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'organization_id' => 'required|integer|exists:organizations,id',
                'category_id' => 'nullable|integer|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            
            $organizationId = $request->organization_id;

            if (!$user->organizations()->find($organizationId)) {
                return response()->json(['message' => 'Akses ditolak. Anda bukan anggota dari organisasi ini.'], 403);
            }

            // PERBAIKAN: Karena tidak ada 'organization_id' di tabel produk,
            // kita harus mencari produk berdasarkan kategori yang dimiliki organisasi.
            $categoryIds = Category::where('organization_id', $organizationId)->pluck('id');
            
            $query = Product::whereIn('category_id', $categoryIds)->with('category');

            if ($request->has('category_id')) {
                // Pastikan kategori yang difilter juga milik organisasi ini
                if (in_array($request->category_id, $categoryIds->toArray())) {
                    $query->where('category_id', $request->category_id);
                } else {
                    // Jika kategori filter tidak valid, kembalikan array kosong
                    return response()->json([], 200);
                }
            }

            $products = $query->get();

            return response()->json($products, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|string|url',
                'category_id' => 'required|integer|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $category = Category::find($request->category_id);
            $organizationId = $category->organization_id;
            if (!$user->organizations()->find($organizationId)) {
                return response()->json(['message' => 'Akses ditolak. Anda bukan anggota dari organisasi pemilik kategori ini.'], 403);
            }

            $productData = $request->only(['name', 'description', 'stock', 'price', 'image', 'category_id']);
            
            $product = Product::create($productData);

            return response()->json([
                'message' => 'Produk berhasil dibuat.',
                'data' => $product->load('category')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat produk.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 
     */
    public function show($id)
    {
        try {
            $user = auth()->user();
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
            }

            if (!$user->organizations()->find($product->category->organization_id)) {
                return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk melihat produk ini.'], 403);
            }

            return response()->json($product, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil detail produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
            }

            if (!$user->organizations()->find($product->category->organization_id)) {
                return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengubah produk ini.'], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'stock' => 'sometimes|required|integer|min:0',
                'price' => 'sometimes|required|numeric|min:0',
                'image' => 'sometimes|nullable|string|url',
                'category_id' => 'sometimes|required|integer|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if ($request->has('category_id')) {
                $newCategory = Category::find($request->category_id);
                if ($newCategory->organization_id != $product->category->organization_id) {
                    return response()->json([
                        'message' => 'Validasi gagal.',
                        'errors' => ['category_id' => ['Kategori yang dipilih tidak termasuk dalam organisasi ini.']]
                    ], 422);
                }
            }

            $updateData = $request->only(['name', 'description', 'stock', 'price', 'image', 'category_id']);
            $product->update($updateData);

            return response()->json([
                'message' => 'Produk berhasil diperbarui.',
                'data' => $product->load('category')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui produk.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
            }

            if (!$user->organizations()->find($product->category->organization_id)) {
                return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk menghapus produk ini.'], 403);
            }
            
            $product->delete();

            return response()->json(['message' => 'Produk berhasil dihapus.'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus produk.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
}
