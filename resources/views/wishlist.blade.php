@extends('layouts.app')

@section('javascript')
<script src="{{ asset('js/confirm.js') }}" defer></script>
@endsection

@section('content')
<div id="app" class="container-md">
    <h1 class="py-4 text-center">買い物リスト</h1>

    @if($wishlistCount == 0)
    <div class="my-material-list__empty text-center mt-4">登録されている食材がありません</div>
    @endif

    <div class="my-material-list px-5">
        @foreach($wishlist as $w)
        <div class="my-material-list__box p-3 d-flex justify-content-between ">
            <div class="left d-flex align-items-center">
                <img loading="lazy" src="/img/materials/{{ $w['image'] }}" alt="食材画像" class="my-material-list__box-img">
                <div class="side ml-3">
                    <div class="my-material-list__box-title">{{ $w['material'] }}</div>
                    <div class="my-material-list__box-category mt-1">{{ $w['category'] }}</div>
                    
                </div>
            </div>
            <div class="right">
                <form class="user__clear" action="{{ route('delete-wishlist2') }}" method="POST">
                @csrf
                    <input type="hidden" name="material_id" value="{{ $w['material_id'] }}">
                    <button class="my-material-list__box-delete p-2" type="submit">
                        <i class="fas fa-trash-alt mr-2"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($wishlistCount > 0)
    <form class="user__clear" action="{{ route('clearWishlist') }}" id="deleteForm" method="POST">
        @csrf
        <div class="text-center mt-4">
            <button class="clearBtn text-center" type="submit" onclick="deleteHandle(event);">
                <i class="fas fa-trash-alt mr-2"></i>全ての食材を削除する
            </button>
        </div>
    </form>
    @endif
</div>
@endsection