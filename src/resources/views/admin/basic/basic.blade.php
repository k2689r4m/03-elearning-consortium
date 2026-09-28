@extends('admin.layouts.admin')

@section('script')
    <script>
        const handleConfirmModal = (id, status) => {
            const confirmModal = document.querySelector('#' + id)
            if (status) {
                confirmModal.style.display = 'block';
            }
            else {
                confirmModal.style.display = 'none';
            }
        }

        {{--const requestImage = () => {--}}
        {{--    const year = document.querySelector('#year');--}}
        {{--    const month = document.querySelector('#month');--}}

        {{--    const formData = new FormData();--}}

        {{--    formData.append('year', year.value);--}}
        {{--    formData.append('month', month.value);--}}

        {{--    const request = new XMLHttpRequest();--}}

        {{--    request.open('post', '{{ route('admin.basicImageApi') }}', true);--}}
        {{--    request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');--}}

        {{--    request.onload = (e) => {--}}
        {{--        if (request.status === 200) {--}}
        {{--            // console.log(request.response);--}}
        {{--            const images = JSON.parse(request.response);--}}
        {{--            document.querySelector('#image1').src = images.image1;--}}
        {{--            document.querySelector('#image2').src = images.image2;--}}
        {{--        }--}}
        {{--        else {--}}
        {{--            console.log('fail');--}}
        {{--        }--}}
        {{--    }--}}

        {{--    request.send(formData);--}}
        {{--}--}}

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

        // window.onload = () => {
        //     // ['#year', '#month'].forEach((selector) => {
        //     //     const dom = document.querySelector(selector);
        //     //     dom.addEventListener('change', () => {
        //     //         requestImage();
        //     //     })
        //     // });
        //     // imgPreview();
        //     // requestImage();
        //     // document.querySelectorAll('.img-upload input').forEach(function(e){
        //     //     console.log(e.value);
        //     //     imgPreview(e);
        //     });
        // }
    </script>
@endsection

@section('content')
    @error('images')
    <div class="popup confirm" id="confirm-modal2">
        <div class="dim" onclick="handleConfirmModal('confirm-modal2', false)"></div>
        <div class="confirm-txt">
            {{ $message }}
        </div>
        <div class="confirm-btn">
            <button class="btn w-100" onclick="handleConfirmModal('confirm-modal2', false)">확인</button>
        </div>
    </div>
    @enderror
    <section class="content-wrap bg-white">
        <form method="GET" action="{{ route('admin.basicView') }}">
            <div class="admin-con__top t-right">
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
                <button class="btn btn-primary">검색</button>
            </div>
        </form>
    <form method="POST" action="{{ route('admin.basic') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="year" value="{{ $year }}" />
        <input type="hidden" name="month" value="{{ $month }}" />

        <div class="img-upload__wrap">
            <label class="img-upload">
                1번 이미지
                <img src="{{ is_null($basic) ? old('image1') : asset('basic/img/'.$basic->firstImagePathName) }}" id="image1">
{{--                <img src="{{ is_null($basic) ? old('image1') : asset('storage/img/'.$basic->firstImagePathName) }}" id="image1">--}}
                <input type="file" name="image1" accept="image/*" onchange="imgPreview(this)" />
            </label>
            <br />
            <label class="img-upload">
                2번 이미지
                <img src="{{ is_null($basic) ? '' : asset('basic/img/'.$basic->secondImagePathName) }}" id="image2">
{{--                <img src="{{ is_null($basic) ? '' : asset('storage/img/'.$basic->secondImagePathName) }}" id="image2">--}}
                <input type="file" name="image2" accept="image/*" onchange="imgPreview(this)" />
            </label>
        </div>

        <div class="t-center">
            <button class="btn btn-gray btn-lg" type="button" onclick="location.href='{{ route('admin.memberView') }}'">취소</button>
            <button class="btn btn-primary btn-lg" type="button" onclick="handleConfirmModal('confirm-modal', true)">저장</button>
        </div>

        <div class="popup confirm" id="confirm-modal" style="display: none">
            <div class="dim" onclick="handleConfirmModal('confirm-modal', false)"></div>
            <div class="confirm-txt">
                저장하시겠습니까?
            </div>
            <div class="confirm-btn">
                <button class="btn gray" type="button" onclick="handleConfirmModal('confirm-modal', false)">취소</button>
                <button class="btn">저장</button>
            </div>
        </div>
    </form>
    </section>
@endsection
