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
                <form method="GET" action="{{ route('admin.total3View') }}">
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
                        <form method="POST" action="{{ route('admin.total3Excel') }}" enctype="multipart/form-data" id="excelForm">
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

        <div class="w-scroll p-b-10">
            <table class="admin-table">
                <tr>
                    <th>No</th>
                    <th>과목명</th>
                    <th>학점</th>
                    <th>과목구분</th>
                    <th>담당교수 소속대학</th>
                    <th>교수명</th>
                    <th>수강생수</th>
                    <th>수료자수</th>
                    <th>최종수료율</th>
                    <th>최종성적<br/>60점이상취득자수</th>
                    <th>최종성적<br/>60점이상취득률</th>
                    <th>학습진도율</th>
                    <th>출석률</th>
                    <th>지각률</th>
                    <th>결석률</th>
                    <th>시험응시자수(중간)</th>
                    <th>시험응시율(중간)</th>
                    <th>시험응시자수(기말)</th>
                    <th>시험응시율(기말)</th>
                    <th>시험응시율(전체)</th>
                    <th>과제수행률</th>
                    <th>토론참여율</th>
                    <th>퀴즈수행률</th>
                    <th>학습참여도</th>
                </tr>
            @if(gettype($thirdTotals) == 'object' && count($thirdTotals) > 0)
                @foreach($thirdTotals as $thirdTotal)
                    <tr>
{{--                        <td>{{$thirdTotal->id ?? '-'}}</td>--}}
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{$thirdTotal->lectureName ? $thirdTotal->lectureName : '-'}}</td>
                        <td>{{$thirdTotal->grades ? $thirdTotal->grades : '-'}}</td>
                        <td>{{$thirdTotal->subjectClassification ? $thirdTotal->subjectClassification : '-'}}</td>
                        <td>{{$thirdTotal->professorUnivName ? $thirdTotal->professorUnivName : '-'}}</td>
                        <td>{{$thirdTotal->professorName ? $thirdTotal->professorName : '-'}}</td>
                        <td>{{$thirdTotal->memberCount ? $thirdTotal->memberCount : '-'}}</td>
                        <td>{{$thirdTotal->graduatesCount ? $thirdTotal->graduatesCount : '-'}}</td>
                        <td>{{$thirdTotal->finalCompletionRate ? $thirdTotal->finalCompletionRate : '-'}}{{$thirdTotal->finalCompletionRate ? '%' : ''}}</td>
                        <td>{{ $thirdTotal->FinalScoreSixtyPointTakers ? $thirdTotal->FinalScoreSixtyPointTakers : '-' }}</td>
                        <td>{{ $thirdTotal->FinalScoreSixtyPointApplicantRate ? $thirdTotal->FinalScoreSixtyPointApplicantRate : '-' }}{{ $thirdTotal->FinalScoreSixtyPointApplicantRate ? '%' : '' }}</td>
{{--                        <td>{{$thirdTotal->sum ? $thirdTotal->sum : '-'}}</td>--}}
                        <td>{{$thirdTotal->learningProgressRate ? $thirdTotal->learningProgressRate : '-'}}{{$thirdTotal->learningProgressRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->attendanceRate ? $thirdTotal->attendanceRate : '-'}}{{$thirdTotal->attendanceRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->lateRate ? $thirdTotal->lateRate : '-'}}{{$thirdTotal->lateRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->absenceRate ? $thirdTotal->absenceRate : '-'}}{{$thirdTotal->absenceRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->midtermTestTakers ? $thirdTotal->midtermTestTakers : '-'}}</td>
                        <td>{{$thirdTotal->midtermTestApplicationRate ? $thirdTotal->midtermTestApplicationRate : '-'}}{{$thirdTotal->midtermTestApplicationRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->finalTestTakers ? $thirdTotal->finalTestTakers : '-'}}</td>
                        <td>{{$thirdTotal->finalTestApplicationRate ? $thirdTotal->finalTestApplicationRate : '-'}}{{$thirdTotal->finalTestApplicationRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->testApplicationRate ? $thirdTotal->testApplicationRate : '-'}}{{$thirdTotal->testApplicationRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->taskPerformanceRate ? $thirdTotal->taskPerformanceRate : '-'}}{{$thirdTotal->taskPerformanceRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->discussionParticipationRate ? $thirdTotal->discussionParticipationRate : '-'}}{{$thirdTotal->discussionParticipationRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->quizProgressRate ? $thirdTotal->quizProgressRate : '-'}}{{$thirdTotal->quizProgressRate ? '%' : ''}}</td>
                        <td>{{$thirdTotal->learningParticipationRate ? $thirdTotal->learningParticipationRate : '-'}}{{$thirdTotal->learningParticipationRate ? '%' : ''}}</td>
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
