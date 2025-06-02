<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // // Total Produk
        // $totalProducts = Product::count();

        // // Total Kategori
        // $totalCategories = Category::count();

        // // Total Stok Semua Produk
        // $totalStock = Product::sum('stock');

        // // Produk dengan Stok Kurang dari atau Sama dengan 5
        // $lowStockProducts = Product::where('stock', '<=', 5)->count();

        // return view('dashboard.index', compact(
        //     'totalProducts',
        //     'totalCategories',
        //     'totalStock',
        //     'lowStockProducts'
        // ));

        return view('pages.dashboard');
    }
}
