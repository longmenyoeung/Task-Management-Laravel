<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $request->user()->categories()->paginate(10);
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Explain code :
     * 1 .$request->user()
      * Returns the currently authenticated user.
     * 
     * 2. ->categories
     * Calls the categories() relationship defined in your User model
     * 
     * 3. ->create($validated)
     * Creates a new category using the validated data and automatically sets 
     * its user_id to the authenticated user's ID.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' =>  ['nullable', 'string']
        ]);

        $category = $request->user()->categories()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created.',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Category found.',
            'data' => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = $request->user()
                            ->categories()
                            ->find($id);

        if(!$category){
            return response()->json(['message' => "Only owner can update this categories."], 403);
        }


        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string']
        ]);
        
        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated.',
            'data' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,string $id)
    {

        $category = $request->user()
                            ->categories()
                            ->find($id);

        if(!$category){
            return response()->json(['message' => "Only owner can delete this categories."], 403);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted.',
            'id' => $category->id
        ], 200);
    }
}
