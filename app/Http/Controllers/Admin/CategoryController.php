<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends BaseController
{
    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->baseService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(Category::paginate('15', ['id', 'title', 'created_at']));
    }


    /**
     * @param CategoryRequest $request
     * @return CategoryResource|\Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->baseService->store($request->all());
            return new CategoryResource($category);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . $exception->getFile() . $exception->getLine());
        }

    }

    /**
     * @param Category $category
     * @return CategoryResource
     */
    public function get(Category $category)
    {
        return new CategoryResource($category);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category = $this->baseService->update($category, $request->all());
            return new CategoryResource($category);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . $exception->getFile() . $exception->getLine());
        }
    }


    /**
     * @param Category $category
     * @return void
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->noContent();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . $exception->getFile() . $exception->getLine());
        }

    }


}
