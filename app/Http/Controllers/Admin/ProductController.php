<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseController
{
    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->baseService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $search = $request->get('search');
        $filters = $request->get('filters');
        $products = $this->baseService->getAll($search, $filters);
        return ProductResource::collection($products);
    }


    /**
     * @param ProductRequest $request
     * @return ProductResource|\Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = $this->baseService->store($request->all());
        return new ProductResource($product);
    }

    /**
     * @param Product $Product
     * @return ProductResource
     */
    public function get(Product $product)
    {
        return new ProductResource($product);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->baseService->update($product, $request->all());
        return new ProductResource($product);
    }


    /**
     * @param Product $Product
     * @return void
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->noContent();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . $exception->getFile() . $exception->getLine());
        }

    }


}
