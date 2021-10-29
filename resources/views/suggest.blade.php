@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <h1 class="mb-2 text-center">レシピ提案</h1>
    
    <li class="head-text mb-4">（<span>5個以上</span>の食材を登録していない場合、利用できません）</li>
    <div class="text-center mb-2">現在登録されている食材数: <span>{{ $count }}</span> </div>
    <div class="text-center">
        {{-- 条件を満たしていたら、提案機能を使えるようにする --}}
        @if ( $count >= 5 )
            <form class="suggest__form" action="{{ route('menuSuggest') }}" method="POST">
                @csrf
                <button type="submit" class="suggest__btn">メニューを探す</button>
            </form>
        @endif
        
        <a href="{{ route('material') }}">
            <button class="suggest__btn-secondary">食材を追加する</button>
        </a>
    </div>
</div>
@endsection
