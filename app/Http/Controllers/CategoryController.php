<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    public function store(CategoryCreateRequest $request): CategoryResource
    {
        $category = Category::create($request->all());
        return (new CategoryResource($category))->additional([
            'message' => 'Category created.'
        ]);
    }

    public function update(CategoryUpdateRequest $request, Category $category): CategoryResource
    {
        $category->update($request->all());
        $category->save();
        return (new CategoryResource($category))->additional([
            'message' => 'Category updated.'
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted.']);
    }
}
