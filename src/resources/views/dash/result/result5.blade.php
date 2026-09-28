@extends('layouts.result')

@section('script')
@endsection

@section('_content')
    <section class="content-wrap">
        <div class="content">
            <div class="col-wrap">
                <div class="content-card col col-12">
                    <div class="p-20 t-center img-wrap">
                        <img @if($thirdPer) src="{{ route('result5Image', ['imagePathName' => $thirdPer->imagePathName ?? 'no image']) }}" @endif alt="" />
                    </div>
                    <div class="line-div m-t-20"></div>
                    <ul class="check-list">
                        @if($thirdPer)
                            @foreach($thirdPer->content as $c)
                                <li class="check-list__item">
                                    {{ $c }}
                                </li>
                            @endforeach
                        @endif
{{--                        <li class="check-list__item">--}}
{{--                            건국대학교 재학생이 참여한 2020학년도 1학기 4개의 학점인정 과정은 '4차산업혁명시대 미래인재 핵심역량' 기반의 교과목으로써 '융합', '창의', '신기술활용', '문제해결', 등 역량 향상에 주요한 기여를 했을 것으로 기대됨--}}
{{--                        </li>--}}
{{--                        <li class="check-list__item">--}}
{{--                            건국대학교 재학생이 참여한 2020학년도 1학기 4개의 학점인정 과정은 '4차산업혁명시대 미래인재 핵심역량' 기반의 교과목으로써 '융합', '창의', '신기술활용', '문제해결', 등 역량 향상에 주요한 기여를 했을 것으로 기대됨--}}
{{--                        </li>--}}
{{--                        <li class="check-list__item">--}}
{{--                            건국대학교 재학생이 참여한 2020학년도 1학기 4개의 학점인정 과정은 '4차산업혁명시대 미래인재 핵심역량' 기반의 교과목으로써 '융합', '창의', '신기술활용', '문제해결', 등 역량 향상에 주요한 기여를 했을 것으로 기대됨--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
