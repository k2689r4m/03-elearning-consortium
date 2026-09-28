@extends('admin.layouts.total')

@section('_script')
    <script>
        window.onload = () => {
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
        <div class="admin-con__top">
            <form>
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
                    <button class="btn btn-primary">검색</button>
                </div>
            </form>
            <div class="right">
                <form method="POST" action="{{ route('admin.total2Excel') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="year" id="_year" value="{{ $year }}" />
                    <input type="hidden" name="month" id="_month" value="{{ $month }} "/>

                    <label class="excel-upload btn btn-primary btn-line">
                        엑셀업로드<input type="file" name="excel" accept=".xls,.xlsx" />
                    </label>
                    <button class="btn btn-primary">저장</button>
                </form>
            </div>
        </div>
        <hr>
        <table class="admin-table">
            <tr>
                <th>No</th>
                <th>과목명</th>
                <th>학점</th>
                <th>과목구분</th>
                <th>담당교수<br/>소속대학</th>
                <th>교수명</th>
                <th>종합<br/>만족도</th>
                <th>자기평가</th>
                <th>강의지원</th>
                <th>학습내용/<br/>교수자평가</th>
                <th>학습평가</th>
                <th>교육운영</th>
                <th>시스템</th>
                <th>전체<br/>만족도</th>
            </tr>
            @if(gettype($secondTotals) == 'object' && count($secondTotals) > 0)
                @foreach($secondTotals as $secondTotal)
                    <tr>
{{--                        <td>{{ $secondTotal->id}}</td>--}}
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $secondTotal->lectureName}}</td>
                        <td>{{ $secondTotal->grades}}</td>
                        <td>{{ $secondTotal->subjectClassification}}</td>
                        <td>{{ $secondTotal->professorUnivName}}</td>
                        <td>{{ $secondTotal->professorName}}</td>
                        <td>{{ $secondTotal->overallSatisfactionRate}}</td>
                        <td>{{ $secondTotal->selfEvaluationRate}}</td>
                        <td>{{ $secondTotal->lectureSupportRate}}</td>
                        <td>{{ $secondTotal->learningContentEvaluationRate}}</td>
                        <td>{{ $secondTotal->evaluationRate}}</td>
                        <td>{{ $secondTotal->operatorEvaluationRate}}</td>
                        <td>{{ $secondTotal->systemEvaluationRate}}</td>
                        <td>{{ $secondTotal->totalSatisfactionRate}}</td>
                    </tr>
                @endforeach
            @else
            @endif
        </table>

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
