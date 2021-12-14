@extends('layouts.app')

@section('content')
<div id="app">
    <h1 class="mb-4 text-center pt-4">お気に入りレシピ</h1>
    <div class="recipe-container px-5 mt-4">

        @if($favoriteCount == 0)
        <div class="my-meterial-list__empty text-center mt-4">登録されているレシピがありません</div>
        @endif

       @foreach($user_favorite as $uf)
        <li class="recipes__recipe mb-3">
            <a href="https://recipe.rakuten.co.jp/recipe/{{ $user_favorite[$loop->index]['recipe_id'] }}" target="_blank">
                <div class="d-flex">
                    <img loading="lazy" class="recipes__recipe-img" src="{{ $user_favorite[$loop->index]['recipe_image'] }}" alt="レシピ画像">
                    <div class="recipes__recipe-cover" style="background-image: linear-gradient(90deg, rgba(232,228,210, 1), 60%, rgba(232,232,218, 0.66)), url('{{ $user_favorite[$loop->index]['recipe_image'] }}')">
                        <div class="recipes__recipe-title mx-3 my-2">{{ $user_favorite[$loop->index]['recipe_title'] }}</div>
                        <div class="recipes__recipe-menu d-flex">
                            <form class="favRecipe" action="{{ route('favRecipe2') }}" method="POST">
                                @csrf
                                <input type="hidden" name="rid" value="{{ $user_favorite[$loop->index]['recipe_id'] }}">
                                <input type="hidden" name="title" value="{{ $user_favorite[$loop->index]['recipe_title'] }}">
                                <input type="hidden" name="img" value="{{ $user_favorite[$loop->index]['recipe_image'] }}">
                                <button type="submit" class="recipes__fav-btn-active mr-2">
                                    <i class="fas fa-bookmark"></i>
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
@endsection