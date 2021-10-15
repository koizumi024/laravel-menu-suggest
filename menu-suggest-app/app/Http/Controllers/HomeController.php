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
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())->get();
        $include_materials = [];
        foreach($user_materials as $u){
            array_push($include_materials, $u['material_id']);
        }

        return view('home', compact('categories', 'materials', 'include_materials'));
    }
    public function store(Request $request)
    {
        $posts = $request->all();
        // dd($posts);
        UserMaterial::where('user_id', '=', \Auth::id())->delete();
        
        foreach($posts['materials_id'] as $mid){
            UserMaterial::insert(['material_id' => $mid, 'user_id' => \Auth::id()]);
        }
        return redirect( route('home') );
    }
}
