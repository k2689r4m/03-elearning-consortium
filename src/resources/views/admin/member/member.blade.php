@extends('admin.layouts.admin')

@section('script')
    <script>
        const handleConfirmModal = (status, id) => {
            const confirmModal = document.querySelector('#confirm-modal');
            if (status) {
                confirmModal.style.display = 'block';
                document.getElementById('deleteId').value = id;
            }
            else {
                confirmModal.style.display = 'none';
            }
        }
    </script>
@endsection

@section('content')
    <section class="content-wrap bg-white">
        @if (session('status'))
{{--            <div class="alert alert-success">--}}
{{--                {{ session('status') }}--}}
{{--            </div>--}}
        @endif
{{--        <ul class="page-nav">--}}
{{--            <li class="page-nav__item"><a href="{{ route('admin.memberView') }}">home</a></li>--}}
{{--            <li class="page-nav__item"><a href="{{ route('admin.memberView') }}">회원관리</a></li>--}}
{{--        </ul>--}}
        <div class="admin-con__top">
            <form method="GET" action="{{ route('admin.memberView') }}">
                <select name="type">
                    <option value="univName">학교명</option>
                    <option value="username">아이디</option>
                </select>
                <input class="text" type="text" placeholder="검색어를 두자 이상 입력하세요." name="content" />
                <button class="btn btn-primary">검색</button>
            </form>
            <button class="btn btn-primary btn-lg fr" onclick="location.href='{{ route('admin.memberNewView') }}'">회원추가</button>
        </div>
        @error('search')
        {{ $message }}
        @enderror
        <div>
            <table class="admin-table">
                <colgroup>
                    <col width="5%" />
                    <col width="40%" />
                    <col width="15%" />
                    <col width="20%" />
                    <col width="20%" />
                </colgroup>
                <thead>
                    <tr>
                        <th>
                            번호
                        </th>
                        <th>
                            학교명
                        </th>
                        <th>
                            아이디
                        </th>
                        <th>
                            가입일
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        @if($user->admin != 1)
                        <tr>
                            <td>
{{--                                {{ $user->id }}--}}
                                {{ $users->firstItem() + $key - 1 }}
                            </td>
                            <td>
                                {{ $user->univName }}
                            </td>
                            <td>
                                {{ $user->username }}
                            </td>
                            <td>
                                {{ $user->created_at }}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-line" onclick="location.href='{{ route('admin.memberEditView', ['userId' => $user->id]) }}'">수정</button>
                                <button class="btn btn-red btn-line m-l-4" onclick="handleConfirmModal(true, {{$user->id}})">삭제</button>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            {{ $users->withQueryString('')->links('vendor.pagination.tailwind') }}
        </div>

        <div class="popup confirm" id="confirm-modal" style="display: none">
            <div class="dim" onclick="handleConfirmModal(false)"></div>
            <form method="GET" action="{{ route('admin.memberDelete') }}">
            <div class="confirm-txt">
                <input type="hidden" name="deleteId" id="deleteId" />
                삭제하시겠습니까?
            </div>
            <div class="confirm-btn">
                <button class="btn gray" type="button" onclick="handleConfirmModal(false)">취소</button>
                <button class="btn">확인</button>
            </div>
            </form>
        </div>
    </section>
@endsection
