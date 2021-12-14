@extends('layouts.app')

@section('javascript')
<script src="{{ asset('js/confirm.js') }}" defer></script>
@endsection

@section('content')
<div id="app">
    <h1 class="pt-4 text-center">マイ食材</h1>

    <div class="my-material__box-container px-5 py-4">
        <a href="{{ route('update-materials') }}">
            <div class="my-material__box p-4">
                <div class="my-material__box-top d-flex justify-content-between">
                    <div class="my-material__box-title">マイ食材の更新</div>
                    <span class="arrow sample5-3 mr-3"></span>
                </div>
                <div class="my-material__box-description mt-3">持っている食材の追加、削除ができます。</div>
            </div>
        </a>
        
        <a href="{{ route('dislike-materials') }}">
            <div class="my-material__box p-4 mt-4">
                <div class="my-material__box-top d-flex justify-content-between">
                    <div class="my-material__box-title">嫌いな食材の設定</div>
                    <span class="arrow sample5-3 mr-3"></span>
                </div>
                
                <div class="my-material__box-description mt-3">アレルギーや苦手等の理由で、提案する際に非表示にしたい食材を設定できます。</div>
            </div>
        </a>
    </div>
    
    <div class="my-material-list px-5">
        <h4 class="text-center mt-5">マイ食材一覧（合計: {{ $count }}）</h3>

        @if($count == 0)
        <div class="my-material-list__empty text-center mt-4">登録されている食材がありません</div>
        @endif

        @foreach($user_materials as $u)
        <div class="my-material-list__box p-3 mx-4 my-3 d-flex justify-content-between">
            <div class="left d-flex align-items-center">
                <img loading="lazy" src="/img/materials/{{ $u['image'] }}" alt="食材画像" class="my-material-list__box-img">
                <div class="side ml-3">
                    <div class="my-material-list__box-title">{{ $u['material'] }}</div>
                    <div class="my-material-list__box-category mt-1">{{ $u['category'] }}</div>
                    
                </div>
            </div>
            <div class="right">
                <form class="user__clear" action="{{ route('delete-material2') }}" method="POST">
                @csrf
                    <input type="hidden" name="material_id" value="{{ $u['material_id'] }}">
                    <button class="my-material-list__box-delete p-2" type="submit">
                        <i class="fas fa-trash-alt mr-2"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach

        @if($count > 0)
        <form class="user__clear" action="{{ route('clear') }}" id="deleteForm" method="POST">
            @csrf
            <div class="text-center mt-4">
                <button class="clearBtn text-center" type="submit" onclick="deleteHandle(event);">
                    <i class="fas fa-trash-alt mr-2"></i>全ての食材を削除する
                </button>
            </div>
        </form>
        @endif
    </div>
    

</div>
@endsection