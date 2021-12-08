@extends('layouts.app')

@section('javascript')
<script src="{{ asset('js/confirm.js') }}" defer></script>
@endsection

@section('content')
<div id="app">
    <div id="overlay" class="setting__modal-overlay" v-show="showFavorite" v-on:click="closeFavorite">
        <div id="content" class="setting__modal-content">
            <p>お気に入りレシピ</p>
            <p><button v-on:click="closeFavorite">閉じる</button></p>
        </div>
    </div>


    <div class="mx-3">
        <h1 class="mb-4 text-center">ユーザー設定</h1>
        
        <a href="{{ route('wishlist') }}">
            <div class="user__btn"><i class="fas fa-shopping-cart mr-2"></i>買い物リスト</div>
        </a>

        <div v-on:click="openFavorite" class="setting__btn"><i class="far fa-bookmark mr-2"></i>お気に入りレシピ</div>


        <a href="{{ route('dislike') }}">
            <div class="user__btn"><i class="far fa-eye-slash mr-2"></i>非表示食材の設定</div>
        </a>

        <form class="user__clear" action="{{ route('clear') }}" id="deleteForm" method="POST">
            @csrf
            <button class="setting__clearBtn" type="submit" onclick="deleteHandle(event);">
                <i class="fas fa-trash-alt mr-2"></i>全ての食材データを削除する
            </button>
        </form>
        
        
        <a href="{{ route('logout') }}"
        onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
        <div class="setting__btn-danger"><i class="fas fa-sign-out-alt mr-2"></i>ログアウト</div>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>

@endsection
