<?php

namespace App\Http\Controllers;

use App\Models\aff;
use App\Models\aff_info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        if ($id == "all")
            return $this->JsonResponse(aff_info::leftJoin('affs', 'aff_infos.id', '=', 'affs.aff')
                ->select(DB::raw('id,name,url,count(*) as level'))
                ->groupBy('id')
                ->orderBy('id')
                ->get());
        else
            return $this->JsonResponse(aff_info::leftJoin('affs', 'aff_infos.id', '=', 'affs.aff')
                ->select(DB::raw('id,name,url,count(*) as level'))
                ->where('id', $id)
                ->groupBy('id')
                ->orderBy('id')
                ->get());
    }


    public function level($id)
    {
        if ($id == "all")
            return $this->JsonResponse(aff::all());
        else
            return $this->JsonResponse(aff::where('aff', $id)->get());
    }

    public function random()
    {
        return $this->JsonResponse(aff_info::leftJoin('affs', 'aff_infos.id', '=', 'affs.aff')
            ->inRandomOrder()
            ->first());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = aff_info::insertGetId([
            'name' => $request->name,
            'url' => $request->url,
        ]);
        for ($i = 0; $i < $request->level; $i++) {
            aff::insert([
                'aff' => $id,
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(aff_info $aff_info)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        aff_info::where('id', $id)->update([
            'name' => $request->name,
            'url' => $request->url,
        ]);

        aff::where('aff', $id)->delete();
        for ($i = 0; $i < $request->level; $i++) {
            aff::insert([
                'aff' => $id,
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = aff_info::find($id);
        $category->delete();
        aff::where('aff', $id)->delete();
    }
}