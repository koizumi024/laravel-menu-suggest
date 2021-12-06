@extends('layouts.app')

@section('content')
<div id="app">
    <h1 class="mb-4 text-center">提案結果</h1>
    
    {{-- <div class="text-center mb-2">登録されている食材数: {{ $count }}</div> --}}
    <div class="text-center">
        <div class="result__top mb-4">
            <p>あなたにオススメのメニューは...</p>
            <p class="result__top-menu">{{ $first_key }}</p>
            <h3 class="head-text mb-0">マッチ度</h3>
            <div class="result__top-percent mb-4"><span>{{ $first_data }}</span>%</div>
            <a href="/menu/{{ $first_id }}" class="mb-3">
                <button class="primaryBtn"><i class="fas fa-search mr-2"></i>詳しく見る</button>
            </a>
        </div>

        <h3 class="head-text mb-4">詳細結果（上位10件）</h3>
        <div class="result__graph">
            @foreach($sliceResult as $key => $data)
            <li class="result__graph-column row">
                <div class="result__graph-menu col-4 p-0">
                    {{-- $loop->iterationはforeachのループした回数を取得するらしい（１から始まる） --}}
                    <a href="/menu/{{ $menu_idName[$loop->iteration-1] }}">
                        <i class="result__graph-icon fas fa-search mr-1"></i>{{ $key }}
                    </a>
                </div>
                <div class="col-8">
                    <div class="result__graph-percentBar" style="width:{{ $data }}%;">
                        <span class="result__graph-percent">{{ $data }}</span>%
                    </div>
                </div>
            </li>
            @endforeach
        </div>
    </div>
</div>
@endsection
