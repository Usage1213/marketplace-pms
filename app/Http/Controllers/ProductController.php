<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate as FacadesGate;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        // Create a new product using the validated data
        $product = Product::create($validatedData);

        // Return a JSON response with the created product
        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        FacadesGate::authorize('modify', $product);
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'image_url' => 'nullable|string',
            'stock_quantity' => 'sometimes|integer|min:0',
        ]);

        // Update the product with the validated data
        $product->update($validatedData);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        FacadesGate::authorize('modify', $product);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!'
        ]);
    }
}
