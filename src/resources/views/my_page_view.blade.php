@extends('layouts.app')

{{--@section('content')--}}
{{--    <my_page_main></my_page_main>--}}
{{--@endsection--}}

@section('script')
    <script>
        const scrollBottom = () => {
            window.scrollTo(0, 9999);
        }
    </script>
@endsection

@section('content')
    <section class="content-wrap main">
        <p class="top-txt">
            <span class="fc-blue">대학 e-러닝 기반 학점인정 컨소시엄 사업</span>은<br>
            정부 정책 및 교육부의 대학평가, 재정지원사업, 학사제도 발전방안에<br>
            적극 대응하기 위한 컨소시엄 형태의 <span class="fc-green">스마트 러닝 연합·공유 체제</span> 입니다.
        </p>
        <div class="main-image">
            <div class="main-image__img">
                @if ($image1 ?? false)
                    <img src="{{ route('basicImage', ['fileName' => $image1]) }}" />
                @else
                    이미지 영역
                @endif
            </div>
            <div class="main-image__txt">

                <ul class="list">
                    <li class="list__item">
                        <div class="numbering">01</div>
                        <p class="txt">
                            e-러닝 콘텐츠 공동 개발 및 활용을 통한 <br>
                            온라인 기반 학점교류 연합·공유 체제 구축
                        </p>
                    </li>
                    <li class="list__item">
                        <div class="numbering">02</div>
                        <p class="txt">
                            일반대학, 전문대학, 특수대학 등 <br>
                            전국 대학의 지속적인 참여 확대
                        </p>
                    </li>
                    <li class="list__item">
                        <div class="numbering">03</div>
                        <p class="txt">
                            참여 대학의 우수한 교수진이 제공하는 <br>
                            특화된 교육과정 공유를 통한 역량 습득 다양화
                        </p>
                        <button class="btn next-page" onclick="scrollBottom()">></button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-image">
            <div class="main-image__img">
                @if ($image2 ?? false)
                    <img src="{{ route('basicImage', ['fileName' => $image2]) }}" />
                @else
                    이미지 영역
                @endif
            </div>
            <div class="main-image__txt">
                <ul class="list">
                    <li class="list__item">
                        <div class="numbering">04</div>
                        <p class="txt">
                            일반대학 원격 수업 운영기준 준수 및 <br>
                            국내 최고 수준의 e-러닝 시스템 운영
                        </p>
                    </li>
                    <li class="list__item">
                        <div class="numbering">05</div>
                        <p class="txt">
                            <strong>‘군 e-러닝’</strong> 학점인정 컨소시엄 운영 확대로<br>
                            국복무 중 학점 취득 및 자기주도학습 실현
                        </p>
                    </li>
                    <li class="list__item">
                        <div class="numbering">06</div>
                        <p class="txt">
                            고품질 콘텐츠 제공을 통한 학습 효과 상승 및<br>
                            교육 성과 창출의 기회
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </section>
@endsection
