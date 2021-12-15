@extends('layouts.app')

@section('content')
<div id="app" class="container-md px-0">

    {{-- <h1 class="mb-4 text-center">提案結果</h1> --}}
    
    <div class="text-center">
        <div class="result__top mb-4"
        style="background-image: linear-gradient(180deg, rgba(232,221,164, 0.6), 66.6%, rgba(235,231,210, 1)), url('{{ $panelImage[0] }}')">
            <p>あなたにオススメのメニューは...</p>
            <p class="result__top-menu">{{ $first_key }}</p>
            
            <h3 class="head-text mb-0">マッチ度</h3>
            <div class="result__top-percent mb-4"><span class="mr-1">{{ $first_data }}</span>%</div>
        
            
            <a href="/menu/{{ $first_id }}" class="mb-3">
                <button class="primaryBtn"><i class="fas fa-search mr-2"></i>詳しく見る</button>
            </a>
        </div>
    </div>

    @if($count < 10)
    <div class="alert alert-danger m-4 d-flex align-items-center suggest-alert" role="alert">
        <i class="fas fa-exclamation-circle mr-3"></i>
        <div class="alert-danger-text">食材数が少ないため、正確な提案結果を取得できない可能性があります。</div>
    </div>
    @endif
    
    <h4 class="result__graph-head text-center mt-5 mb-4">詳細結果</h3>
    <div class="result__graph">
        @foreach($sliceResult as $key => $data)
        <li class="result__graph-column row">
            <div class="result__graph-menu col-5 col-md-4 px-0">
                <a href="/menu/{{ $menu_idName[$loop->index] }}">
                    <i class="result__graph-icon fas fa-search mx-1"></i><span class="result__graph-text mr-2">{{ $key }}</span>
                </a>
            </div>
            <div class="col-7 col-md-8 p-0 result__graph-percentBar-bg">
                <div class="result__graph-percentBar" style="width:{{ $data }}%;">
                    <span class="result__graph-percent {{ $data == 100 ? 'mr-3' : 'mr-1' }}">{{ $data }}%</span>
                </div>
            </div>
        </li>
        @endforeach
    </div>
</div>
@endsection
