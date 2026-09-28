@extends('admin.layouts.per')

@section('_script')
    <script>
        const imgPreview = (e) => {
            let reader = new FileReader();
            let changeImg = document.getElementById(e.name);
            if(e.value.length != 0){
                reader.readAsDataURL(e.files[0]);
                reader.onload = () => {
                    changeImg.src = reader.result;
                };
            }
        }

        window.onload = () => {
            @if ($userId)
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
            @endif
        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap bg-white">
        <div class="btn-tab__wrap">
            <h3 class="admin-tit">대학교 선택</h3>
            @foreach($univ as $u)
                <button class="btn btn-tab" onclick="
                    document.querySelector('#userId').value = '{{ $u->id }}';
                    document.querySelector('#search').click();
                    "
                        @if($userId && $userId == $u->id)
                        disabled
                    @endif
                >{{ $u->univName }}</button>
            @endforeach
        </div>
        <hr>
        <form class="w-100" method="GET" action="{{ route('admin.per4View') }}">
            <input type="hidden" name="userId" id="userId" @if($userId) value="{{ $userId }}" @endif />
            <div class="admin-con__top">
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
                <button class="btn btn-primary" id="search">검색</button>

                @if ($userId)
                    <button class="btn btn-primary fr" type="button" onclick="document.getElementById('imageForm').submit()">저장</button>
                @endif
            </div>
        </form>
        @if ($userId)
            <form method="POST" action="{{ route('admin.per4Write') }}" enctype="multipart/form-data" id="imageForm">
                @csrf
                <input type="hidden" name="year" id="_year" value="{{ $year }}" />
                <input type="hidden" name="month" id="_month" value="{{ $month }}" />
                <input type="hidden" name="userId" value="{{ $userId }}" />

                <div class="admin-input">
                    <label class="img-upload">
                        <img @if($fourthPer && $fourthPer->imagePathName) src="{{ route('result6Image', ['imagePathName' => $fourthPer->imagePathName]) }}" @endif id="image" />
                        <input type="file" name="image" accept="image/*" onchange="imgPreview(this)" />
                    </label>
                </div>
            </form>
            {{--        <form method="POST" action="{{ route('admin.total3Excel') }}" enctype="multipart/form-data">--}}
            {{--            @csrf--}}
            {{--            <input type="hidden" name="year" id="_year" value="{{ $year }}" />--}}
            {{--            <input type="hidden" name="month" id="_month" value="{{ $month }}" />--}}
            {{--            <input type="hidden" name="userId" value="{{ $userId }}" />--}}

            {{--            <input type="file" name="excel" />--}}
            {{--            <button>저장</button>--}}
            {{--        </form>--}}
        @else
            <p>업로드 하기 위해 먼저 학교, 연도, 학기를 선택해 주세요.</p>
        @endif
        <div>
            {{--        @if($thirdTotal)--}}
            {{--            {{ $thirdTotal->id }}--}}
            {{--        @endif--}}
        </div>
    </section>
@endsection
