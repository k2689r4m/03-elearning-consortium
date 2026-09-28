@extends('admin.layouts.total')

@section('_script')
    <script>
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

        const handleConfirmModal = (id, status) => {
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
                <form method="GET" action="{{ route('admin.total4View') }}">
                    <input type="hidden" name="userId" id="userId" @if($userId) value="{{ $userId }}" @endif />
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
                </form>
                <div class="right">
                    @if ($userId)
                        <form method="POST" action="{{ route('admin.total4Excel') }}" enctype="multipart/form-data" id="excelForm">
                            @csrf
                            <input type="hidden" name="year" id="_year" value="{{ $year }}" />
                            <input type="hidden" name="month" id="_month" value="{{ $month }}" />
                            <input type="hidden" name="userId" value="{{ $userId }}" />

                            <label class="excel-upload btn btn-primary btn-line">
                                엑셀업로드<input type="file" name="excel" accept=".xls,.xlsx" />
                            </label>
                            <button class="btn btn-primary fr" type="button" onclick="document.getElementById('excelForm').submit()">저장</button>
                        </form>
                    @endif
                </div>
            </div>
        @if ($userId)

        <div>
            <table class="admin-table">
                <tr>
                    <th>No</th>
                    <th>참여과목명</th>
                    <th>학점</th>
                    <th>과목구분</th>
                    <th>담당교수 소속대학</th>
                    <th>교수명</th>
                    <th>종합만족도</th>
                    <th>자기평가</th>
                    <th>강의지원</th>
                    <th>학습내용/교수자평가</th>
                    <th>학습평가</th>
                    <th>교육운영</th>
                    <th>시스템</th>
                    <th>전반적 만족도</th>
                </tr>
            @if(gettype($fourthTotal) == 'object' && count($fourthTotal) > 0)
                @foreach($fourthTotal as $f)
                    <tr>
{{--                        <td>{{ $f->id ? $f->id : '-' }}</td>--}}
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $f->lectureName ? $f->lectureName : '-' }}</td>
                        <td>{{ $f->grades ? $f->grades : '-' }}</td>
                        <td>{{ $f->subjectClassification ? $f->subjectClassification : '-' }}</td>
                        <td>{{ $f->professorUnivName ? $f->professorUnivName : '-' }}</td>
                        <td>{{ $f->professorName ? $f->professorName : '-' }}</td>
                        <td>{{ $f->overallSatisfactionRate ? $f->overallSatisfactionRate : '-' }}</td>
                        <td>{{ $f->selfEvaluationRate ? $f->selfEvaluationRate : '-' }}</td>
                        <td>{{ $f->lectureSupportRate ? $f->lectureSupportRate : '-' }}</td>
                        <td>{{ $f->learningContentEvaluationRate ? $f->learningContentEvaluationRate : '-' }}</td>
                        <td>{{ $f->evaluationRate ? $f->evaluationRate : '-' }}</td>
                        <td>{{ $f->operatorEvaluationRate ? $f->operatorEvaluationRate : '-' }}</td>
                        <td>{{ $f->systemEvaluationRate ? $f->systemEvaluationRate : '-' }}</td>
                        <td>{{ $f->totalSatisfactionRate ? $f->totalSatisfactionRate : '-' }}</td>
                    </tr>
                @endforeach
            @endif
            </table>
        </div>
        @else
            <p>업로드 하기 위해 먼저 학교, 연도, 학기를 선택해 주세요.</p>
        @endif

        @error('error')
        <div class="popup confirm" id="confirm-modal" style="display: block;">
            <div class="dim" onclick="handleConfirmModal('confirm-modal', false)"></div>
            <div class="confirm-txt">
                {{ $message }}
            </div>
            <div class="confirm-btn">
                <button class="btn w-100" type="button" onclick="handleConfirmModal('confirm-modal', false)">확인</button>
            </div>
        </div>
        @enderror
    </section>
@endsection
