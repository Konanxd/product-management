<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('pages.report');
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

        $totalInventoryWorth = $products->sum('price');
        $totalItems = $products->count('id');
        $mostStockItem = $products->sortByDesc('stock')->first();
        $mostStockCategory = Category::withSum('products', 'stock')
            ->orderByDesc('products_sum_stock')
            ->first();

        return response()->json([
            'user' => $user,
            'organizations' => $org,
            'totalInventoryWorth' => $totalInventoryWorth,
            'totalItems' => $totalItems,
            'mostStockItem' => $mostStockItem,
            'mostStockCategory' => $mostStockCategory,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
