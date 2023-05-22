<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Illust;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index($id)
    {
        if ($id == "all")
            return $this->JsonResponse(Download::all());
        else
            return $this->JsonResponse(Download::where('illust', $id)->get());
    }
    public function store(Request $request)
    {
        Download::insert([
            'illust' => $request->illust,
            'created_at' => Carbon::now(),
        ]);
    }
    public function download($id)
    {
        $illust = Illust::where('id', $id)->first();
        $filePath = 'public/' . $illust->illust;
        $fileName = str_replace('illust/', '', $illust->illust);
        $mimeType = Storage::mimeType($filePath);
        $headers = [['Content-Type' => $mimeType]];

        return Storage::download($filePath, $fileName, $headers);
    }
}