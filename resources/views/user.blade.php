@extends('layouts.app')

@section('content')
<div id="app">

    <h1 class="mb-4 text-center">ユーザ設定</h1>
    
    <div v-on:click="openFavorite" class="user__btn"><i class="far fa-heart"></i>お気に入りレシピ</div>

    <div id="overlay" class="user__modal-overlay" v-show="showFavorite" v-on:click="closeFavorite">
      <div id="content" class="user__modal-content">
          <p>お気に入りレシピ</p>
          <p><button v-on:click="closeFavorite">閉じる</button></p>
        </div>
    </div>

    <div v-on:click="openUser" class="user__btn"><i class="far fa-heart"></i>ユーザー情報の変更</div>

    <div id="overlay" class="user__modal-overlay" v-show="showUser" v-on:click="closeUser">
      <div id="content" class="user__modal-content">
          <p>ユーザー情報の変更</p>
          <p><button v-on:click="closeUser">閉じる</button></p>
        </div>
    </div>
    
    <a href="{{ route('dislike') }}">
        <div class="user__btn"><i class="far fa-eye-slash"></i>非表示食材の設定</div>
    </a>

    <form class="user__clear" action="{{ route('clear') }}" method="POST">
        @csrf
        <button type="submit">全ての食材データを削除する</button>
    </form>
    
    
    <a href="{{ route('logout') }}"
    onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
    <div class="user__btn"><i class="fas fa-sign-out-alt"></i>ログアウト</div>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
@endsection
