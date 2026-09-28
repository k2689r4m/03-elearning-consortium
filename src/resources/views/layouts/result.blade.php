@extends('layouts.app')

@section('script')
    @yield('_script')
@endsection

<?php
$request_uri = $_SERVER['REQUEST_URI'];
$uri = substr($request_uri, 1, 7).'View';
?>

@section('content')
    <section class="content-wrap">
        <div class="content-top">
            <div class="content-top__left">
                <h3 class="tit">사업참여성과</h3>
            </div>
            @if(!strpos($request_uri,"result1") && !strpos($request_uri,"result3"))
            <form class="w-100" method="GET" action="{{ route($uri) }}">
                <div class="content-top__right">
                    <span class="fc-gray">학기정보</span>
                    <select name="year" id="year">
                        @foreach(range(2010, 2050) as $y)
                            <option value="{{ $y }}" @if ($y == ($year ?? '')) selected @endif>{{ $y }}</option>
                        @endforeach
                    </select>
                    <span class="fc-gray">학년도</span>
                    @if(strpos($request_uri,"result2"))
                    <select name="month" id="month">
                        @foreach([1,2] as $m)
                            <option value="{{ $m }}" @if ($m == $month) selected @endif>{{ $m }}</option>
                        @endforeach
                    </select>
                    @else
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
                    @endif
                    <span class="fc-gray"> 학기</span>
                    <button class="btn btn-sm btn-primary">검색</button>
                </div>
            </form>
            @endif
        </div>
        <div class="main-tab">
            <button class="btn main-tab__btn @if(strpos($request_uri,"result1")) active @endif" onclick="location.href='{{ route('result1View') }}'">성과관리체계</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"result2")) active @endif" onclick="location.href='{{ route('result2View') }}'">참여성과 Analytics</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"result3")) active @endif" onclick="location.href='{{ route('result3View') }}'">미래인재 핵심역량</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"result4")) active @endif" onclick="location.href='{{ route('result4View') }}'">과정별 성취목표 역량</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"result5")) active @endif" onclick="location.href='{{ route('result5View') }}'">발전방안</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"result6")) active @endif" onclick="location.href='{{ route('result6View') }}'">추천과정</button>
        </div>
    </section>
    @yield('_content')
@endsection
