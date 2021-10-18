<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/border.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/original.css') }}" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
</head>
<body>
    <div id="app" class="bg-app">

        <main class="py-4">
            @yield('content')
        </main>
        
        <footer>
            <a href="{{ route('material') }}">
                <div class="footer__tab">
                    @if (Request::routeIs('material'))
                        <i class="fas fa-list footer__icon active"></i>
                        <div class="footer__text active">食材管理</div>
                    @else
                        <i class="fas fa-list footer__icon"></i>
                        <div class="footer__text">食材管理</div>
                    @endif
                </div>
            </a>
            <a href="{{ route('suggest') }}">
                <div class="footer__tab">
                    @if (Request::routeIs('suggest'))
                        <i class="fas fa-utensils footer__icon active"></i>
                        <div class="footer__text active">メニュー提案</div>
                    @else
                    <i class="fas fa-utensils footer__icon"></i>
                    <div class="footer__text">メニュー提案</div>
                    @endif
                </div>
            </a>
            <a href="{{ route('user') }}">
                <div class="footer__tab">
                    @if (Request::routeIs('user') || Request::routeIs('dislike'))
                        <i class="fas fa-user footer__icon active"></i>
                        <div class="footer__text active">ユーザー設定</div>
                    @else
                        <i class="fas fa-user footer__icon"></i>
                        <div class="footer__text">ユーザー設定</div>
                    @endif
                </div>
            </a>
        </footer>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                isActive: '1',
        }
    })
    </script>
</body>
</html>
