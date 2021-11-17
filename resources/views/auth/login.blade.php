@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                メニュー提案アプリケーション
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Eメール</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-form-label text-md-right">パスワード</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">ログイン情報を記憶する</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="primaryBtn mb-3"><i class="fas fa-unlock mr-2"></i></i>ログイン</button>

                        <a class="secondaryBtn" href="{{ route('register') }}">
                            <i class="fas fa-sign-in-alt mr-2"></i>新規登録
                        </a>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">パスワードを忘れた場合</a>
                        @endif
                    </div>
                </form>
            
        </div>
    </div>
</div>
@endsection
