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
            <button class="btn <?php if(strpos($request_uri,"total1")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.total1View') }}'">전체</button>
            <button class="btn <?php if(strpos($request_uri,"total2")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.total2View') }}'">전체만족도</button>
            <button class="btn <?php if(strpos($request_uri,"total3")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.total3View') }}'">과목별정보(학교별)</button>
            <button class="btn <?php if(strpos($request_uri,"total4")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.total4View') }}'">만족도(학교별)</button>
            <button class="btn <?php if(strpos($request_uri,"total5")) { ?>active<?php } ?>" onclick="location.href='{{ route('admin.total5View') }}'">주관식관리</button>
        </div>
    </div>
    @yield('_content')
@endsection
