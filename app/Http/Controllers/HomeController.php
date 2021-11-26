<?php

namespace App\Http\Controllers;

use App\Models\Buy;
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
    

    // ===== material.blade.php関連 =====
    // 読み込み時に行う処理
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

    // 食材を更新した際に行う処理
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

        // リダイレクト後に表示されるメッセージ
        $message = "食材を更新しました";

        return redirect( route('material') )->with('successMessage', $message);
    }



    // ===== setting.blade.php関連 =====
    // 読み込み時に行う処理
    public function loadSetting()
    {
        return view('setting');
    }

    // 全ての食材データを削除するボタンが押されたら
    public function clear()
    {
        UserMaterial::where('user_id', '=', \Auth::id())->delete();

        // リダイレクト後に表示されるメッセージ
        $message = "全ての食材データを削除しました";
    
        return redirect( route('setting') )->with('successMessage', $message);
    }



    // ===== dislike.blade.php関連 =====
    // 読み込み時に行う処理
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

    // 食材を更新した際に行う処理
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

        // リダイレクト後に表示されるメッセージ
        $message = "非表示にする食材を更新しました";

        return redirect( route('dislike') )->with('successMessage', $message);
    }

        

    // ===== suggest.blade.php関連 =====
    // 読み込み時に行う処理
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
                // マッチ率の分母を取得（計算用）
                $menuMaterialCount = MenuMaterial::where('menu_id', '=', $i)->count();
                // メニュー名を取得
                $menuName = Menu::where('id', '=', $i)->get();
                
                if($menuMaterialCount != 0){
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

                    // 計算
                    $matchPercent = intval(round($matchCount/$menuMaterialCount, 2)*100);
                    // 配列に入れる
                    $matchResult += array($menuName[0]['menu'] => $matchPercent);
                }else{
                    // menu_materialsに登録されていないメニューがあったら強制的にマッチ率を0にする
                    $matchResult += array($menuName[0]['menu'] => 0);
                }
            }
                
            // $matchResultはマッチ率の高い順に並び替える
            arsort($matchResult);
            //　一番上のデータの取得
            foreach($matchResult as $key => $data){
                $first_key = $key;
                $first_data = $data;
                //メニューIDを取得
                $firstmenu = Menu::select('id')->where('menu', '=', $first_key)->get();
                $first_id = $firstmenu[0]['id'];
                break;
            }

            // メニューidを提案順に並べた配列を取得
            $menu_idName=[];
            $j=0;
            foreach($matchResult as $key => $data){
                $menuId = Menu::select('id')->where('menu', '=', $key)->get();
                $menu_id = $menuId[0]['id'];
                $menu_idName += array($j => $menu_id);
                $j++;
            }
            //dd($menu_idName);
            //dd($matchResult);

        return view('suggest', compact('count', 'matchResult', 'first_key', 'first_data', 'first_id', 'menu_idName'));
    }



    // ===== menu.blade.php関連 =====
    // 読み込み時に行う処理
    public function loadMenuDetail($id){
        // ユーザーの持つ食材をDBから取得
        $user_materials = UserMaterial::where('user_id', '=', \Auth::id())
        ->whereNull('deleted_at')
        ->get();

        // チェックボックス用の配列
        $includeMaterialsId = [];
        foreach($user_materials as $u){
            array_push($includeMaterialsId, $u['material_id']);
        }

        //　$idの食材を取得
        $menuMaterials = MenuMaterial::select('menu_materials.*', 'materials.material AS material')
            ->where('menu_id', '=', $id)
            ->leftJoin('materials', 'materials.id', '=', 'menu_materials.material_id')
            ->get();
        
        $selectedMenu = Menu::find($id);

        return view('menu', compact('menuMaterials', 'includeMaterialsId', 'selectedMenu'));
    }

    public function addBuy(Request $request)
    {
        $posts = $request->all();
    
        // 買い物リストに追加
        Buy::insert(['material_id' => $posts['material_id'], 'user_id' => \Auth::id()]);

        // リダイレクト後に表示されるメッセージ
        $message = "買い物リストに追加しました";
        
        return redirect( route('menu.index', ['id' => $posts['selected_id'],]) )->with('successMessage', $message);
    }



    // ===== wishlist.blade.php関連 =====
    // 読み込み時に行う処理
    public function loadWishlist()
    {
        // 買い物リストを取得
        $wishlist = Buy::select('buys.*', 'materials.material AS material')
            ->where('user_id', '=', \Auth::id())
            ->leftJoin('materials', 'materials.id', '=', 'buys.material_id')
            ->get();

        return view('wishlist', compact('wishlist'));
    }

    // 削除ボタンが押されたら
    public function wishlistDelete(Request $request)
    {
        $posts = $request->all();

        Buy::where('user_id', '=', \Auth::id())
        ->where('material_id', '=', $posts['material_id'])
        ->delete();

        // リダイレクト後に表示されるメッセージ
        $message = "削除しました";

        return redirect( route('wishlist') )->with('successMessage', $message);
    }
}

