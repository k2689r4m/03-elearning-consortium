@extends('admin.layouts.total')

@section('_script')
    <script>
        let deleteElement;
        let deleteUri;
        const handleDeleteContent = (e) => {
            const delNode = deleteElement.parentNode.parentNode;

            const formData = new FormData();

            const request = new XMLHttpRequest();

            request.open('get', deleteUri, true);

            request.onload = (e) => {
                if (request.status === 200) {
                    // console.log(request.response);
                    if (request.response) {
                        delNode.remove();
                    }
                }
                else {
                    console.log('fail');
                }
            }

            request.send(formData);
            handleConfirmModal('confirm-modal', false);
        }

        const handleConfirmModal = (id, status, e, _deleteUri) => {
            deleteElement = e;
            deleteUri = _deleteUri;
            const confirmModal = document.querySelector('#' + id)
            if (status) {
                confirmModal.style.display = 'block';
            }
            else {
                confirmModal.style.display = 'none';
            }
        }
        window.onload = () => {
{{--            @if($userId)--}}
{{--            const year = document.querySelector('#year');--}}
{{--            const _year = document.querySelector('#_year');--}}

{{--            _year.value = year.value;--}}
{{--            year.addEventListener('change', () => {--}}
{{--                _year.value = year.value;--}}
{{--            });--}}

{{--            const month = document.querySelector('#month');--}}
{{--            const _month = document.querySelector('#_month');--}}

{{--            _month.value = month.value;--}}
{{--            month.addEventListener('change', () => {--}}
{{--                _month.value = month.value;--}}
{{--            });--}}
{{--            @endif--}}
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
        <form class="w-100" method="GET" action="{{ route('admin.total5View') }}">
            <input type="hidden" name="userId" id="userId" @if($userId) value="{{ $userId }}" @endif />
            <div class="admin-con__top">
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
                    <button class="btn btn-primary btn-lg m-r-0 fr" onclick="location.href='{{ route('admin.total5WriteView', ['userId' => $userId, 'year' => $year, 'month' => $month]) }}'" type="button">추가</button>
                @endif
            </div>
        </form>
        @if ($userId)
        @if(gettype($shortAnswers) == 'object' && count($shortAnswers) > 0)
            <table class="admin-table">
                <colgroup>
                    <col width="10%" />
                    <col width="70%" />
                    <col width="20%" />
                </colgroup>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>과목명</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shortAnswers as $shortAnswer)
                        <tr>
                            <td>{{ $shortAnswer->id }}</td>
                            <td><a href='{{ route('admin.total5WriteView', ['userId' => $userId, 'year' => $year, 'month' => $month, 'shortAnswerId' => $shortAnswer->id]) }}'>{{ $shortAnswer->getLectureName()->lectureName }}</a></td>
                            <td>
                                <button class="btn btn-primary btn-line btn-sm" onclick="location.href='{{ route('admin.total5EditView', ['userId' => $userId, 'year' => $year, 'month' => $month, 'shortAnswerId' => $shortAnswer->id]) }}'">수정</button>
                                <button class="btn btn-red btn-sm m-l-4" onclick="handleConfirmModal('confirm-modal', true, this, '{{ route('admin.total5Delete', ['shortAnswerId' => $shortAnswer->id]) }}')">삭제</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif
        @endif
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

    @error('error')
    <div class="popup confirm">
        <div class="dim" onclick="this.parentNode.remove()"></div>
        <div class="confirm-txt">
            {{ $message }}
        </div>
        <div class="confirm-btn">
            <button class="btn" onclick="this.parentNode.parentNode.remove()">취소</button>
            <button class="btn" onclick="location.href='{{ route('admin.total3View', ['userId' => $userId, 'year' => $year, 'month' => $month]) }}'">확인</button>
        </div>
    </div>
    @enderror
@endsection
