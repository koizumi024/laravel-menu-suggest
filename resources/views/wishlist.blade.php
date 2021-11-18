@extends('layouts.app')

@section('content')
<div id="app">
    <h1 class="mb-4 text-center">買い物リスト</h1>
    <form action="">
    @foreach($wishlist as $w)
    <li>
        <input class="wishlist__checkbox" type="checkbox" name="material_id" value="{{ $w['material_id'] }}" id="checkbox{{ $w['material_id'] }}">
        <label for="checkbox{{ $w['material_id'] }}">{{ $w['material'] }}</label>
    </li>
    @endforeach
    
    <button type="submit" class="dangerBtn"><i class="fas fa-trash-alt mr-2"></i>削除する</button>
    </form>
</div>
@endsection