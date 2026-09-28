@extends('layouts.result')

@section('script')
@endsection

@section('_content')
    <section class="content-wrap">
        <div class="content">
            <div class="col-wrap">
                <div class="content-card col col-12">
{{--                    <h4 class="content-card__tit">{{ $year }}학년도 {{ $month }}학기 학점인정 과정별 성취 목표 역량</h4>--}}
                    <div class="p-20 t-center img-wrap">
                        <img @if($fourthPer) src="{{ route('result6Image', ['imagePathName' => $fourthPer->imagePathName]) }}" @endif alt="" />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
