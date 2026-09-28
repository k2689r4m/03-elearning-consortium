@extends('admin.layouts.admin')

@section('script')
    @yield('_script')
@endsection

<?php
$request_uri = $_SERVER['REQUEST_URI'];
?>

@section('content')
    <div class="admin-top">
        {{--        <ul class="page-nav">--}}
        {{--            <li class="page-nav__item">메인</li>--}}
        {{--            <li class="page-nav__item">종합정보관리</li>--}}
        {{--        </ul>--}}
        <div class="tab-menu">
            <button class="btn <?php if(strpos($request_uri,"per1")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.per1View') }}'">종합</button>
            <button class="btn <?php if(strpos($request_uri,"per2")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.per2View') }}'">과정별성취목표역량</button>
            <button class="btn <?php if(strpos($request_uri,"per3")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.per3View') }}'">발전방안</button>
            <button class="btn <?php if(strpos($request_uri,"per4")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.per4View') }}'">학점교류 추천과정</button>
        </div>
    </div>
    @yield('_content')
@endsection
