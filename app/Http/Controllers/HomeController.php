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
        $categories = Category::select('categories.*')->get();
        $materials = Material::select('materials.*')->get();

        #ユーザーの持つ食材をDBから取得
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->get();
        #配列でviewに渡す
        $include_materials = [];
        foreach($user_materials as $u){
            array_push($include_materials, $u['material_id']);
        }

        return view('material', compact('categories', 'materials', 'include_materials'));
    }

    public function store(Request $request)
    {
        $posts = $request->all();

        DB::transaction(function() use($posts) {
            UserMaterial::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->delete();
            
            foreach($posts['materials_id'] as $mid){
                UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id()]);
            }
        });
        return redirect( route('material') );
    }

    public function suggest()
    {
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->get();
        $include_materials = [];
        foreach($user_materials as $u){
            array_push($include_materials, $u['material_id']);
        }
        $count = count($include_materials);

        return view('suggest', compact('count'));
    }

    public function user()
    {
        return view('user');
    }

    public function dislike()
    {
        $categories = Category::select('categories.*')->get();
        $materials_all = Material::select('materials.*')->get();

        #非表示の食材を取得
        $hidden_materials = UserMaterial::where('user_id', '=', \Auth::id())->whereNotNull('deleted_at')->get();
        #配列でviewに渡す
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
            UserMaterial::where('user_id', '=', \Auth::id())->whereNotNull('deleted_at')->delete();
            
            foreach($posts['materials_id'] as $mid){
                UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id(), 'deleted_at' => date("Y-m-d H:i:s", time())]);
            }
        });
        return redirect( route('dislike') );
    }
}
