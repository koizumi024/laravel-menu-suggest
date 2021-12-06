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
    
    <div class="rakuten-recipe text-center">
        <a href="https://recipe.rakuten.co.jp/search/{{ $selectedMenu['menu'] }}" target="_blank">
        <button class="primaryBtn"><i class="fas fa-external-link-alt mr-2"></i>楽天レシピで検索</button>
    </a>
    </div>
    
</div>
@endsection