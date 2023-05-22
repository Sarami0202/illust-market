<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Illust_Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        if ($id == "all")
        return $this->JsonResponse(Category::select("*")
            ->orderBy("id", "desc")
            ->get());
    else
        return $this->JsonResponse(Category::where('id', $id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Category::insert([
            'name' => $request->name,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Category::where('id', $id)->update([
            'name' => $request->name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        Illust_Category::where('category',$id)->delete();
    }
}
