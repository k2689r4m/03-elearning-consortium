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
        </form>
            <div class="right">
            <form method="POST" action="{{ route('admin.total1Excel') }}" enctype="multipart/form-data">
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
            <thead>
            <tr>
                <th>전체과목수</th>
                <th>전체인원수</th>
                <th>전체출석률</th>
                <th>전체지각률</th>
                <th>전체결석률</th>
                <th>전체학습<br/>진도율</th>
                <th>전체최종<br/>수료율</th>
{{--                <th>전체최종성적<br/>60점이상수료율</th>--}}
                <th>전체시험<br/>응시율</th>
                <th>전체과제<br/>수행률</th>
                <th>전체토론<br/>참여율</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            @if($firstTotal)
                <td>{{ $firstTotal->lectureCount }}</td>
                <td>{{ $firstTotal->memberCount }}</td>
                <td>{{ $firstTotal->attendanceRate }}%</td>
                <td>{{ $firstTotal->lateRate }}%</td>
                <td>{{ $firstTotal->absenceRate }}%</td>
                <td>{{ $firstTotal->learningProgressRate }}%</td>
                <td>{{ $firstTotal->completionRate }}%</td>
{{--                <td>{{ $firstTotal->overSixtyRate }}%</td>--}}
                <td>{{ $firstTotal->testApplicationRate }}%</td>
                <td>{{ $firstTotal->taskPerformanceRate }}%</td>
                <td>{{ $firstTotal->discussionParticipationRate }}%</td>
            @else
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{--<td></td>--}}
                <td></td>
                <td></td>
                <td></td>
            @endif
            </tr>
            </tbody>
        </table>
        <table class="admin-table">
            <tr>
                <th>전체퀴즈<br/>수행율</th>
                <th>전체학습<br/>참여도</th>
                <th>전체종합<br/>만족도</th>
                <th>전체자기평가<br/>만족도</th>
                <th>전체강의지원<br/>만족도</th>
                <th>전체학습내용/<br/>교수자평가만족도</th>
                <th>전체학습평가<br/>만족도</th>
                <th>전체교육운영<br/>만족도</th>
                <th>전체시스템<br/>만족도</th>
                <th>전체전반적인<br/>만족도</th>
            </tr>
            <tr>
                @if($firstTotal)
                <td>{{ $firstTotal->quizProgressRate }}%</td>
                <td>{{ $firstTotal->learningParticipationRate }}%</td>
                <td>{{ $firstTotal->satisfactionRate }}</td>
                <td>{{ $firstTotal->selfEvaluationSatisfactionRate }}</td>
                <td>{{ $firstTotal->lectureSupportSatisfactionRate }}</td>
                <td>{{ $firstTotal->overallSatisfactionRate }}</td>
                <td>{{ $firstTotal->learningEvaluationSatisfactionRate }}</td>
                <td>{{ $firstTotal->educationOperationSatisfactionRate }}</td>
                <td>{{ $firstTotal->overallSystemSatisfactionRate }}</td>
                <td>{{ $firstTotal->totalSatisfactionRate }}</td>
                @else
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @endif
            </tr>
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
