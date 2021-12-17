@extends('layouts.app')

@section('content')

<div id="app" class="container-md">
    <h1 class="mb-4 pt-4 text-center">{{ $selectedMenu['menu'] }}</h1>
    <h3 class="head-text mb-3">必要かも？</h3>
    <div class="need__materials mb-4">
        @foreach( $menuMaterials as $mm)
        <div class="need__material justify-content-center
        {{ in_array($mm['material_id'], $includeMaterialsId) ? 'd-none' : 'd-flex' }}">
            <div class="need__material-name">
                {{ $mm['material'] }}
            </div>
            <form action="{{ route('add-wishlist') }}" method="POST">
                @csrf
                <input type="hidden" name="material_id" value="{{ $mm['material_id'] }}">
                <input type="hidden" name="selected_id" value="{{ $selectedMenu['id'] }}">
                <button type="submit" class="ml-3 mb-3 {{ in_array($mm['material_id'], $wishlistMaterialsId) ? 'smallBtn-disable' : 'smallBtn' }}" {{ in_array($mm['material_id'], $wishlistMaterialsId) ? 'disabled' : '' }}>
                    <i class="fas fa-shopping-cart mr-2"></i>追加{{ in_array($mm['material_id'], $wishlistMaterialsId) ? '済' : '' }}</button>
            </form>
        </div>
        @endforeach
    </div>
    
    <div class="rakuten-direct-link text-center my-4">
        <a href="https://recipe.rakuten.co.jp/search/{{ $selectedMenu['menu'] }}" target="_blank">
                <button class="primaryBtn"><i class="fas fa-external-link-alt mr-2"></i>{{ $selectedMenu['menu'] }}を楽天レシピで検索</button>
        </a>
    </div>

    <h2 class="mb-4 text-center">人気のレシピ</h1>
        
        <div class="recipe-container px-3">
            
        @foreach( $recipes as $r)
        <li class="recipes__recipe mb-3">
            <a href="https://recipe.rakuten.co.jp/recipe/{{ $recipes[$loop->index]['rid'] }}" target="_blank">
                <div class="d-flex">
                    <img loading="lazy" class="recipes__recipe-img" src="{{ $recipes[$loop->index]['img'] }}" alt="レシピ画像">
                    <div class="recipes__recipe-cover" style="background-image: linear-gradient(90deg, rgba(232,228,210, 1), 60%, rgba(232,232,218, 0.66)), url('{{ $recipes[$loop->index]['img'] }}')">
                        <div class="recipes__recipe-title mx-3 my-2">{{ $recipes[$loop->index]['title'] }}</div>
                        <div class="recipes__recipe-menu d-flex align-items-center">
                            
                            <div class="recipes__recipe-dislike-alert mr-2 {{ in_array( $recipes[$loop->index]['rid'], $dislikeMenuList) ? '' : 'd-none' }}">
                                <i class="fas fa-exclamation-circle mr-2"></i>嫌いな食材あり
                            </div>
                            
                            <form class="favRecipe" action="{{ route('favRecipe') }}" method="POST">
                                @csrf
                                <input type="hidden" name="rid" value="{{ $recipes[$loop->index]['rid'] }}">
                                <input type="hidden" name="title" value="{{ $recipes[$loop->index]['title'] }}">
                                <input type="hidden" name="img" value="{{ $recipes[$loop->index]['img'] }}">
                                <input type="hidden" name="selected_id" value="{{ $selectedMenu['id'] }}">
                                <button type="submit" class="{{ in_array( $recipes[$loop->index]['rid'], $favoritesId) ? 'recipes__fav-btn-active' : 'recipes__fav-btn' }} mr-2">
                                    <i class="{{ in_array( $recipes[$loop->index]['rid'], $favoritesId) ? 'fas' : 'far' }} fa-bookmark"></i>
                                </button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </a>
        </li>
        @endforeach
        
        
        
    </div>

        
</div>
<div class="button_bar d-flex justify-content-center">
    <a href="{{ route('suggest') }}">
        <button class="recipes__closeBtn"><i class="fas fa-times mr-2"></i>終了する</button>
    </a>
</div>
@endsection