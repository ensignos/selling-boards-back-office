<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Merchant;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index(Merchant $merchant)
    {
        return CategoryResource::collection($merchant->categories);
    }

    public function store(Merchant $merchant, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'colour' => ['required', 'string', Rule::in(['RED', 'BLUE', 'GREEN', 'YELLOW', 'ORANGE', 'PURPLE', 'PINK'])],
        ]);

        $category = $merchant->categories()->create($validated);
        
        return CategoryResource::make($category)->response()->setStatusCode(201);
    }

    public function show(Merchant $merchant, Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(Request $request, Merchant $merchant, Category $category)
    {
        $valdiated = $request->validate([
            'name' => ['sometimes', 'filled', 'string', 'max:100'],
            'colour' => ['sometimes', 'filled', 'string', Rule::in(['RED', 'BLUE', 'GREEN', 'YELLOW', 'ORANGE', 'PURPLE', 'PINK'])],
        ]);

        $category->update($valdiated);

        return new CategoryResource($category);
    }

    public function destroy(Merchant $merchant, Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
