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
        <!-- フラッシュメッセージ -->
        @if (Session::has('successMessage'))
        <div class="message__box">
            <i class="fas fa-check message__icon"></i>
            <div class="message__text">
                {{ session('successMessage') }}
            </div>
        </div>
        @endif

        <div class="logout"><a href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"></a></div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>


        {{-- ルートによってビューを差し込む部分 --}}
        <main class="pb-4">
            @yield('content')
        </main>
        
        {{-- ルートによってアクティブなタブの色を変えるフッター --}}
        <footer>
            <a href="{{ route('my-materials') }}">
                <div class="footer__tab">
                @if (Request::routeIs('update-materials') || Request::routeIs('dislike-materials')|| Request::routeIs('my-materials'))
                    <i class="fas fa-apple-alt footer__icon active"></i>
                    <div class="footer__text active">マイ食材</div>
                @else
                    <i class="fas fa-apple-alt footer__icon"></i>
                    <div class="footer__text">マイ食材</div>
                @endif
                </div>
            </a>

            <a href="{{ route('suggest') }}">    
                <div class="footer__tab">
                @if (Request::routeIs('suggest') || (Request::routeIs('menu.index')) || (Request::routeIs('end')))
                    <i class="fas fa-lightbulb footer__icon active"></i>
                    <div class="footer__text active">提案</div>
                @else
                    <i class="fas fa-lightbulb footer__icon"></i>
                    <div class="footer__text">提案</div>
                @endif
                </div>
            </a>

            <a href="{{ route('wishlist') }}">
                <div class="footer__tab">
                @if (Request::routeIs('wishlist'))
                    <i class="fas fa-shopping-cart footer__icon active"></i>
                    <div class="footer__text active">買い物リスト</div>
                @else
                    <i class="fas fa-shopping-cart footer__icon"></i>
                    <div class="footer__text">買い物リスト</div>
                @endif
                </div>
            </a>
            
            <a href="{{ route('favorite') }}">
                <div class="footer__tab">
                @if (Request::routeIs('favorite'))
                    <i class="fas fa-bookmark footer__icon active"></i>
                    <div class="footer__text active">お気に入り</div>
                @else
                    <i class="fas fa-bookmark footer__icon"></i>
                    <div class="footer__text">お気に入り</div>
                @endif
                </div>
            </a>
           
        </footer>
        
    </div>
    {{--  タブメニュー （参考: https://qiita.com/mimoe/items/86d5312b3741320b717b) --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                isActive: '1',
            },
        })
    </script>
</body>
</html>
