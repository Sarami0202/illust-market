<?php

namespace App\Http\Controllers;

use App\Models\Illust;
use App\Models\Illust_Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IllustTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getTags($id)
    {
        if ($id == "all")
            return $this->JsonResponse(Illust_Tags::all());
        else
            return $this->JsonResponse(Illust_Tags::where('illust', $id)->get());
    }

    public function getIllust($tag, $num, $page)
    {

        if ($num == 0)
            return $this->JsonResponse(Illust_Tags::leftJoin('illusts', 'illusts.id', '=', 'illust__tags.illust')
                ->Where('tags', $tag)
                ->orderBy('id', 'desc')
                ->get());
        else
            return $this->JsonResponse(Illust_Tags::leftJoin('illusts', 'illusts.id', '=', 'illust__tags.illust')
                ->select(DB::raw('illusts.illust, illusts.name, illusts.id'))
                ->orWhere('tags', $tag)
                ->orWhere('illusts.name', 'like', '%' . $tag . '%')
                ->groupBy('id')
                ->orderBy('id', 'desc')
                ->offset($num * ($page - 1))
                ->limit($num)
                ->get());
    }

    public function Count($tag)
    {
        return $this->JsonResponse(Illust_Tags::leftJoin('illusts', 'illusts.id', '=', 'illust__tags.illust')
            ->select(DB::raw('count(*) as count'))
            ->orWhere('tags', $tag)
            ->orWhere('illusts.name', 'like', '%' . $tag . '%')
            ->groupBy('id')
            ->get()
            ->count());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Illust_Tags::insert([
            'illust' => $request->illust,
            'tags' => $request->tags,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Illust_Tags $illust_Tags)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Illust_Tags $illust_Tags)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Illust_Tags $illust_Tags)
    {
        //
    }
}