@extends('layouts.app')

@section('content')
<div id="app">
    <a href="{{ route('setting') }}">
        <div>戻る</div>
    </a>

    <h1 class="mb-4 text-center">非表示食材の管理</h1>
    <h3 class="head-text mb-4">カテゴリ選択</h3>
    <div class="categories">
        @foreach ($categories as $c)
        <input type="radio" class="d-none" id="tab{{ $c['id'] }}"
        name="tab" value="{{ $c['id'] }}" v-model="isActive">
        <label for="tab{{ $c['id'] }}">{{ $c['category'] }}</label>
        @endforeach 
    </div>

    <form class="materials" action="{{ route('dstore') }}" method="POST">
        @csrf
        @foreach ($categories as $c)
        <div class="materials__wrap" v-bind:class="isActive == {{ $c['id'] }} ? '' : 'd-none' ">
            @foreach ($materials_all as $m)
                @if($m['category_id'] == $c['id'])
                    <input type="checkbox" name="materials_id[]" class="d-none" id="checkbox{{ $m['id'] }}"
                    value="{{ $m['id'] }}" {{ in_array($m['id'], $exclude_materials) ? 'checked' : '' }}>
                    <div class="materials__content">
                        <div class="{{ in_array($m['id'], $exclude_materials) ? 'materials__border-active' : 'materials__border' }}"
                        id="{{ $m['id'] }}">
                            <label for="checkbox{{ $m['id'] }}" onClick="selectMaterial{{ $m['id'] }}()">
                                <img src="/img/materials/{{ $m['image'] }}" alt="食材の画像" class="material__img">
                            </label>
                        </div>
                        <li class="material__name">{{ $m['material'] }}</li>
                    </div>
                @endif
            @endforeach
            <button type="submit" class="material__submitBtn-hidden"><i class="far fa-eye-slash mr-2"></i>非表示にする</button>
        </div> 
        @endforeach
    </form>
    
</div>
@endsection
