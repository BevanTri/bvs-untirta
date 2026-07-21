<?php

namespace App\Http\Controllers;

use App\Models\BrandPartner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'categories' => Category::where('is_active', true)->get(),
            'products' => Product::where('is_active', true)->available()->with('category')->latest()->take(8)->get(),
            'totalProducts' => Product::where('is_active', true)->available()->count(),
            'services' => Service::where('is_active', true)->get(),
            'brands' => BrandPartner::where('is_active', true)->get(),
        ]);
    }

    public function products(Category $category = null)
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->available()->when($category, fn($q) => $q->where('category_id', $category->id))->with('category')->latest()->paginate(12);
        return view('pages.products', compact('categories', 'products', 'category'));
    }

    public function productDetail(Product $product)
    {
        return view('pages.product-detail', compact('product'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->get();
        return view('pages.services', compact('services'));
    }
}
