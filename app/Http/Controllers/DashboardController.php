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

        $organizationIds = $user->organizations->pluck('id');

        if ($organizationIds->isEmpty()) {
            return response()->json([
                'user' => $user,
                'organizations' => [],
                'totalProduct' => 0,
                'lowStockProducts' => 0,
                'emptyStockProducts' => 0,
                'recentProducts' => []
            ], 200);
        }

        $productsQuery = Product::whereHas('category', function ($query) use ($organizationIds) {
            $query->whereIn('organization_id', $organizationIds);
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
            'recentProducts' => $recentProducts
        ], 200);
    }
}
