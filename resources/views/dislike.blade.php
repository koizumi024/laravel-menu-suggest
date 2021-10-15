@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <h1 class="mb-4 text-center">非表示食材</h1>
    <h3 class="categories__head mb-4">カテゴリ選択</h3>
    <div class="categories">
        @foreach ($categories as $c)
        <input type="radio" class="d-none" id="tab{{ $c['id'] }}"
        name="tab" value="{{ $c['id'] }}" v-model="isActive">
        <label for="tab{{ $c['id'] }}">{{ $c['category'] }}</label>
        @endforeach 
    </div>

    <form class="materials" action="{{ route('store') }}" method="POST">
        @csrf
        @foreach ($categories as $c)
        <div class="materials__wrap" v-bind:class="isActive == {{ $c['id'] }} ? '' : 'd-none' ">
            @foreach ($materials as $m)
                @if($m['category_id'] == $c['id'])
                    <input type="checkbox" name="materials_id[]" class="d-none"
                    id="checkbox{{ $m['id'] }}" value={{ $m['id'] }} 
                    {{ in_array($m['id'], $include_materials) ? 'checked' : '' }}>
                    <div class="materials__content">
                        <div class="{{ in_array($m['id'], $include_materials) ? 'materials__border-active' : 'materials__border' }}"
                        id="{{ $m['id'] }}">
                            <label for="checkbox{{ $m['id'] }}" onClick="selectMaterial{{ $m['id'] }}()">
                                <img src="{{ asset('img/materials/tamanegi.png') }}" 
                                alt="" height=110 width=110>
                            </label>
                        </div>
                        <li class="material__name">{{ $m['material'] }}</li>
                    </div>
                @endif
            @endforeach
        </div> 
        @endforeach
        <button type="submit" class="btn btn-primary material__submitBtn">登録する</button>
    </form>
    
</div>
@endsection
