@extends('layouts.app')

@section('script')
    <script>
        var confirmClose = () => {
            document.getElementById("dim").remove();
            document.getElementById("popup").remove();
        }
    </script>
@endsection

@section('content')
    <div class="login-wrap">
        <div class="login-content-wrap">
            <div class="login-txt">
                대학 e-러닝 기반<br />
                학점인정컨소시엄<br />
                <br />
                교육성과관리시스템
            </div>
            <div class="login">
                <h2 class="logo">logo</h2>
                <form method="POST" action="{{ route('guest.login') }}">
                    @csrf
    {{--            <div class="login-input">--}}
    {{--                <label class="login-input__label">아이디</label>--}}
    {{--                <input id="email" name="email" type="text" placeholder="ID@email.com" class="login-input__id" value="{{ old('email') }}" autocomplete="off" autofocus>--}}

    {{--                @error('email')--}}
    {{--                <span class="invalid-feedback" role="alert">--}}

    {{--                    @if ($message == '아이디를 입력하세요' || $message == '아이디는 이메일 형식이어야 합니다')--}}
    {{--                        <strong class="login-input__guide">{{ $message }}</strong>--}}
    {{--                    @else--}}
    {{--                        <div class="dim" id="dim" onclick="confirmClose()"></div>--}}
    {{--                        <div class="popup confirm" id="popup">--}}
    {{--                            <div class="confirm-txt">--}}
    {{--                                아이디 또는 비밀번호가 일치하지 않습니다.<br>--}}
    {{--                                다시 확인해주세요.--}}
    {{--                            </div>--}}
    {{--                            <div class="confirm-btn">--}}
    {{--                                <button class="btn w-100" onclick="confirmClose()">확인</button>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    @endif--}}
    {{--                </span>--}}
    {{--                @enderror--}}

    {{--            </div>--}}
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
                </form>
                <input style="display:none">
                <input type="password" style="display:none">
    {{--            <div class="login-find">--}}
    {{--                <a class="left">아이디 찾기</a>--}}
    {{--                <a class="right">비밀번호 찾기</a>--}}
    {{--            </div>--}}
            </div>
        </div>
    </div>
{{--<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>--}}
@endsection
