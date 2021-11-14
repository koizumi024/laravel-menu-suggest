@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <h1 class="mb-2 text-center">メニュー提案結果</h1>
    
    <div class="text-center mb-2">現在登録されている食材数: <span>{{ $count }}</span> </div>
    <div class="text-center">

        <p>あなたに１番おすすめのメニューは</p>
        <p>{{ $first_key }}</p>
        <p>マッチ度</p>
        <p>{{ $first_data }}%</p>

        <h3 class="head-text mb-4">詳細結果</h3>
        <div class="result__graph container">
            @foreach($matchResult as $key => $data)
            <li class="result__graph-column row">
                <div class="result__graph-menu col-4">{{ $key }}</div>
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
