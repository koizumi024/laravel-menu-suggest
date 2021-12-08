<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class ScrapingController extends Controller
{
    public function scrapingRecipe()
    {
        $client = new Client();
        $keyword = "肉じゃが";
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


        // 配列化
        $recipes=[];
        $length = count($recipeTitles);
        for($i=0; $i<$length; $i++){
            $recipe=["title" => $recipeTitles[$i], "rid" => $recipeIds[$i], "img" => $recipeImgs[$i]];
            array_push($recipes, $recipe);
        }
        
        dd($recipes);
        
        return view('search', compact('recipes'));
    }
}
