<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Material;
use App\Models\UserMaterial;

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

        #ユーザーの持つ食材を取得
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->get();
        $include_materials = [];
        foreach($user_materials as $u){
            array_push($include_materials, $u['material_id']);
        }

        return view('material', compact('categories', 'materials', 'include_materials'));
    }

    public function store(Request $request)
    {
        $posts = $request->all();
        // dd($posts);
        UserMaterial::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->delete();
        
        foreach($posts['materials_id'] as $mid){
            UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id()]);
        }
        return redirect( route('material') );
    }

    public function user(){
        return view('user');
    }

    public function suggest(){
        return view('suggest');
    }
}
