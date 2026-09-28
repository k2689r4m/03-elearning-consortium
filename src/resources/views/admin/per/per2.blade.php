@extends('admin.layouts.per')

@section('_script')
    <script>
        let deleteElement;
        let deleteUri;

        window.onload = () => {
{{--            @if ($userId)--}}
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

        const send = () => {
            const year = document.querySelector('#year');
            const month = document.querySelector('#month');

            const formData = new FormData();

            formData.append('year', year.value);
            formData.append('month', month.value);
{{--            @if($userId)--}}
{{--                formData.append('userId', '{{ $userId }}');--}}
{{--            @endif--}}

            const trs = document.getElementsByName('tr');
            trs.forEach((tr) => {
                const inputs = tr.querySelectorAll('input');

                const _data = {};
                inputs.forEach((input) => {
                    if (input.name != 'id' && input.name != 'lectureName') {
                        _data[input.name] = input.checked;
                        // Object.defineProperty(_data, input.name, input.checked);
                        // _data.setProperty(input.name, input.checked);
                    }
                    else {
                        _data[input.name] = input.value;
                        // _data.setProperty(input.name, input.checked);
                        // Object.defineProperty(_data, input.name, input.checked);
                    }
                });

                formData.append('data[]', JSON.stringify(_data));
            });


            const request = new XMLHttpRequest();

            request.open('post', '{{ route('admin.per2Write') }}', true);
            request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            request.onload = (e) => {
                if (request.status === 200) {
                    const response = JSON.parse(request.response);
                    if (response.fail) {
                        console.log()
                        document.getElementById('confirmModal2Txt').innerText = response.data.lectureName;
                        handleConfirmModal('confirm-modal2', true)
                    }
                    else {
                        location.href = response.data;
                    }
                }
                else {
                    console.log('fail');
                }
            }

            request.send(formData);
        }

        const handleAddContent = () => {
            const container = document.querySelector('#container');
            const tr = document.createElement('tr');
            tr.setAttribute('name', 'tr');
            tr.innerHTML = `
                <input type="hidden" name="id" value="0" />
                <td><input type="text" name="lectureName" /></td>
                <td><input type="checkbox" name="fusion" /></td>
                <td><input type="checkbox" name="creative" /></td>
                <td><input type="checkbox" name="professionalism" /></td>
                <td><input type="checkbox" name="informationCommunication" /></td>
                <td><input type="checkbox" name="problemPrediction" /></td>
                <td><input type="checkbox" name="utilizationNewTechnology" /></td>
                <td><input type="checkbox" name="expression" /></td>
                <td><input type="checkbox" name="collaboration" /></td>
                <td><input type="checkbox" name="discrimination" /></td>
                <td><input type="checkbox" name="adaptation" /></td>
                <td><input type="checkbox" name="solution" /></td>
                <td><button class="btn btn-red btn-sm" type="button" onclick="handleConfirmModal('confirm-modal', true, this, 0)">삭제</button></td>
            `;
            container.appendChild(tr);
        }

        const handleDeleteContent = (e) => {
            let emptyDeleteTarget = e;
            if (deleteUri === 0) {
                deleteElement.parentNode.parentNode.remove();
                handleConfirmModal('confirm-modal', false);
            }
            else {
                const year = document.querySelector('#year');
                const month = document.querySelector('#month');

                const formData = new FormData();

                formData.append('year', year.value);
                formData.append('month', month.value);
{{--                @if($userId)--}}
{{--                formData.append('userId', '{{ $userId }}');--}}
{{--                @endif--}}
                formData.append('id', deleteUri);

                const request = new XMLHttpRequest();

                request.open('post', '{{ route('admin.per2Delete') }}', true);
                request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                request.onload = (e) => {
                    if (request.status === 200) {
                        if (request.response) {
                            if (request.response) {
                                document.getElementById(request.response).remove();
                                handleConfirmModal('confirm-modal', false);
                            }
                        }
                        else {
                            emptyDeleteTarget.parentNode.parentNode.remove();
                            handleConfirmModal('confirm-modal', false);
                        }
                    }
                    else {
                        console.log('fail');
                    }
                }

                request.send(formData);
            }
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

        const getData = () => {
            let request = new XMLHttpRequest();

            request.open('GET', `{{ route('admin.per2Fetch') }}?year={{ $year }}&month={{ $month }}`, true);
            request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            request.onload = (e) => {
                if (request.status === 200) {
                    if (request.response) {
                        const response = JSON.parse(request.response);
                        if (response.fail) {
                            console.log(response.data);
                        }
                        else {
                            const container = document.querySelector('#container');

                            response.data.forEach((data) => {
                                const tr = document.createElement('tr');
                                tr.setAttribute('name', 'tr');
                                tr.innerHTML = `
                                <input type="hidden" name="id" value="0" />
                                <td><input type="text" name="lectureName" value="${data.lectureName}" /></td>
                                <td><input type="checkbox" name="fusion" ${data.fusion ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="creative" ${data.creative ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="professionalism" ${data.professionalism ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="informationCommunication" ${data.informationCommunication ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="problemPrediction" ${data.problemPrediction ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="utilizationNewTechnology" ${data.utilizationNewTechnology ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="expression" ${data.expression ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="collaboration" ${data.collaboration ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="discrimination" ${data.discrimination ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="adaptation" ${data.adaptation ? 'checked' : ''}/></td>
                                <td><input type="checkbox" name="solution" ${data.solution ? 'checked' : ''}/></td>
                                <td><button class="btn btn-red btn-sm" type="button" onclick="handleConfirmModal('confirm-modal', true, this, 0)">삭제</button></td>
                            `;
                                container.appendChild(tr);
                            })
                        }
                    }
                    else {
                        console.log('test');
                    }
                }
                else {
                    console.log('fail');
                }
            }

            request.send();
        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap bg-white">
{{--        <div class="btn-tab__wrap">--}}
{{--            <h3 class="admin-tit">대학교 선택</h3>--}}
{{--            @foreach($univ as $u)--}}
{{--                <button class="btn btn-tab" onclick="--}}
{{--                    document.querySelector('#userId').value = '{{ $u->id }}';--}}
{{--                    document.querySelector('#search').click();--}}
{{--                    "--}}
{{--                        @if($userId && $userId == $u->id)--}}
{{--                        disabled--}}
{{--                    @endif--}}
{{--                >{{ $u->univName }}</button>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--        <hr>--}}
        <div class="admin-con__top">
        <form class="w-100" method="GET" action="{{ route('admin.per2View') }}">
{{--            <input type="hidden" name="userId" id="userId" @if($userId) value="{{ $userId }}" @endif />--}}
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

{{--                @if ($userId)--}}
                    <button class="btn btn-primary fr" type="button" onclick="send()">저장</button>
                    <button class="btn btn-primary fr" type="button" onclick="getData()">직전 학기 데이터 불러오기</button>
{{--                @endif--}}
            </div>
        </form>
        </div>
{{--        @if ($userId)--}}
            <form method="POST" action="{{ route('admin.per2Write') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="year" id="_year" value="{{ $year }}" />
                <input type="hidden" name="month" id="_month" value="{{ $month }}" />
{{--                <input type="hidden" name="userId" value="{{ $userId }}" />--}}

                <div>
                    <table class="admin-table">
                        <thead>
                            <th>과정명</th>
                            <th>융합</th>
                            <th>창의</th>
                            <th>전문성</th>
                            <th>정보통신</th>
                            <th>문제예측</th>
                            <th>신기술활용</th>
                            <th>표현(구현)</th>
                            <th>협동적수행</th>
                            <th>정보판별력</th>
                            <th>직업적응</th>
                            <th>문제해결</th>
                            <th>관리</th>
                        </thead>
                        <tbody id="container">
                        @if(gettype($secondPers) && count($secondPers) > 0)
                            @foreach($secondPers as $secondPer)
                                <tr name="tr" id="{{ $secondPer->id }}">
                                    <input type="hidden" name="id" value="{{ $secondPer->id }}" />
                                    <td><input type="text" name="lectureName" value="{{ $secondPer->lectureName }}" /></td>
                                    <td><input type="checkbox" name="fusion" @if($secondPer->fusion) checked @endif /></td>
                                    <td><input type="checkbox" name="creative" @if($secondPer->creative) checked @endif /></td>
                                    <td><input type="checkbox" name="professionalism" @if($secondPer->professionalism) checked @endif /></td>
                                    <td><input type="checkbox" name="informationCommunication" @if($secondPer->informationCommunication) checked @endif /></td>
                                    <td><input type="checkbox" name="problemPrediction" @if($secondPer->problemPrediction) checked @endif /></td>
                                    <td><input type="checkbox" name="utilizationNewTechnology" @if($secondPer->utilizationNewTechnology) checked @endif /></td>
                                    <td><input type="checkbox" name="expression" @if($secondPer->expression) checked @endif /></td>
                                    <td><input type="checkbox" name="collaboration" @if($secondPer->collaboration) checked @endif /></td>
                                    <td><input type="checkbox" name="discrimination" @if($secondPer->discrimination) checked @endif /></td>
                                    <td><input type="checkbox" name="adaptation" @if($secondPer->adaptation) checked @endif /></td>
                                    <td><input type="checkbox" name="solution" @if($secondPer->solution) checked @endif /></td>
                                    <td><button class="btn btn-red btn-sm" type="button" onclick="handleConfirmModal('confirm-modal', true, this, {{ $secondPer->id }})">삭제</button></td>
                                </tr>
                            @endforeach
                        @else
                            <tr name="tr">
                                <input type="hidden" name="id" value="0" />
                                <td><input type="text" name="lectureName" /></td>
                                <td><input type="checkbox" name="fusion" /></td>
                                <td><input type="checkbox" name="creative" /></td>
                                <td><input type="checkbox" name="professionalism" /></td>
                                <td><input type="checkbox" name="informationCommunication" /></td>
                                <td><input type="checkbox" name="problemPrediction" /></td>
                                <td><input type="checkbox" name="utilizationNewTechnology" /></td>
                                <td><input type="checkbox" name="expression" /></td>
                                <td><input type="checkbox" name="collaboration" /></td>
                                <td><input type="checkbox" name="discrimination" /></td>
                                <td><input type="checkbox" name="adaptation" /></td>
                                <td><input type="checkbox" name="solution" /></td>
                                <td><button class="btn btn-red btn-sm" type="button" onclick="handleConfirmModal('confirm-modal', true, this, 0)">삭제</button></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <div class="m-t-20 t-right">
                        <button class="btn btn-sub" type="button" onclick="handleAddContent()">추가</button>
                    </div>
    {{--                <div>--}}
    {{--                    내용--}}
    {{--                </div>--}}
    {{--                <div id="content-container">--}}
    {{--                    @if($firstPer)--}}
    {{--                        @foreach($firstPer->content as $content)--}}
    {{--                            <div>--}}
    {{--                                <input type="text" name="content[]" value="{{ $content }}" /><button type="button" onclick="handleDeleteContent(event)">삭제</button>--}}
    {{--                            </div>--}}
    {{--                        @endforeach--}}
    {{--                    @else--}}
    {{--                        <div>--}}
    {{--                            <input type="text" name="content[]" /><button type="button" onclick="handleDeleteContent(event)">삭제</button>--}}
    {{--                        </div>--}}
    {{--                    @endif--}}
    {{--                </div>--}}
    {{--                <div>--}}
    {{--                    <button type="button" onclick="handleAddContent()">추가</button>--}}
    {{--                </div>--}}
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
{{--        @else--}}
{{--            <p>업로드 하기 위해 먼저 학교, 연도, 학기를 선택해 주세요.</p>--}}
{{--        @endif--}}
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

    <div class="popup confirm" id="confirm-modal2" style="display:none">
        <div class="dim" onclick="handleConfirmModal('confirm-modal2', false)"></div>
        <div class="confirm-txt" id="confirmModal2Txt">
            과정명을 입력해주세요.
        </div>
        <div class="confirm-btn">
            <button class="btn w-100" onclick="handleConfirmModal('confirm-modal2', false)">확인</button>
        </div>
    </div>
@endsection
