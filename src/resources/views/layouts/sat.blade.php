@extends('layouts.app')

@section('script')
    @yield('_script')
@endsection

<?php
$request_uri = $_SERVER['REQUEST_URI'];
$uri = substr($request_uri, 1, 4).'View';
?>

@section('content')
    <section class="content-wrap">
        <div class="content-top">
            <div class="content-top__left">
                <h3 class="tit">만족도조사</h3>
            </div>
            @if(!strpos($request_uri,"sat1"))
            <form class="w-100" method="GET" action="{{ route($uri) }}">
                <div class="content-top__right">
                    <span class="fc-gray">학기정보</span>
                    <select name="year" id="year">
                        @foreach(range(2010, 2050) as $y)
                            <option value="{{ $y }}" @if ($y == $year) selected @endif>{{ $y }}</option>
                        @endforeach
                    </select>
                    <span class="fc-gray">학년도</span>
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
                    <span class="fc-gray"> 학기</span>
                    <button class="btn btn-sm btn-primary">검색</button>
                </div>
            </form>
            @endif
        </div>
        <div class="main-tab">
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat1")) active @endif" onclick="location.href='{{ route('sat1View') }}'">조사개요</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat2")) active @endif" onclick="location.href='{{ route('sat2View') }}'">만족도종합</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat3")) active @endif" onclick="location.href='{{ route('sat3View') }}'">자기평가</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat4")) active @endif" onclick="location.href='{{ route('sat4View') }}'">강의지원</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat5")) active @endif" onclick="location.href='{{ route('sat5View') }}'">학습내용・교수법</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat6")) active @endif" onclick="location.href='{{ route('sat6View') }}'">학습평가</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat7")) active @endif" onclick="location.href='{{ route('sat7View') }}'">교육운영</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat8")) active @endif" onclick="location.href='{{ route('sat8View') }}'">시스템이용</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat9")) active @endif" onclick="location.href='{{ route('sat9View') }}'">전반적만족도</button>
            <button class="btn main-tab__btn @if(strpos($request_uri,"sat0")) active @endif" onclick="location.href='{{ route('sat0View') }}'">주관식문항</button>
            {{--            <button class="btn <?php if(strpos($request_uri,"sat4")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.per4View') }}'">미래인재 핵심역량</button>--}}
        </div>
    </section>
    @yield('_content')
@endsection
