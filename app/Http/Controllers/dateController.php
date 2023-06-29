<?php

namespace App\Http\Controllers;

use App\Models\create_date;
use Carbon\Carbon;
use Illuminate\Http\Request;

class dateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($open, $close)
    {
        return $this->JsonResponse(create_date::where('created_at', '>=', $open)->
            where('created_at', '<=', $close)->count());
    }
    public function get()
    {
        return $this->JsonResponse(create_date::all());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(create_date $create_date)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, create_date $create_date)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(create_date $create_date)
    {
        //
    }
}