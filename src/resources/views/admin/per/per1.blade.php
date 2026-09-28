@extends('admin.layouts.per')

@section('_script')
    <script>
        let deleteElement;
        window.onload = () => {
            @if ($userId)
                const year = document.querySelector('#year');
                const _year = document.querySelector('#_year');

                _year.value = year.value;
                year.addEventListener('change', () => {
                    _year.value = year.value;
                });

                const month = document.querySelector('#month');
                const _month = document.querySelector('#_month');

                _month.value = month.value;
                month.addEventListener('change', () => {
                    _month.value = month.value;
                });
            @endif
        }

        const handleAddContent = () => {
            const container = document.querySelector('#content-container');
            const div = document.createElement('div');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'content[]';
            const button = document.createElement('button');
            button.type = 'button';
            button.innerText = '삭제';
            button.classList = 'btn btn-red m-l-4';
            button.addEventListener('click', () => {
                handleConfirmModal('confirm-modal', true, button);
            });

            div.appendChild(input);
            div.appendChild(button);
            container.appendChild(div);
        }

        const handleDeleteContent = (e) => {
            deleteElement ? deleteElement.parentNode.remove() : e.target.parentNode.remove();
            handleConfirmModal('confirm-modal', false);
        }

        const handleConfirmModal = (id, status, e) => {
            deleteElement = e;
            const confirmModal = document.querySelector('#' + id)
            if (status) {
                confirmModal.style.display = 'block';
            }
            else {
                confirmModal.style.display = 'none';
            }
        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap bg-white">
        <div class="btn-tab__wrap">
            <h3 class="admin-tit">대학교 선택</h3>
            @foreach($univ as $u)
                <button class="btn btn-tab" onclick="
                    document.querySelector('#userId').value = '{{ $u->id }}';
                    document.querySelector('#search').click();
                    "
                        @if($userId && $userId == $u->id)
                        disabled
                    @endif
                >{{ $u->univName }}</button>
            @endforeach
        </div>
        <hr>
        <div class="admin-con__top">
        <form class="w-100" method="GET" action="{{ route('admin.per1View') }}">
            <input type="hidden" name="userId" id="userId" @if($userId) value="{{ $userId }}" @endif />
            <div>
                학기정보
                <select name="year" id="year">
                    @foreach(range(2010, 2050) as $y)
                        <option value="{{ $y }}" @if ($y == $year) selected @endif>{{ $y }}</option>
                    @endforeach
                </select>
                학년도
                <select name="month" id="month">
                    @foreach([1,3,2,4] as $m)
                        <option value="{{ $m }}" @if ($m == $month) selected @endif>
                            @switch($m)
                                @case(1)
                                @case(2)
                                {{ $m }}
                                @break
                                @case(3)
                                여름계절
                                @break
                                @case(4)
                                겨울계절
                                @break
                            @endswitch
                        </option>
                    @endforeach
                </select>
                학기
                <button class="btn btn-primary" id="search">검색</button>
                @if ($userId)
                    <button class="btn btn-primary fr" type="button" onclick="document.getElementById('contentForm').submit()">저장</button>
                @endif
            </div>
        </form>
        </div>
        @if ($userId)
            <form method="POST" action="{{ route('admin.per1Write') }}" enctype="multipart/form-data" id="contentForm">
                @csrf
                <input type="hidden" name="year" id="_year" value="{{ $year }}" />
                <input type="hidden" name="month" id="_month" value="{{ $month }}" />
                <input type="hidden" name="userId" value="{{ $userId }}" />

                <div class="admin-input">
                    <h3 class="admin-tit t-left">
                        내용
                    </h3>
                    <div id="content-container">
                        @if($firstPer)
                            @foreach($firstPer->content as $content)
                                <div>
                                    <input type="text" name="content[]" value="{{ $content }}" />
                                    <button class="btn btn-red" type="button" onclick="handleConfirmModal('confirm-modal', true, this)">삭제</button>
                                </div>
                            @endforeach
                        @else
                            <div>
                                <input type="text" name="content[]" />
                                <button class="btn btn-red" type="button" onclick="handleConfirmModal('confirm-modal', true, this)">삭제</button>
                            </div>
                        @endif
                    </div>
                    <div class="m-t-10">
                        <button class="btn btn-sub" type="button" onclick="handleAddContent()">추가</button>
                    </div>
                </div>
            </form>
    {{--        <form method="POST" action="{{ route('admin.total3Excel') }}" enctype="multipart/form-data">--}}
    {{--            @csrf--}}
    {{--            <input type="hidden" name="year" id="_year" value="{{ $year }}" />--}}
    {{--            <input type="hidden" name="month" id="_month" value="{{ $month }}" />--}}
    {{--            <input type="hidden" name="userId" value="{{ $userId }}" />--}}

    {{--            <input type="file" name="excel" />--}}
    {{--            <button>저장</button>--}}
    {{--        </form>--}}
        @else
            <p>업로드 하기 위해 먼저 학교, 연도, 학기를 선택해 주세요.</p>
        @endif
        <div>
    {{--        @if($thirdTotal)--}}
    {{--            {{ $thirdTotal->id }}--}}
    {{--        @endif--}}
        </div>
    </section>

    <div class="popup confirm" id="confirm-modal" style="display:none">
        <div class="dim" onclick="handleConfirmModal('confirm-modal', false)"></div>
        <div class="confirm-txt">
            삭제하시겠습니까?
        </div>
        <div class="confirm-btn">
            <button class="btn gray" onclick="handleConfirmModal('confirm-modal', false)">취소</button>
            <button class="btn" onclick="handleDeleteContent()">확인</button>
        </div>
    </div>
@endsection
