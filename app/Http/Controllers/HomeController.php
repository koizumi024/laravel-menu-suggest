<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Models\Category;
use App\Models\Material;
use App\Models\UserMaterial;
use App\Models\MenuMaterial;
use App\Models\Menu;
use App\Models\Buy;
use App\Models\Favorite;

use Goutte\Client;
use meCab\meCab;


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
    public function loadMyMaterials()
    {
        $user_materials = UserMaterial::select('user_materials.*', 'materials.material AS material', 'categories.category AS category', 'materials.image AS image')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->leftJoin('materials', 'materials.id', '=', 'user_materials.material_id')
            ->leftJoin('categories', 'categories.id', '=', 'materials.category_id')
            ->get();

        $includeMaterialsId = [];
        foreach($user_materials as $u){
            array_push($includeMaterialsId, $u['material_id']);
        }

        return view('my-materials', compact('user_materials'));
    }


    public function loadUpdateMaterials()
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

        return view('update-materials', compact('categories', 'materials', 'includeMaterialsId', 'exclude_materials'));
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

        // リダイレクト
        $message = "食材を更新しました";
        return redirect( route('update-materials') )->with('successMessage', $message);
    }



    // ===== dislike.blade.php関連 =====
    // 読み込み時に行う処理
    public function loadDislikeMaterials()
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

        return view('dislike-materials', compact('categories', 'materials_all', 'exclude_materials'));
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
                    UserMaterial::insert([
                        'material_id' => $mid, 
                        'user_id' => \Auth::id(), 
                        'deleted_at' => date("Y-m-d H:i:s", time())
                    ]);

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

        // リダイレクト
        $message = "非表示にする食材を更新しました";
        return redirect( route('dislike-materials') )->with('successMessage', $message);
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

            // menu_materialsテーブルの取得
            $menu_materials = MenuMaterial::select('menu_materials.*')->get();
    
            // メニューの数
            $menusCount = Menu::count();
            // メニューの数だけループ
            $matchResult=[];
            for($i=1; $i <= $menusCount; $i++){
                $matchCount=0;
                $matchPercent=0;
                $dislikeMaterialsId=[];
                $menuMaterialsId=[];

                // 非表示にした食材を取得
                $dislikeMaterials = UserMaterial::where('user_id', '=', \Auth::id())
                    ->whereNotNull('deleted_at')
                    ->get();
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
                    foreach($dislikeMaterials as $dm){
                        array_push($dislikeMaterialsId, $dm['material_id']);
                    }
    
                    // 持っている食材とメニューに含まれる食材の比較
                    foreach($includeMaterialsId as $imi){
                        $result = in_array($imi, $menuMaterialsId, true);
                        if($result){
                            $matchCount++;
                        }
                    }

                    // 非表示にした食材とメニューに含まれる食材の比較（１つでも含まれていたらmatchCountを0にする）
                    $dislikeCount=0;
                    foreach($menuMaterialsId as $mmi){
                        $dislike = in_array($mmi, $dislikeMaterialsId, true);
                        if($dislike){
                            $dislikeCount++;
                        }
                    }

                    if($dislikeCount != 0){
                        $matchCount=0;
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
            // 上位10件に絞り込み
            $sliceResult = array_slice($matchResult, 0, 10);
            // dd($sliceResult);
            
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

            // 画像の取得
            $client = new Client();
            $url = "https://recipe.rakuten.co.jp/search/".$first_key;
            $crawler = $client->request('GET', $url);

            $imgNode = $crawler->filter('.recipe_ranking__img img')->eq(0);
            $panelImage = $imgNode->each(function($element){
                return $element->attr('src');
            });
            
        return view('suggest', compact('sliceResult', 'first_key', 'first_data', 'first_id', 'menu_idName', 'panelImage'));
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

        $wishlistMaterials = Buy::where('user_id', '=', \Auth::id())->get();
        $wishlistMaterialsId = [];
        foreach($wishlistMaterials as $wm){
            array_push($wishlistMaterialsId, $wm['material_id']);
        }

        $client = new Client();
        $keyword = $selectedMenu['menu'];
        $url = "https://recipe.rakuten.co.jp/search/".$keyword;
        $crawler = $client->request('GET', $url);

        // レシピ名を取得
        $titleNode = $crawler->filter('.recipe_ranking__recipe_title');
        $recipeTitles = $titleNode->each(function($element){
            return $element->text();
        });

        // レシピIDを取得
        $idNode = $crawler->filter('.recipe_ranking__item a');
        $recipeIds = $idNode->each(function($element){
            $onlyId = substr($element->attr('href'), 8);
            $onlyId2 = str_replace('/', '', $onlyId);
            return $onlyId2;
        });

        // レシピ画像を取得
        $imgNode = $crawler->filter('.recipe_ranking__img img');
        $recipeImgs = $imgNode->each(function($element){
            return $element->attr('src');
        });

        // 食材を取得
        $materialNode = $crawler->filter('.recipe_ranking__material');
        $recipeMaterials = $materialNode->each(function($element){
            return $element->text();
        });

        // dd($recipeMaterials);

        // 配列化
        $recipes=[];
        $length = count($recipeTitles);
        for($i=0; $i<$length; $i++){
            $recipe=[
                "title" => $recipeTitles[$i], 
                "rid" => $recipeIds[$i],
                "materials" => $recipeMaterials[$i],  
                "img" => $recipeImgs[$i]
            ];
            array_push($recipes, $recipe);
        }
        // dd($recipes);

        // 嫌いな食材が含まれていないかチェック
        // 非表示にした食材を取得
        $dislikeMaterials = UserMaterial::select('user_materials.*', 'materials.material AS material')
            ->where('user_id', '=', \Auth::id())
            ->whereNotNull('deleted_at')
            ->leftJoin('materials', 'materials.id', '=', 'user_materials.material_id')
            ->get();
        
        $dislikeMaterialList = [];
        foreach($dislikeMaterials as $d){
            array_push($dislikeMaterialList, $d['material']);
        }
        // dd($dislikeMaterialList);

        $dislikeMenuList=[];
        for($i=0; $i<$length; $i++){
            $dislikeCount=0;
            foreach($dislikeMaterialList as $d){
                if(strpos($recipes[$i]['materials'], $d) !== false){
                    array_push($dislikeMenuList, $recipes[$i]['rid']);
                }
            }
        }
        // dd($dislikeMenuList);
        
        // お気に入りレシピの取得
        $user_recipes = Favorite::where('user_id', '=', \Auth::id())->get();
        
        $favoritesId = [];
        foreach($user_recipes as $ur){
            array_push($favoritesId, $ur['recipe_id']);
        }
        // dd($favoritesId);
    
        return view('menu', compact('menuMaterials', 'includeMaterialsId', 'selectedMenu', 'wishlistMaterialsId', 'recipes', 'favoritesId', 'dislikeMenuList'));
    }

    public function addWishlist(Request $request)
    {
        $posts = $request->all();
    
        // 買い物リストに追加
        Buy::insert([
            'material_id' => $posts['material_id'], 
            'user_id' => \Auth::id()
        ]);

        // リダイレクト
        $message = "買い物リストに追加しました";
        return redirect( route('menu.index', ['id' => $posts['selected_id'],]) )->with('successMessage', $message);
    }



    // ===== wishlist.blade.php関連 =====
    // 読み込み時に行う処理
    public function loadWishlist()
    {
        // 買い物リストを取得
        $wishlist = Buy::select('buys.*', 'materials.material AS material', 'categories.category AS category', 'materials.image AS image')
            ->where('user_id', '=', \Auth::id())
            ->leftJoin('materials', 'materials.id', '=', 'buys.material_id')
            ->leftJoin('categories', 'categories.id', '=', 'materials.category_id')
            ->get();

        $wishlistCount = Buy::where('user_id', '=', \Auth::id())->count();
        
            // dd($wishlist);

        return view('wishlist', compact('wishlist', 'wishlistCount'));
    }

    // 削除ボタンが押されたら
    public function deleteWishlist(Request $request)
    {
        $posts = $request->all();

        Buy::where('user_id', '=', \Auth::id())
            ->where('material_id', '=', $posts['material_id'])
            ->delete();

        // リダイレクト
        $message = "削除しました";
        return redirect( route('wishlist') )->with('successMessage', $message);
    }

    public function favRecipe(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        $fav_exists = Favorite::where('recipe_id', '=', $posts['rid'])
                        ->where('user_id', '=', \Auth::id())
                        ->exists();

        if( !$fav_exists ){
            Favorite::insert([
            'recipe_id' => $posts['rid'], 
            'recipe_title' => $posts['title'],
            'recipe_image' => $posts['img'],
            'user_id' => \Auth::id()
        ]);
        }else{
            Favorite::where('recipe_id', '=', $posts['rid'])
                ->where('user_id', '=', \Auth::id())
                ->delete();
        }
        
        // リダイレクト
        return redirect( route('menu.index', ['id' => $posts['selected_id']]) );
    }

    public function loadFavorite()
    {
        $user_favorite = Favorite::where('user_id', '=', \Auth::id())->get();

        $favoriteCount = Favorite::where('user_id', '=', \Auth::id())->count();
        
        return view('favorite', compact('user_favorite', 'favoriteCount'));
    }

    public function favRecipe2(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        $fav_exists = Favorite::where('recipe_id', '=', $posts['rid'])
                        ->where('user_id', '=', \Auth::id())
                        ->exists();

        if( !$fav_exists ){
            Favorite::insert([
            'recipe_id' => $posts['rid'], 
            'recipe_title' => $posts['title'],
            'recipe_image' => $posts['img'],
            'user_id' => \Auth::id()
        ]);
        }else{
            Favorite::where('recipe_id', '=', $posts['rid'])
                ->where('user_id', '=', \Auth::id())
                ->delete();
        }
        
        // リダイレクト
        return redirect( route('favorite') );
    }

    public function loadEnd()
    {
        $user_materials = UserMaterial::select('user_materials.*', 'materials.material AS material')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->leftJoin('materials', 'materials.id', '=', 'user_materials.material_id')
            ->get();

        // dd($user_materials);
        return view('end', compact('user_materials'));
    }

    public function deleteMaterial(Request $request)
    {
        $posts = $request->all();
        // dd($posts);

        if(isset($posts['material_id'])){
            foreach($posts['material_id'] as $mid){
                UserMaterial::where('material_id', '=', $mid)
                ->where('user_id', '=', \Auth::id())
                ->delete();
            }
        }
        
        // リダイレクト
        $message = "削除しました";
        return redirect( route('suggest') )->with('successMessage', $message);
    }

    public function deleteMaterial2(Request $request)
    {
        $posts = $request->all();
 
        UserMaterial::where('material_id', '=', $posts['material_id'])
            ->where('user_id', '=', \Auth::id())
            ->delete();
        
        // リダイレクト
        $message = "削除しました";
        return redirect( route('my-materials') )->with('successMessage', $message);
    }

    public function deleteWishlist2(Request $request)
    {
        $posts = $request->all();
 
        Buy::where('material_id', '=', $posts['material_id'])
            ->where('user_id', '=', \Auth::id())
            ->delete();
        
        // リダイレクト
        $message = "削除しました";
        return redirect( route('wishlist') )->with('successMessage', $message);
    }

     // 全ての食材データを削除するボタンが押されたら
     public function clear()
     {
         UserMaterial::where('user_id', '=', \Auth::id())->delete();
 
         // リダイレクト
         $message = "削除しました";
         return redirect( route('my-materials') )->with('successMessage', $message);
     }

     public function clearWishlist()
     {
         Buy::where('user_id', '=', \Auth::id())->delete();
 
         // リダイレクト
         $message = "削除しました";
         return redirect( route('wishlist') )->with('successMessage', $message);
     }
}

