<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable){
        $categories = Category::all();
    return $dataTable->render('backend.category.index',compact('categories'));
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = new Category();
        $category->parent_id = $request->parent_id;
        $category->name = $request->name;
        $category->save();
        return response()->json([
            'status'  => true,
            'message' => 'Category Stored successfully',
        ]);
    }

    public function edit(Request $request){
        try {
            $category = Category::find($request->id);



            return response()->json([
                'status'  => true,
                'data'    => $category,
                'message' => 'Category fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function update(Request $request,Category $category){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->save();
        return response()->json([
            'status'  => true,
            'message' => 'Category Updated successfully',
        ]);
    }

    public function delete(Category $category){
        try {
            if ($category->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Category deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "Category not found!"
                ]);
            }


        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // In your CategoryController.php
    public function getByParent(Request $request)
    {

        $parentId = $request->input('parent_id', null);
        $categories = Category::where('parent_id', $parentId)->get();

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }
}
