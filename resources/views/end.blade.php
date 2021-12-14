@extends('layouts.app')

@section('content')
<div id="app">
    <h1 class="mb-4 text-center">お疲れ様でした</h1>
    <h3 class="head-text mb-3"><i class="fas fa-hand-pointer mr-2"></i>使い終わった食材があれば選択してください</h3>

    <form action="{{ route('delete-material') }}" method="POST">
    @csrf
        @foreach ($user_materials as $u)
            <li>
                <input class="" type="checkbox" name="material_id[]" value="{{ $u['material_id'] }}" id="checkbox{{ $u['material_id'] }}">
                <label for="checkbox{{ $u['material_id'] }}">{{ $u['material'] }}</label>
            </li>
        @endforeach

        <button type="submit">終了する</button>
    </form>
    
    

</div>
@endsection