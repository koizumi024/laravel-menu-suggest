<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class ScrapingController extends Controller
{
    public function index()
    {
        $url = "https://recipe.rakuten.co.jp/";
        $client = new Client();
        $crawler = $client->request('GET', $url);

        // アクセス結果からタイトルタグのテキストを取得
        $titleNode = $crawler->filter('title');
        $title = $titleNode->text();

        return view('search', compact('title'));
    }
}
