<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Material;
use App\Models\UserMaterial;
use App\Models\MenuMaterial;
use App\Models\Menu;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // 1. 食材管理ページ読み込み時に行う処理
    public function loadMaterial()
    {
        // カテゴリ一覧を取得
        $categories = Category::select('categories.*')->get();
        // 食材一覧を取得
        $materials = Material::select('materials.*')->get();

        // ユーザーの持つ食材をDBから取得
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->get();

        // チェックボックス用の配列
        $includeMaterialsId = [];
        foreach($user_materials as $u){
            array_push($includeMaterialsId, $u['material_id']);
        }

        // 非表示食材を取得
        $hidden_materials = UserMaterial::where('user_id', '=', \Auth::id())
        ->whereNotNull('deleted_at')
        ->get();
        // viewに渡す用の配列
        $exclude_materials = [];
        foreach($hidden_materials as $h){
            array_push($exclude_materials, $h['material_id']);
        }

        return view('material', compact('categories', 'materials', 'includeMaterialsId', 'exclude_materials'));
    }

    // 1-1. 食材管理ページで食材を更新した際に行う処理
    public function store(Request $request)
    {
        $posts = $request->all();

        DB::transaction(function() use($posts) {
            UserMaterial::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->delete();
            
            // 食材を追加（食材が１つもなければ追加する処理はしないので分岐）
            if(isset($posts['materials_id'])){
                foreach($posts['materials_id'] as $mid){
                    UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id()]);
                }
            }
        });
        return redirect( route('material') )->with('successMessage', '食材を更新しました');
    }

    // 2. メニュー提案ページ読み込み時に行う処理
    public function loadSuggest()
    {
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->get();

        $includeMaterialsId = [];
        foreach($user_materials as $u){
            array_push($includeMaterialsId, $u['material_id']);
        }
        // 今持っている食材の数
        $count = count($includeMaterialsId);

        return view('suggest', compact('count'));
    }

    // 3. ユーザー設定ページ読み込み時に行う処理
    public function loadSetting()
    {
        return view('setting');
    }

    // 3-1. 全ての食材データを削除した場合の処理
    public function clear()
    {
        UserMaterial::where('user_id', '=', \Auth::id())->delete();
    
        return redirect( route('setting') )->with('successMessage', '全ての食材データを削除しました');
    }

    // 4. 非表示食材管理ページ読み込み時に行う処理
    public function dislike()
    {
        // カテゴリ一覧を取得
        $categories = Category::select('categories.*')->get();
        // 食材一覧を全て取得
        $materials_all = Material::select('materials.*')->get();

        // 非表示食材を取得
        $hidden_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNotNull('deleted_at')
            ->get();
        // viewに渡す用の配列
        $exclude_materials = [];
        foreach($hidden_materials as $h){
            array_push($exclude_materials, $h['material_id']);
        }

        return view('dislike', compact('categories', 'materials_all', 'exclude_materials'));
    }

    // 4-1. 非表示食材管理ページで食材を更新した際に行う処理
    public function dstore(Request $request)
    {
        $posts = $request->all();

        DB::transaction(function() use($posts) {
            // 非表示食材を全て削除
            UserMaterial::where('user_id', '=', \Auth::id())
                ->whereNotNull('deleted_at')
                ->delete();
            
            // 非表示食材の追加（食材が１つもなければ追加する処理はしないので分岐）
            if(isset($posts['materials_id'])){
                foreach($posts['materials_id'] as $mid){
                    UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id(), 'deleted_at' => date("Y-m-d H:i:s", time())]);

                    $null_exists = UserMaterial::where('user_id', '=', \Auth::id())
                        ->where('material_id', '=', $mid)
                        ->whereNull('deleted_at')
                        ->exists();

                    if( $null_exists ){
                        UserMaterial::where('user_id', '=', \Auth::id())
                        ->where('material_id', '=', $mid)
                        ->whereNull('deleted_at')
                        ->delete();
                    }
                }
            }
        });
        return redirect( route('dislike') )->with('successMessage', '非表示にする食材を更新しました');
    }

    // 2-1. メニュー提案リクエストがあった場合の処理
    public function menuSuggest()
    {
        // ユーザーの持つ食材をDBから取得
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->get();

        $includeMaterialsId = [];
        foreach($user_materials as $u){
            array_push($includeMaterialsId, $u['material_id']);
        }
        
        // menu_materialsテーブルの取得
        $menu_materials = MenuMaterial::select('menu_materials.*')->get();

        // メニューの数
        $menusCount = Menu::count();
        // メニューの数だけループ
        $matchResult=[];
        for($i=1; $i <= $menusCount; $i++){
            $matchCount=0;
            $matchPercent=0;
            $menuMaterialsId=[];
            //メニュー別に食材を取得
            $menuMaterials = MenuMaterial::where('menu_id', '=', $i)->get();
            foreach($menuMaterials as $m){
                array_push($menuMaterialsId, $m['material_id']);
            }

            // 比較する
            foreach($includeMaterialsId as $ii){
                $result = in_array($ii, $menuMaterialsId);
                // 一致していたら
                if($result){
                    $matchCount++;
                }
            }
            // マッチ率の分母を取得（計算用）
            $menuMaterialCount = MenuMaterial::where('menu_id', '=', $i)->count();

            // 計算
            $matchPercent = intval(round($matchCount/$menuMaterialCount, 2)*100);
            // 配列に入れる
            $menuName = Menu::where('id', '=', $i)->get();
            $matchResult += array($menuName[0]['menu'] => $matchPercent);
        }
        // TODO $matchResultはマッチ率の高い順に並び替える
        arsort($matchResult);
        dd($matchResult);

        return redirect( route('suggest') );
    }
}