@extends('layouts.result')

@section('script')
@endsection

@section('_content')
    <section class="content-wrap">
        <div class="content">
            <div class="col-wrap">
                <div class="content-card col col-12">
                    <h4 class="content-card__tit">{{ $year }}학년도 {{ $month }}학기 학점인정 과정별 성취 목표 역량</h4>
                    <table class="table t-center br-b m-t-20">
                        <colgroup>
                            <col width="23%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                            <col width="7%" />
                        </colgroup>
                        <tr>
                            <th rowspan="3">과정명</th>
                            <th colspan="11">4차 산업혁명 시대 미래인재 핵심 역량 (출처 : 교육부, 2017)</th>
                        </tr>
                        <tr>
                            <th colspan="5">1순위</th>
                            <th colspan="2">2순위</th>
                            <th colspan="4">3순위</th>
                        </tr>
                        <tr>
                            <th class="bg-blue2">융합</th>
                            <th class="bg-blue2">창의</th>
                            <th class="bg-blue2">전문성</th>
                            <th class="bg-blue2">정보통신</th>
                            <th class="bg-blue2">문제예측</th>
                            <th class="bg-blue2">신기술활용</th>
                            <th class="bg-blue2">표현(구현)</th>
                            <th class="bg-blue2">협동적수행</th>
                            <th class="bg-blue2">정보판별력</th>
                            <th class="bg-blue2">직업적용</th>
                            <th class="bg-blue2">문제해결</th>
                        </tr>
                        @foreach($secondPer as $s)
                        <tr>
                            <td>{{ $s->lectureName }}</td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->fusion) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->creative) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->professionalism) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->informationCommunication) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->problemPrediction) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->utilizationNewTechnology) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->expression) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->collaboration) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->discrimination) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->adaptation) checked @endif /></td>
                            <td><input type="checkbox" class="checked-view" disabled @if($s->solution) checked @endif /></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
