@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <h1 class="mb-4 text-center">ユーザ設定</h1>
    
    {{ Auth::user()->name }}さん
    <button class="btn btn-primary"><i class="far fa-heart"></i></i>お気に入りレシピ</button>
    <a href="{{ route('dislike') }}">
        <button class="btn btn-primary"><i class="far fa-eye-slash"></i>非表示食材の設定</button>
    </a>
    
    
    <a href="{{ route('logout') }}"
    onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
    <button class="btn btn-primary"><i class="fas fa-sign-out-alt"></i>ログアウト</button>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    <form action="">
        @csrf
        <input type="text" class="form-control" name="userName" id="userName" value="{{ Auth::user()->name }}">
        <button type="submit" class="btn btn-primary">変更する</button>
    </form>
</div>
@endsection
