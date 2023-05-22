<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Illust_Category;
use Illuminate\Http\Request;

class IllustCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getCategory($id)
    {
        if ($id == "all")
            return $this->JsonResponse(Illust_Category::all());
        else
            return $this->JsonResponse(Illust_Category::where('illust', $id)->get());
    }

    public function getIllust($id, $num, $page)
    {

        if ($num == 0) {
            $category = Category::select('id')
                ->Where('name', $id)
                ->get();

            return $this->JsonResponse(Illust_Category::leftJoin('illusts', 'illusts.id', '=', 'illust__categories.illust')
                ->Where('category', $category[0]["id"])
                ->orderBy('id', 'desc')
                ->get());
        } else
            return $this->JsonResponse(Illust_Category::leftJoin('illusts', 'illusts.id', '=', 'illust__categories.illust')
                ->Where('category', $id)
                ->orderBy('id', 'desc')
                ->offset($num * ($page - 1))
                ->limit($num)
                ->get());

    }

    public function Count($id)
    {
        return $this->JsonResponse(Illust_Category::where('category', $id)->count());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Illust_Category::insert([
            'illust' => $request->illust,
            'category' => $request->category,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Illust_Category $illust_Category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Illust_Category $illust_Category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Illust_Category $illust_Category)
    {
        //
    }
}