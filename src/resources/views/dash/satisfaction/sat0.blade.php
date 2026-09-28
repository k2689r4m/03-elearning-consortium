@extends('layouts.sat')

@section('_script')
    <script>
        window.onload = () => {
            const data = {!! $shortAnswers !!};
            const uri = '{{ route('sat0View') }}';

            const select = document.getElementById('lectureId');
            select.addEventListener('change', (e) => {
                data.forEach((d) => {
                    if (d.id == e.target.value) {
                        document.getElementById('positive').innerHTML = `
                            <li class="dot-list__item blue">
                                ${d.positive}
                            </li>
                        `;

                        document.getElementById('negative').innerHTML = `
                            <li class="dot-list__item blue">
                                ${d.negative}
                            </li>
                        `;
                        document.getElementById('worldCloud').src = uri + '/'+d.wordCloudPathName;
                    }
                })
            })
        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap">
        <div class="content">
            <div class="col-wrap">
                <div class="content-top">
                    <div class="content-top__left">
                        <span class="fc-gray">과목명</span>
                        <select class="w-200" id="lectureId">
                            @foreach($shortAnswers as $shortAnswer)
                                <option value="{{ $shortAnswer->id }}">{{ $shortAnswer->getLectureName()->lectureName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="content-card col col-6 h-750">
                    <div class="h-50">
                        <span class="label label-blue">긍정적 의견</span>
                        <ul class="dot-list" id="positive">
                            <li class="dot-list__item blue">
                                {!! $shortAnswers->first()->positive ?? '' !!}
                            </li>
{{--                            <li class="dot-list__item blue">--}}
{{--                                약간 기초적인 부분부터 시작해서 기초를 다지기에 매우 좋았다고 생각한다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item blue">--}}
{{--                                기말 과제 설명을 위해 화상 강의로 한번 더 강의를 해주셔서 좋았다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item blue">--}}
{{--                                코딩에 대해서 쉽게 배울 수 있는 강의여서 좋았다. 아이디어를 표현하는 방법들을 알게 되었다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item blue">--}}
{{--                                실습 예시가 충분히 제공되어 과제를 하는데 도움이 되었다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item blue">--}}
{{--                                기본적으로 코딩과 카카오 오븐에 관심이 있는 학생들이 많은 도움을 받을 수 있는 강의 구성이었다.--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                    <div class="line-div m-t-30 m-b-30"></div>
                    <div class="h-50">
                        <span class="label label-red">부정적 의견</span>
                        <ul class="dot-list" id="negative">
                            <li class="dot-list__item red">
                                {!! $shortAnswers->first()->negative ?? '' !!}
                            </li>
{{--                            <li class="dot-list__item red">--}}
{{--                                과제가 매우 어렵다--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item red">--}}
{{--                                강의자료가 더 디테일한 텍스트로 나왔으면 한다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item red">--}}
{{--                                교수님이 말하시는 내용과 강의 속 내용이 차이가 있다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item red">--}}
{{--                                양이 너무 많다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item red">--}}
{{--                                pdf 자료가 수업내용에 비해 부족하다고 느꼈습니다.--}}
{{--                            </li>--}}
{{--                            <li class="dot-list__item red">--}}
{{--                                최근 사례가 반영이 되면 좋을 것 같습니다.--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                </div>
                <div class="content-card col col-6 h-750 word-cloud__wrap">
                    @if(isset($shortAnswers->first()->wordCloudPathName))
                    <img src="{{ route('sat0Image', ['imagePathName' => $shortAnswers->first()->wordCloudPathName ?? '']) }}" id="worldCloud" />
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
