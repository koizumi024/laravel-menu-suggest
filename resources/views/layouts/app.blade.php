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
    @yield('javascript')

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
        {{-- 浮かせて表示＋自動でフェードアウトするメッセージ --}}
        @if (Session::has('successMessage'))
            <div class="message__box">
                <i class="fas fa-check message__icon"></i>
                <div class="message__text">
                    {{ session('successMessage') }}
                </div>
            </div>
        @endif

        {{-- ルートによってビューを差し込む部分 --}}
        <main class="py-4">
            @yield('content')
        </main>
        
        {{-- ルートによってアクティブなタブの色を変えるフッター --}}
        <footer>
            @if (Request::routeIs('material'))
                <div class="footer__tab">
                    <i class="fas fa-list-alt footer__icon active"></i>
                    <div class="footer__text active">食材の管理</div>
                </div>
            @else
                <a href="{{ route('material') }}">
                    <div class="footer__tab">
                        <i class="far fa-list-alt footer__icon"></i>
                        <div class="footer__text">食材の管理</div>
                    </div>
                </a>
            @endif

            @if (Request::routeIs('suggest'))
                <div class="footer__tab">
                    <i class="fas fa-lightbulb footer__icon active"></i>
                    <div class="footer__text active">メニュー提案</div>
                </div>
            @else
                <a href="{{ route('suggest') }}">
                    <div class="footer__tab">
                        <i class="far fa-lightbulb footer__icon"></i>
                        <div class="footer__text">メニュー提案</div>
                    </div>
                </a>
            @endif

            @if (Request::routeIs('setting') || (Request::routeIs('dislike')))
                <div class="footer__tab">
                    <i class="fas fa-user footer__icon active"></i>
                    <div class="footer__text active">ユーザー設定</div>
                </div>
            @else
                <a href="{{ route('setting') }}">
                    <div class="footer__tab">
                        <i class="far fa-user footer__icon"></i>
                        <div class="footer__text">ユーザー設定</div>
                    </div>
                </a>
            @endif
        </footer>
        
    </div>
    {{--  Vue.jsを利用したタブメニュー （参考: https://qiita.com/mimoe/items/86d5312b3741320b717b) --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                isActive: '1',
                showFavorite: false,
                showUser: false,
            },
            methods:{
                openFavorite: function(){
                this.showFavorite = true
                },
                closeFavorite: function(){
                this.showFavorite = false
                },
                openUser: function(){
                this.showUser = true
                },
                closeUser: function(){
                this.showUser = false
                }
            }
        })
    </script>
</body>
</html>
