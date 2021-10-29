<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Material;
use App\Models\UserMaterial;
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
    public function index()
    {
        #カテゴリ一覧を取得
        $categories = Category::select('categories.*')->get();
        #食材一覧を取得
        $materials = Material::select('materials.*')->get();

        #ユーザーの持つ食材をDBから取得
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->get();

        #チェックボックス用の配列
        $include_materials = [];
        foreach($user_materials as $u){
            array_push($include_materials, $u['material_id']);
        }

        #非表示食材を取得
        $hidden_materials = UserMaterial::where('user_id', '=', \Auth::id())
        ->whereNotNull('deleted_at')
        ->get();
        #viewに渡す用の配列
        $exclude_materials = [];
        foreach($hidden_materials as $h){
            array_push($exclude_materials, $h['material_id']);
        }

        return view('material', compact('categories', 'materials', 'include_materials', 'exclude_materials'));
    }

    public function store(Request $request)
    {
        $posts = $request->all();

        DB::transaction(function() use($posts) {
            UserMaterial::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->delete();
            
            #食材を追加（食材が１つもなければ追加する処理はしないので分岐）
            if(isset($posts['materials_id'])){
                foreach($posts['materials_id'] as $mid){
                    UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id()]);
                }
            }
        });
        return redirect( route('material') )->with('successMessage', '食材を更新しました');
    }

    public function suggest()
    {
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->get();

        $include_materials = [];
        foreach($user_materials as $u){
            array_push($include_materials, $u['material_id']);
        }
        #今持っている食材の数
        $count = count($include_materials);

        return view('suggest', compact('count'));
    }

    public function user()
    {
        return view('user');
    }

    public function dislike()
    {
        #カテゴリ一覧を取得
        $categories = Category::select('categories.*')->get();
        #食材一覧を全て取得
        $materials_all = Material::select('materials.*')->get();

        #非表示食材を取得
        $hidden_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNotNull('deleted_at')
            ->get();
        #viewに渡す用の配列
        $exclude_materials = [];
        foreach($hidden_materials as $h){
            array_push($exclude_materials, $h['material_id']);
        }

        return view('dislike', compact('categories', 'materials_all', 'exclude_materials'));
    }

    public function dstore(Request $request)
    {
        $posts = $request->all();

        DB::transaction(function() use($posts) {
            #非表示食材を全て削除
            UserMaterial::where('user_id', '=', \Auth::id())
                ->whereNotNull('deleted_at')
                ->delete();
            
            #非表示食材の追加（食材が１つもなければ追加する処理はしないので分岐）
            if(isset($posts['materials_id'])){
                foreach($posts['materials_id'] as $mid){
                    UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id(), 'deleted_at' => date("Y-m-d H:i:s", time())]);
                }
            }
        });
        return redirect( route('dislike') )->with('successMessage', '非表示にする食材を更新しました');
    }

    public function clear()
    {
        UserMaterial::where('user_id', '=', \Auth::id())->delete();
    
        return redirect( route('user') )->with('successMessage', '全ての食材データを削除しました');
    }

    public function menuSuggest()
    {
        #ユーザーの持つ食材をDBから取得
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->get();
            
        return redirect( route('suggest') );
    }
}