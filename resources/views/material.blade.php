@extends('layouts.app')

@section('content')

<div id="app">
    <h1 class="mb-4 text-center">食材管理</h1>
    <h3 class="head-text mb-4">カテゴリ選択</h3>
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
                    <div class="materials__content {{ in_array($m['id'], $exclude_materials) ? 'd-none' : '' }}">
                        <div class="{{ in_array($m['id'], $include_materials) ? 'materials__border-active' : 'materials__border' }}"
                        id="{{ $m['id'] }}">
                            <label for="checkbox{{ $m['id'] }}" onClick="selectMaterial{{ $m['id'] }}()">
                                <img src="https://kohacu.com/wp-content/uploads/2019/05/kohacu.com_000737_20180304_v2.png" alt="" class="material__img">
                            </label>
                        </div>
                        <li class="material__name">{{ $m['material'] }}</li>
                    </div>
                @endif
            @endforeach
        </div> 
        @endforeach
        <button type="submit" class="material__submitBtn">追加する</button>
    </form>
</div>
@endsection
