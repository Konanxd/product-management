<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    public function data()
    {
        $user = auth('api')->user();

        $orgId = $user->organizations->pluck('id');

        if ($orgId->isEmpty()) {
            return response()->json([
                'user' => $user,
                'organizations' => [],
                'totalProduct' => 0,
                'lowStockProducts' => 0,
                'emptyStockProducts' => 0,
                'recentProducts' => []
            ], 200);
        }

        $products = Product::with('category')
            ->whereHas('category', function ($query) use ($orgId) {
                $query->where('organization_id', $orgId);
            })
            ->latest()
            ->get();

        $productsQuery = Product::whereHas('category', function ($query) use ($orgId) {
            $query->whereIn('organization_id', $orgId);
        });

        $totalProduct = $productsQuery->count();
        $lowStockProducts = $productsQuery->clone()->where('stock', '<=', 5)->count();
        $emptyStockProducts = $productsQuery->clone()->where('stock', '=', 0)->count();

        $recentProducts = $productsQuery->clone()->latest()->limit(5)->get();

        return response()->json([
            'user' => $user,
            'organizations' => $user->organizations,
            'totalProduct' => $totalProduct,
            'lowStockProducts' => $lowStockProducts,
            'emptyStockProducts' => $emptyStockProducts,
            'recentProducts' => $recentProducts,
            'products' => $products
        ], 200);
    }
}
