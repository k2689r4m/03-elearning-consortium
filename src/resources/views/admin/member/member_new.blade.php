@extends('admin.layouts.admin')

@section('script')
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
        const confirmModalDelete = (e) => {
            const deleteTarget = e.parentNode.parentNode;
            deleteTarget.parentNode.removeChild(deleteTarget);
        }
    </script>
@endsection

@section('content')
<div class="content-wrap bg-white">
{{--    <ul class="page-nav">--}}
{{--        --}}
{{--        <li class="page-nav__item"><a href="{{ route('admin.memberView') }}">home</a></li>--}}
{{--        <li class="page-nav__item"><a href="{{ route('admin.memberView') }}">회원관리</a></li>--}}
{{--        <li class="page-nav__item"><a href="{{ route('admin.memberNewView') }}">회원추가</a></li>--}}
{{--    </ul>--}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
{{--                <div class="card-header">{{ __('Register') }}</div>--}}

                <div class="card-body t-center admin-input">
                    <form method="POST" action="{{ route('admin.memberNew') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">아이디</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username">

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}
                            <label for="password" class="col-md-4 col-form-label text-md-right">비밀번호</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
{{--                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>--}}
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">비밀번호 확인</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="univName" class="col-md-4 col-form-label text-md-right">학교명</label>

                            <div class="col-md-6">
                                <input id="univName" type="text" class="form-control @error('univName') is-invalid @enderror" name="univName" value="{{ old('univName') }}" autocomplete="univName">

                                @error('univName')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="t-center p-t-30">
                            <button type="button" class="btn btn-gray btn-lg" onclick="location.href='{{ route('admin.memberView') }}'">
                                취소
                            </button>
                            <button type="button" class="btn btn-primary btn-lg" onclick="handleConfirmModal(true)">
                                추가
                            </button>
                        </div>

                        <div class="popup confirm" id="confirm-modal" style="display: none">
                            <div class="dim" onclick="handleConfirmModal(false)"></div>
                            <div class="confirm-txt">
                                저장하시겠습니까?
                            </div>
                            <div class="confirm-btn">
                                <button class="btn gray" type="button" onclick="handleConfirmModal(false)">취소</button>
                                <button class="btn">확인</button>
                            </div>
                        </div>
                        @error('required')
                        <div class="popup confirm" id="confirm-modal2">
                            <div class="dim" onclick="handleConfirmModal(false)"></div>
                            <div class="confirm-txt">
                                {{ $message }}
                            </div>
                            <div class="confirm-btn">
                                <button class="btn w-100" type="button" onclick="confirmModalDelete(this)">확인</button>
                            </div>
                        </div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
