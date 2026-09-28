@extends('admin.layouts.total')

@section('script')
    <script>
        const handleConfirmModal = (status) => {
            const confirmModal = document.querySelector('#confirm-modal')
            if (status) {
                confirmModal.style.display = 'block';
            }
            else {
                confirmModal.style.display = 'none';
            }
        }

        const imgPreview = (e) => {
            let reader = new FileReader();
            let changeImg = document.getElementById(e.name);
            console.log(changeImg);
            if(e.value.length != 0){
                reader.readAsDataURL(e.files[0]);
                reader.onload = () => {
                    changeImg.src = reader.result;
                };
            }
        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap bg-white">
        <div class="admin-input">
            <form method="POST" action="{{ route('admin.total5Edit') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="userId" value="{{ $userId }}" />
                <input type="hidden" name="year" value="{{ $year }}" />
                <input type="hidden" name="month" value="{{ $month }}" />
                <input name="shortAnswerId" type="hidden" value="{{ $shortAnswer->id }}" />
                <div>
                    <label>과목명</label>
{{--                    <input type="text" name="lectureName" value="{{ $shortAnswer->lectureName ?? old('lectureName') }}" />--}}
                    <select name="lectureId">
                        @foreach($lectures as $lecture)
                            <option value="{{ $lecture->id }}" @if($shortAnswer->lectureId == $lecture->id) selected @endif>{{ $lecture->lectureName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>긍정적 의견</label>
                    <textarea rows="10" name="positive">{{ $shortAnswer->positive ?? old('positive') }}</textarea>
                </div>
                <div>
                    <label>부정적 의견</label>
                    <textarea rows="10" name="negative">{{ $shortAnswer->negative ?? old('negative') }}</textarea>
                </div>
                <div>
                    <label>워드클라우드 이미지</label>
                    <div class="img-wrap__lg">
                        <img src="{{ route('sat0Image', ['imagePathName' => $shortAnswer->wordCloudPathName]) }}" id="wordCloud" />
                    </div>
                    <input type="file" name="wordCloud" accept="image/*" onchange="imgPreview(this)" />
{{--                    <label>워드클라우드 이미지</label> <input type="file" name="wordCloud" value="{{ $shortAnswer->wordCloud ?? old('wordCloud') }}" />--}}
                </div>
                <div class="m-t-20">
                    <button class="btn btn-gray btn-lg" type="button" onclick="location.href='{{ route('admin.total5View', ['userId' => $userId, 'year' => $year, 'month' => $month]) }}'">취소</button>
                    <button class="btn btn-primary btn-lg">수정</button>
                </div>
            </form>
        </div>

        @error('required')
        <div class="popup confirm" id="confirm-modal">
            <div class="dim" onclick="handleConfirmModal(false)"></div>
            <div class="confirm-txt">
                {{ $message }}
            </div>
            <div class="confirm-btn">
                <button type="button" class="btn w-100" onclick="handleConfirmModal(false)">확인</button>
            </div>
        </div>
        @enderror
    </section>
@endsection
<style>

    .btn {
        font-family: "NanumSquare", sans-serif;
        border: none;
        font-size: 14px;
        height: 38px;
        line-height: 31px;
        padding: 0 40px;
        cursor: pointer;
        border-radius: 4px;
        background: transparent;
        outline: none;
        vertical-align: middle;
    }

    .btn-primary {
        border: 1px solid #00467f;
        background: #00467f;
        color: #fff;
    }

    .btn-gray {
        background: #e4e4e4;
        border: 1px solid #e4e4e4;
    }

    .admin-input{
        text-align: center;
        display:flex;
        justify-content: center;
        align-items: center;
    }

    .admin-input label{
        display:block !important;
    }

    .admin-input input {
        border: 1px solid #cfdbe5;
        padding: 7px 10px 8px;
        border-radius: 4px;
        width: 300px !important;
        margin: 10px 5px;
        background: #fff;
    }

    .img-wrap__lg{
        width:auto !important;
        max-width: 100% !important;
        margin:20px auto 0;
    }

    .img-wrap__lg img{
        max-width: 100%;
    }

</style>
