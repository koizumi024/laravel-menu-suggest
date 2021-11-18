@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        メニュー提案アプリケーション
        <form method="POST" action="{{ route('register') }}" class="d-block">
            @csrf
            <div class="form-group">
                <label for="name" class="col-form-label">ユーザー名</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="col-form-label">Eメール</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="col-form-label">パスワード</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm" class="col-form-label">確認用パスワード</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            
            <button type="submit" class="primaryBtn mb-3"><i class="fas fa-sign-in-alt mr-2"></i>新規登録</button>

            <a class="secondaryBtn" href="{{ route('login') }}">
                <i class="fas fa-unlock mr-2"></i>ログイン
            </a>
            
        </form>
    </div>
</div>
@endsection
