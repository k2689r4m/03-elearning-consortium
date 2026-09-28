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


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    {{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <script>
        const handleConfirmModal = (status) => {
            const confirmModal = document.querySelector('#confirm-modal')
            if (status) {
                confirmModal.style.display = 'block';
            }
            else {
                confirmModal.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<div id="app">
    <main class="py-4">
        @error('permission')
        {{--        <div>--}}
        {{--            {{ $message }}--}}
        {{--        </div>--}}
        <div class="popup confirm" id="confirm-modal">
            <div class="dim" onclick="handleConfirmModal(false)"></div>
            <div class="confirm-txt">
                {{ $message }}
            </div>
            <div class="confirm-btn">
                <button class="btn w-100" onclick="handleConfirmModal(false)">확인</button>
            </div>
        </div>
        @enderror

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
             <div class="login-wrap">
                 <div class="login-content-wrap">
                     <div class="login-txt admin">
                         대학 e-러닝 기반<br />
                         학점인정컨소시엄<br />
                         <br />
                         교육성과관리시스템
                     </div>
                    <div class="login">
                        <h2 class="logo">logo</h2>
                        <h3 class="sub-tit">학점인정 컨소시엄 관리자 페이지</h3>
                        <div class="login-input">
                            <label class="login-input__label">아이디</label>
                            <input id="username" name="username" type="text" placeholder="ID" class="login-input__id" value="{{ old('username') }}" autocomplete="off" autofocus>
                        </div>
                        <div class="login-input">
                            <label class="login-input__label">비밀번호</label>
                            <input id="password" name="password" type="password" placeholder="비밀번호를 입력해주세요."  class="login-input__pwd" autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong class="login-input__guide">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button class="btn btn-primary btn-full" type="submit" >로그인</button>
                    </div>
                </div>
             </div>
        </form>
    </main>
</div>
</body>
</html>
