<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $expenseCategories = Category::expense()
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $incomeCategories = Category::income()
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('categories.index', compact('expenseCategories', 'incomeCategories'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'expense');
        return view('categories.create', compact('type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:expense,income',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['is_active'] = true;

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:expense,income',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing transactions. Deactivate it instead.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Category {$status} successfully!");
    }
}
