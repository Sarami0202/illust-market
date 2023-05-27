<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Illust;
use App\Models\Illust_Category;
use App\Models\Illust_Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IllustController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        if ($id == "all")
            return $this->JsonResponse(Illust::select("*")
                ->orderBy("id", "desc")
                ->get());
        else
            return $this->JsonResponse(Illust::where('id', $id)->get());
    }

    public function getIllust($name, $num, $page)
    {

        if ($num == 0)
            return $this->JsonResponse(Illust::Where('name', 'like', '%' . $name . '%')
                ->orderBy('id', 'desc')
                ->get());
        else {
            $name = str_replace('　', ' ', $name); // 全角の空白文字を半角スペースに置換
            $keywords = preg_split('/\s+/', $name); // 空白文字でキーワードを分割
            $I_query = Illust::query(); //名前のみ
            $T_query = Illust_Tags::query()->leftJoin('illusts', 'illusts.id', '=', 'illust__tags.illust'); //タグのみ
            $IT_query1 = Illust::query(); //名前とタグのみ(名前)
            $IT_query2 = Illust_Tags::query()->leftJoin('illusts', 'illusts.id', '=', 'illust__tags.illust'); //名前とタグのみ(タグ)

            foreach ($keywords as $keyword) {
                $I_query->where('illusts.name', 'like', '%' . $keyword . '%');
                $T_query->orWhere('tags', $keyword);
                $IT_query2->orWhere('tags', $keyword);
                $IT_query1->orWhere('illusts.name', 'like', '%' . $keyword . '%');
            }

            $IT_query2->select('illusts.id', 'illusts.illust', 'illusts.name', 'prompt', 'negative', 'seed', 'alt')
                ->groupBy('illusts.id', 'illusts.illust', 'illusts.name', 'prompt', 'negative', 'seed', 'alt')
                ->havingRaw('COUNT(illusts.id) >= ' . (count($keywords) - 1));

            $IT_query1->joinSub($IT_query2, 't', function ($join) {
                $join->on('illusts.id', '=', 't.id');
            })->select('illusts.id', 'illusts.illust', 't.name', 't.prompt', 't.negative', 't.seed', 't.alt')
                ->groupBy('illusts.id', 'illusts.illust', 't.name', 't.prompt', 't.negative', 't.seed', 't.alt');

            $T_query->select('illusts.id', 'illusts.illust', 'illusts.name', 'prompt', 'negative', 'seed', 'alt')
                ->groupBy('illusts.id', 'illusts.illust', 'name', 'prompt', 'negative', 'seed', 'alt')
                ->havingRaw('COUNT(DISTINCT tags) = ' . count($keywords));

            $query = $IT_query1->union($I_query)->union($T_query);
            return $this->JsonResponse($query->orderBy('id', 'desc')
                ->offset($num * ($page - 1))
                ->limit($num)
                ->get());
        }
        ;

    }
    public function Count($name)
    {
        $name = str_replace('　', ' ', $name); // 全角の空白文字を半角スペースに置換
        $keywords = preg_split('/\s+/', $name); // 空白文字でキーワードを分割
        $I_query = Illust::query(); //名前のみ
        $T_query = Illust_Tags::query()->leftJoin('illusts', 'illusts.id', '=', 'illust__tags.illust'); //タグのみ
        $IT_query1 = Illust::query(); //名前とタグのみ(名前)
        $IT_query2 = Illust_Tags::query()->leftJoin('illusts', 'illusts.id', '=', 'illust__tags.illust'); //名前とタグのみ(タグ)

        foreach ($keywords as $keyword) {
            $I_query->where('illusts.name', 'like', '%' . $keyword . '%');
            $T_query->orWhere('tags', $keyword);
            $IT_query2->orWhere('tags', $keyword)
                ->where('illusts.name', 'not like', '%' . $keyword . '%');
            $IT_query1->orWhere('illusts.name', 'like', '%' . $keyword . '%');
        }

        $IT_query2->select('illusts.id', 'illusts.illust', 'illusts.name', 'prompt', 'negative', 'seed', 'alt')
            ->groupBy('illusts.id', 'illusts.illust', 'illusts.name', 'prompt', 'negative', 'seed', 'alt')
            ->havingRaw('COUNT(illusts.id) >= ' . (count($keywords) - 1));

        $IT_query1->joinSub($IT_query2, 't', function ($join) {
            $join->on('illusts.id', '=', 't.id');
        })->select('illusts.id', 'illusts.illust', 't.name', 't.prompt', 't.negative', 't.seed', 't.alt')
            ->groupBy('illusts.id', 'illusts.illust', 't.name', 't.prompt', 't.negative', 't.seed', 't.alt');

        $T_query->select('illusts.id', 'illusts.illust', 'illusts.name', 'prompt', 'negative', 'seed', 'alt')
            ->groupBy('illusts.id', 'illusts.illust', 'name', 'prompt', 'negative', 'seed', 'alt')
            ->havingRaw('COUNT(DISTINCT tags) = ' . count($keywords));

        $query = $IT_query1->union($I_query)->union($T_query);
        return $this->JsonResponse($query->orderBy('id', 'desc')
            ->get()
            ->count());
    }


    public function recommendIllust($num, $date)
    {
        return $this->JsonResponse(Download::leftJoin('illusts', 'illusts.id', '=', 'downloads.illust')
            ->select(DB::raw('illusts.illust, illusts.name, illusts.id,count(*) as count'))
            ->where('created_at', '>=', date("Y-m-d H:i:s", strtotime('-' . $date . 'day')))
            ->groupBy('illusts.id', 'illusts.illust', 'illusts.name')
            ->orderBy('count', 'desc')
            ->limit($num)
            ->get());
    }

    public function connectIllust($category, $num)
    {
        return $this->JsonResponse(Illust_Category::leftJoin('illusts', 'illusts.id', '=', 'illust__categories.illust')
            ->Where('category', $category)
            ->orderBy('id', 'desc')
            ->limit($num)
            ->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $illust = null;
        if ($request['illust'] != null) {
            $illust_file = $request->file('illust');
            $illust = isset($illust_file) ? $illust_file->store('illust', 'public') : null;
        }
        $id = Illust::insertGetId([
            'illust' => $illust,
            'name' => $request->name,
            'alt' => $request->alt,
            'prompt' => $request->prompt,
            'negative' => $request->negative,
            'seed' => $request->seed,
        ]);
        return response()->json(['id' => $id], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Illust $illust)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Illust::where('id', $id)->update([
            'name' => $request->name,
            'alt' => $request->alt,
            'prompt' => $request->prompt,
            'negative' => $request->negative,
            'seed' => $request->seed,
        ]);
        Illust_Category::where('illust', $id)->delete();
        Illust_Tags::where('illust', $id)->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Illust::find($id);
        $category->delete();
        Illust_Category::where('illust', $id)->delete();
        Illust_Tags::where('illust', $id)->delete();
    }
}