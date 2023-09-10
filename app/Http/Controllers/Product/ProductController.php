<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Price is an integer of the penny value
     *
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all([
            'title',
            'base_currency',
            'price',
        ]);

        $product = Product::create($data);

        return new ProductResource($product);
    }
}
