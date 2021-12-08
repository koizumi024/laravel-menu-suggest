@extends('layouts.app')

@section('content')

<div id="app">
    <h1 class="mb-4 text-center">{{ $selectedMenu['menu'] }}</h1>
    <h3 class="head-text mb-3">必要かも？</h3>
    <div class="need__materials mb-4">
        @foreach( $menuMaterials as $mm)
        <div class="need__material justify-content-center
        {{ in_array($mm['material_id'], $includeMaterialsId) == True || in_array($mm['material_id'], $wishlistMaterialsId) == True ? 'd-none' : 'd-flex' }}">
            <div class="need__material-name">
                {{ $mm['material'] }}
            </div>
            <form action="{{ route('addBuy') }}" method="POST">
                @csrf
                <input type="hidden" name="material_id" value="{{ $mm['material_id'] }}">
                <input type="hidden" name="selected_id" value="{{ $selectedMenu['id'] }}">
                <button type="submit" class="smallBtn ml-3 mb-3">
                    <i class="fas fa-shopping-cart mr-2"></i>買い物リストに追加</button>
            </form>
        </div>
        @endforeach
    </div>
    
    
    <div class="rakuten-recipe">
        {{-- <a href="https://recipe.rakuten.co.jp/search/{{ $selectedMenu['menu'] }}" target="_blank">
            <button class="primaryBtn"><i class="fas fa-external-link-alt mr-2"></i>楽天レシピで検索</button>
        </a> --}}

        <h2 class="mb-4 text-center">人気のレシピ</h1>

        @foreach( $recipes as $r)
        <li class="recipes__recipe">
            <a href="https://recipe.rakuten.co.jp/recipe/{{ $recipes[$loop->iteration-1]['rid'] }}" target="_blank">
                <div class="d-flex">
                    <img loading="lazy" class="recipes__recipe-img" src="{{ $recipes[$loop->iteration-1]['img'] }}" alt="レシピ画像">
                    <div class="j" style="background-image: linear-gradient(90deg, rgba(232,228,210, 1), 60%, rgba(232,232,218, 0.66)), url('{{ $recipes[$loop->iteration-1]['img'] }}')">
                        <div class="recipes__recipe-title mx-3 my-2">{{ $recipes[$loop->iteration-1]['title'] }}</div>
                        <div class="recipes__recipe-menu d-flex">
                            <input type="hidden" name="rid" value="{{ $recipes[$loop->iteration-1]['rid'] }}">
                            <div class="kirai">嫌いな食材あり</div>
                            <button class="recipes__fav-btn mr-2"><i class="far fa-star"></i></button>
                        </div>
                        
                        
                    </div>
                </div>
            </a>
        </li>

        @endforeach
    </div>
    
</div>
@endsection