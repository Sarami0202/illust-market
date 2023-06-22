<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getFavorite()
    {
        return $this->JsonResponse(Favorite::leftJoin('illusts', 'illusts.id', '=', 'favorites.illust')
        ->orderBy('favorites.id', 'desc')
        ->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function InsertFavorite(Request $request)
    {
        Favorite::insert([
            'illust' => $request->illust
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Favorite::where('illust', $id)->delete();
    }
}
