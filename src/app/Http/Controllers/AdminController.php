<?php

namespace App\Http\Controllers;

use App\Models\FirstPer;
use App\Models\FirstTotal;
use App\Models\FourthPer;
use App\Models\FourthTotal;
use App\Models\SecondPer;
use App\Models\SecondTotal;
use App\Models\ShortAnswer;
use App\Models\ThirdPer;
use App\Models\ThirdTotal;
use http\Params;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Basic;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FirstTotalImport;
use App\Imports\SecondTotalImport;
use App\Imports\ThirdTotalImport;
use App\Imports\FourthTotalImport;

class AdminController extends Controller
{
    //
    public function loginView (Request $request) {
        return view('admin.auth.login');
    }

    //custom admin login route
    public function login (Request $request) {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->admin) {
                $request->session()->regenerate();

                return redirect()->route('admin.memberView');
            }
            else {
                Auth::logout();
                return redirect()->back()->withErrors(['permission' => '접근이 허용된 관리자만 접근할 수 있습니다.']);
            }
        }
        else {
            return redirect()->back()->withErrors(['permission' => '아이디와 비밀번호를 다시 확인해 주세요.']);
        }
    }

    public function memberView (Request $request) {
        $errors = [];
        if ($request->has('type') && $request->has('content')) {
            $type = $request['type'];
            $content = $request['content'];

            if ($content != "") {
                $contentLen = strlen($content);

                if ($contentLen == 1) {
                    $errors['search'] = '검색어는 두자 입력해주세요.';
                }
                else if ($contentLen > 1) {
                    if ($type == "username" || $type == "univName") {
                        $users = User::where($type, 'like', '%'.$content.'%')->paginate(20);

                        return view('admin.member.member', ['users' => $users]);
                    }
                    else if ($type == "") {
                        $errors['search'] = '검색 옵션을 선택해주세요.';
                    }
                    else {
                        $errors['search'] = '올바른 검색 옵션을 선택해주세요.';
                    }
                }
            }
        }

        $users = User::paginate(20);

        return view('admin.member.member', ['users' => $users])->withErrors($errors);
    }

    public function memberNewView (Request $request) {
        return view('admin.member.member_new');
    }

    public function memberNew (Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'univName' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['required' => '모든 내용을 입력해주세요.']);
        }

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'univName' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return redirect()->back()->withErrors($errors);
        }

        User::create([
            'username' => $request['username'],
            'univName' => $request['univName'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('admin.memberView')->with('status', 'member added');
    }

    public function memberEditView (Request $request, $userId) {
        $validator = Validator::make(['userId' => $userId], [
            'userId' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.memberView');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('admin.memberView');
        }

        return view('admin.member.member_edit', ['user' => $user]);
    }

    public function memberEdit (Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'id' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return redirect()->back();

            $errors = $validator->errors();
            if ($errors->has('password')) {

            }
            else if ($errors->has('password_confirmation')) {

            }
            else if ($errors->has('id')) {

            }
        }

        $user = User::find($request['id']);
        if (!$user) {
            return redirect()->route('admin.memberView');
        }

        $user->update(['password'=> Hash::make($request['password'])]);

        return redirect()->route('admin.memberView');
    }

    public function memberDelete (Request $request) {
        $deleteId = $request['deleteId'];
        $user = User::find($deleteId);
        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'invalid user id']);
        }

        $user->delete();

        return redirect()->route('admin.memberView');
    }

    public function basicView (Request $request) {
        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $basic = Basic::where('year', $year)->where('month', $month)->first();

        return view('admin.basic.basic', ['year' => $year, 'month' => $month, 'basic' => $basic]);
    }

    public function basic (Request $request) {
        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $validator = Validator::make($request->all(), [
            'image1' => ['required'],
            'image2' => ['required'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('image1') && $errors->has('image2')) {
                return redirect()->back()->withInput($request->input())->withErrors(['images' => '이미지를 모두 입력하세요.']);
            }
            else if ($errors->has('image1') || $errors->has('image2')) {
                $basic = Basic::where('year', $request['year'])->where('month', $request['month'])->first();

                if (!$basic) {
                    return redirect()->back()->withInput($request->input())->withErrors(['images' => '이미지를 모두 입력하세요.']);
                }
            }
        }

        $validator = Validator::make($request->all(), [
            'image1' => ['image'],
            'image2' => ['image'],
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('image1') || $errors->has('image2')) {
                return redirect()->back()->withErrors(['images' => '파일 형식은 이미지만 입력 가능합니다.']);
            }

            return redirect()->back()->withErrors($errors);
        }

        if ($request->hasFile('image1') && $request->hasFile('image2')) {
            $basic = Basic::where('year', $request['year'])->where('month', $request['month'])->first();

            $filePathName1 = uniqid();
            $request->file('image1')->storeAs('', $filePathName1, 'img');
            $filePathName2 = uniqid();
            $request->file('image2')->storeAs('', $filePathName2, 'img');

            if (!$basic) {
                Basic::create([
                    'year' => $request['year'],
                    'month' => $request['month'],
                    'firstImageName' => $request['image1']->getClientOriginalName(),
                    'firstImagePathName' => $filePathName1,
                    'secondImageName' => $request['image2']->getClientOriginalName(),
                    'secondImagePathName' => $filePathName2,
                ]);
            }
            else {
                $basic->firstImageName = $request['image1']->getClientOriginalName();
                $basic->firstImagePathName = $filePathName1;
                $basic->secondImageName = $request['image2']->getClientOriginalName();
                $basic->secondImagePathName = $filePathName2;

                $basic->save();
            }

            return redirect()->route('admin.basicView', ['year' => $year, 'month' => $month]);
        }
        else if ($request->hasFile('image1') || $request->hasFile('image2')) {
            $basic = Basic::where('year', $request['year'])->where('month', $request['month'])->first();
            if (!$basic) {
                return redirect()->back();
            }

            if ($request->hasFile('image1')) {
                Storage::disk('img')->delete($basic->firstImagePathName);

                $filePathName1 = uniqid();
                $request->file('image1')->storeAs('', $filePathName1, 'img');

                $basic->firstImageName = $request['image1']->getClientOriginalName();
                $basic->firstImagePathName = $filePathName1;

                $basic->save();

                return redirect()->route('admin.basicView', ['year' => $year, 'month' => $month]);
            }
            else {
                Storage::disk('img')->delete($basic->secondImagePathName);

                $filePathName2 = uniqid();
                $request->file('image2')->storeAs('', $filePathName2, 'img');

                $basic->secondImageName = $request['image2']->getClientOriginalName();
                $basic->secondImagePathName = $filePathName2;

                $basic->save();

                return redirect()->route('admin.basicView', ['year' => $year, 'month' => $month]);
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function basicImageApi (Request $request) {
        $basic = Basic::where('year', $request['year'])->where('month', $request['month'])->first();
        if (!$basic) {
            return ['image1' => '', 'image2' => ''];
        }
        else {
            return ['image1' => asset('storage/img/'.$basic->firstImagePathName), 'image2' => asset('storage/img/'.$basic->secondImagePathName)];
        }
    }

    public function total1View (Request $request) {
        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();

        return view('admin.total.total1', ['year' => $year, 'month' => $month, 'firstTotal' => $firstTotal]);
    }

    public function total1Excel (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

//        $im = new FirstTotalImport($request['year'],$request['month']);
        
        if(!is_null($request->excel)){
            Excel::import(new FirstTotalImport($request['year'],$request['month']), $request->file('excel'));
        }else{
            return redirect()->back()->withErrors(['error' => '엑셀파일을 업로드해 주세요.']);
        }

//        $sss = (new FirstTotalImport($request['year'],$request['month']))->Excel::import($request->file('excel'),null, \Maatwebsite\Excel\Excel::XLSX);

//        return dd($im);

        return redirect()->route('admin.total1View', ['year' => $request['year'], 'month' => $request['month']]);
    }

    public function total2View (Request $request) {
        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $secondTotals = SecondTotal::where('year', $year)->where('month', $month)->get();

        return view('admin.total.total2', ['year' => $year, 'month' => $month, 'secondTotals' => $secondTotals]);
    }

    public function total2Excel (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

        if(!is_null($request->excel)){
            Excel::import(new SecondTotalImport($request['year'],$request['month']), $request->file('excel'));
        }else{
            return redirect()->back()->withErrors(['error' => '엑셀파일을 업로드해 주세요.']);
        }

        return redirect()->route('admin.total2View', ['year' => $request['year'], 'month' => $request['month']]);
    }

    public function total3View (Request $request) {
        $univ = User::select('id', 'univName')->where('admin', '0')->get();

        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $thirdTotals = null;
        $userId = null;
        if ($request->has('userId')) {
            $userId = $request['userId'];
            $thirdTotals = ThirdTotal::where('userId', $userId)->where('year', $year)->where('month', $month)->get();
        }

        return view('admin.total.total3', ['univ' => $univ, 'userId' => $userId, 'thirdTotals' => $thirdTotals, 'year' => $year, 'month' => $month]);
    }

    public function total3Excel (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

        if(!is_null($request->excel)){
            $test = Excel::import(new ThirdTotalImport($request['year'],$request['month'],$request['userId']), $request->file('excel'));
        }else{
            return redirect()->back()->withErrors(['error' => '엑셀파일을 업로드해 주세요.']);
        }

        return redirect()->route('admin.total3View', ['year' => $request['year'], 'month' => $request['month'], 'userId' => $request['userId']]);
    }

    public function total4View (Request $request) {
        $univ = User::select('id', 'univName')->where('admin', '0')->get();

        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $fourthTotal = null;
        $userId = null;
        if ($request->has('userId')) {
            $userId = $request['userId'];
            $fourthTotal = FourthTotal::where('userId', $userId)->where('year', $year)->where('month', $month)->get();
        }

        return view('admin.total.total4', ['univ' => $univ, 'userId' => $userId, 'fourthTotal' => $fourthTotal, 'year' => $year, 'month' => $month]);
    }

    public function total4Excel (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }
        if(!is_null($request->excel)){
            Excel::import(new FourthTotalImport($request['year'],$request['month'],$request['userId']), $request->file('excel'));
        }else{
            return redirect()->back()->withErrors(['error' => '엑셀파일을 업로드해 주세요.']);
        }

        return redirect()->route('admin.total4View', ['year' => $request['year'], 'month' => $request['month'], 'userId' => $request['userId']]);
    }

    public function total5View (Request $request) {
        $univ = User::select('id', 'univName')->where('admin', '0')->get();

        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $shortAnswers = null;
        $userId = null;
        if ($request->has('userId')) {
            $userId = $request['userId'];
            $shortAnswers = ShortAnswer::where('userId', $userId)->where('year', $year)->where('month', $month)->get();
        }

        return view('admin.total.total5', ['univ' => $univ, 'userId' => $userId, 'shortAnswers' => $shortAnswers, 'year' => $year, 'month' => $month]);
    }

    public function total5Delete (Request $request, $shortAnswerId) {
        $shortAnswer = ShortAnswer::find($shortAnswerId);
        if (!$shortAnswer) {
            return false;
        }

        $shortAnswer->delete();

        return true;
    }

    public function total5WriteView (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
            'shortAnswerId' => ['integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

        $shortAnswer = null;
        if ($request->has('shortAnswerId')) {
            $shortAnswer = ShortAnswer::find($request['shortAnswerId']);
            if (!$shortAnswer) {
                return redirect()->back();
            }
        }

        $tt = ThirdTotal::select('id', 'lectureName')->where('year', $request['year'])->where('month', $request['month'])->where('userId', $request['userId'])->get();
        if ($tt->count() <= 0) {
            return redirect()->back()->withErrors(['error' => '과목이 없습니다. 추가 하시겠습니까?']);
        }

        return view('admin.total.total5_write', ['userId' => $request['userId'], 'year' => $request['year'], 'month' => $request['month'], 'shortAnswer' => $shortAnswer, 'lectures' => $tt]);
    }

    public function total5Write (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
//            'lectureName' => ['required', 'string'],
            'positive' => ['required', 'string'],
            'negative' => ['required', 'string'],
            'wordCloud' => ['required', 'image'],
            'lectureId' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors(['required' => '모든 내용을 입력해주세요.']);
        }

        $user = User::find($request['userId']);
        if (!$user) {
            return redirect()->route('admin.total5View');
        }

        $lecture = ThirdTotal::find($request['lectureId']);
        if (!$lecture) {
            return redirect()->back()->withInput($request->input());
        }
        if ($lecture->userId != $request['userId']) {
            return redirect()->back()->withInput($request->input());
        }

        $filePathName = uniqid();
        $request->file('wordCloud')->storeAs('shortAnswer', $filePathName, 'local');

        ShortAnswer::create([
            'userId' => $lecture->userId,
            'year' => $request['year'],
            'month' => $request['month'],
            'lectureId' => $lecture->id,
//            'lectureName' => $request['lectureName'],
            'positive' => $request['positive'],
            'negative' => $request['negative'],
            'wordCloudName' => $request['wordCloud']->getClientOriginalName(),
            'wordCloudPathName' => $filePathName,
        ]);

        return redirect()->route('admin.total5View', ['year' => $request['year'], 'month' => $request['month'], 'userId' => $request['userId']]);
    }

    public function total5EditView (Request $request, $shortAnswerId) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
            'shortAnswerId' => ['integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

        $shortAnswer = null;
        $shortAnswer = ShortAnswer::find($request['shortAnswerId']);
        if (!$shortAnswer) {
            return redirect()->back();
        }

        $tt = ThirdTotal::select('id', 'lectureName')->where('year', $request['year'])->where('month', $request['month'])->where('userId', $request['userId'])->get();

        return view('admin.total.total5_edit', ['userId' => $request['userId'], 'year' => $request['year'], 'month' => $request['month'], 'shortAnswer' => $shortAnswer, 'lectures' => $tt]);
    }

    public function total5Edit (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
            'lectureId' => ['required', 'integer', 'min:1'],
//            'lectureName' => ['required', 'string'],
            'positive' => ['required', 'string'],
            'negative' => ['required', 'string'],
            'wordCloud' => ['image'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors(['required' => '모든 내용을 입력해주세요.']);
        }

        $shortAnswer = ShortAnswer::find($request['shortAnswerId']);

        if (!$shortAnswer) {
            return redirect()->route('admin.total5View');
        }

        $filePathName = null;
        if ($request->hasFile('wordCloud')) {
            $filePathName = uniqid();
            $request->file('wordCloud')->storeAs('shortAnswer', $filePathName, 'local');

            $shortAnswer->wordCloudName = $request['wordCloud']->getClientOriginalName();
            $shortAnswer->wordCloudPathName = $filePathName;
        }

        function mynl2br($text) {
            return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
        }

        $shortAnswer->lectureId = $request['lectureId'];
        $shortAnswer->positive = $request['positive'];
        $shortAnswer->negative = $request['negative'];

        $shortAnswer->positive = htmlentities($shortAnswer->positive);
        $shortAnswer->positive = mynl2br($shortAnswer->positive);
        $shortAnswer->negative = htmlentities($shortAnswer->negative);
        $shortAnswer->negative = mynl2br($shortAnswer->negative);

        $shortAnswer->save();

        return redirect()->route('admin.total5View', ['year' => $request['year'], 'month' => $request['month'], 'userId' => $request['userId']]);
    }

    public function per1View (Request $request) {
        $univ = User::select('id', 'univName')->where('admin', '0')->get();

        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $firstPer = null;
        $userId = null;
        if ($request->has('userId')) {
            $userId = $request['userId'];
            $firstPer = FirstPer::where('userId', $userId)->where('year', $year)->where('month', $month)->first();
        }

        return view('admin.per.per1', ['univ' => $univ, 'userId' => $userId, 'year' => $year, 'month' => $month, 'firstPer' => $firstPer]);
    }

    public function per1Write (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
            'content' => ['array'],
            'content.*' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput();
        }

        $user = User::find($request['userId']);
        if (!$user) {
            return redirect()->route('admin.per1View');
        }

        $firstPer = FirstPer::where('userId', $request['userId'])->where('year', $request['year'])->where('month', $request['month'])->first();
        if (!$firstPer) {
            FirstPer::create([
                'userId' => $request['userId'],
                'year' => $request['year'],
                'month' => $request['month'],
                'content' => $request['content'],
            ]);
        }
        else {
            $firstPer->content = $request['content'];
            $firstPer->save();
        }


        return redirect()->route('admin.per1View', ['userId' => $request['userId'], 'year' => $request['year'], 'month' => $request['month']]);
    }

    public function per2View (Request $request) {
//        $univ = User::select('id', 'univName')->where('admin', '0')->get();

        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $secondPers = null;
//        $userId = null;
//        if ($request->has('userId')) {
//            $userId = $request['userId'];
//            $secondPers = SecondPer::where('userId', $userId)->where('year', $year)->where('month', $month)->get();
//        }
        $secondPers = SecondPer::where('year', $year)->where('month', $month)->get();

        return view('admin.per.per2', ['year' => $year, 'month' => $month, 'secondPers' => $secondPers]);
    }

    public function per2Fetch (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        if ($validator->fails()) {
            return ['fail' => true, 'data' => [$request['year']]];
        }

        $year = $request['year'];
        $month = $request['month'];

        switch ($month) {
            case 1:
                $year -= 1;
                $month = 4;
                break;
            case 2:
                $month = 3;
                break;
            case 3:
                $month = 1;
                break;
            case 4:
                $month = 2;
                break;
        }

        $secondPers = SecondPer::where('year', $year)->where('month', $month)->get();

        return ['fail' => false, 'data' => $secondPers];
    }

    public function per2Write (Request $request) {
//        return dd($request->all());
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
//            'userId' => ['required', 'integer', 'min:1'],
            'data' => ['array'],
            'data.*' => ['required', 'json'],
        ]);

        if ($validator->fails()) {
            return false;
        }

        $secondPers = SecondPer::where('year', $request['year'])->where('month', $request['month'])->get();

        if ($request->has('data')) {
            foreach ($request['data'] as $data) {
                $_data = (array)json_decode($data);

                $validator = Validator::make($_data, [
                    'id' => ['required', 'integer', 'min:0'],
                    'lectureName' => ['required', 'string'],
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();

                    if ($errors->has('id')) {
                        return ['fail' => true, 'data' => ['id' => 'id error']];
                    }
                    else if ($errors->has('lectureName')) {
                        return ['fail' => true, 'data' => ['lectureName' => '과정명을 입력해주세요.']];
                    }
                }

                if (!((int)$_data['id'])) {
                    $lectureName = $_data['lectureName'];
                    $subset = $secondPers->skipUntil(function ($item) use($lectureName) {
                        return $item->lectureName == $lectureName;
                    });

                    if ($subset->count() > 0) {
                        return ['fail' => true, 'data' => ['lectureName' => '중복된 과정명이 있습니다.']];
                    }
                }
            }
        }

        if ($request->has('data')) {
            foreach ($request['data'] as $data) {
                $_data = (array)json_decode($data);

                $validator = Validator::make($_data, [
                    'id' => ['required', 'integer', 'min:0'],
                    'lectureName' => ['required', 'string'],
                    'fusion' => ['required', 'boolean'],
                    'creative' => ['required', 'boolean'],
                    'professionalism' => ['boolean'],
                    'informationCommunication' => ['required', 'boolean'],
                    'problemPrediction' => ['required', 'boolean'],
                    'utilizationNewTechnology' => ['required', 'boolean'],
                    'expression' => ['required', 'boolean'],
                    'collaboration' => ['required', 'boolean'],
                    'discrimination' => ['required', 'boolean'],
                    'adaptation' => ['required', 'boolean'],
                    'solution' => ['required', 'boolean'],
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();

                    if ($errors->has('id')) {
                        return ['fail' => true, 'data' => ['id' => 'id error']];
                    }
                    else if ($errors->has('lectureName')) {
                        return ['fail' => true, 'data' => ['lectureName' => '과정명을 입력해주세요.']];
                    }
                    else if ($errors->has('fusion')) {
                        return ['fail' => true, 'data' => ['fusion' => 'fusion error']];
                    }
                    else if ($errors->has('creative')) {
                        return ['fail' => true, 'data' => ['creative' => 'creative error']];
                    }
                    else if ($errors->has('professionalism')) {
                        return ['fail' => true, 'data' => ['professionalism' => 'professionalism error']];
                    }
                    else if ($errors->has('informationCommunication')) {
                        return ['fail' => true, 'data' => ['informationCommunication' => 'informationCommunication error']];
                    }
                    else if ($errors->has('problemPrediction')) {
                        return ['fail' => true, 'data' => ['problemPrediction' => 'problemPrediction error']];
                    }
                    else if ($errors->has('utilizationNewTechnology')) {
                        return ['fail' => true, 'data' => ['utilizationNewTechnology' => 'utilizationNewTechnology error']];
                    }
                    else if ($errors->has('expression')) {
                        return ['fail' => true, 'data' => ['expression' => 'expression error']];
                    }
                    else if ($errors->has('collaboration')) {
                        return ['fail' => true, 'data' => ['collaboration' => 'collaboration error']];
                    }
                    else if ($errors->has('discrimination')) {
                        return ['fail' => true, 'data' => ['discrimination' => 'discrimination error']];
                    }
                    else if ($errors->has('adaptation')) {
                        return ['fail' => true, 'data' => ['adaptation' => 'adaptation error']];
                    }
                    else if ($errors->has('solution')) {
                        return ['fail' => true, 'data' => ['solution' => 'solution error']];
                    }
                }

                if (!((int)$_data['id'])) {
                    SecondPer::create([
                        'year' => $request['year'],
                        'month' => $request['month'],
                        'lectureName' => $_data['lectureName'],
                        'fusion' => $_data['fusion'],
                        'creative' => $_data['creative'],
                        'professionalism' => $_data['professionalism'],
                        'informationCommunication' => $_data['informationCommunication'],
                        'problemPrediction' => $_data['problemPrediction'],
                        'utilizationNewTechnology' => $_data['utilizationNewTechnology'],
                        'expression' => $_data['expression'],
                        'collaboration' => $_data['collaboration'],
                        'discrimination' => $_data['discrimination'],
                        'adaptation' => $_data['adaptation'],
                        'solution' => $_data['solution'],
                    ]);
                } else {
                    $secondPer = SecondPer::find($_data['id']);
                    if (!$secondPer || $secondPer->year != $request['year'] || $secondPer->month != $request['month']) {
                        SecondPer::create([
                            'year' => $request['year'],
                            'month' => $request['month'],
                            'lectureName' => $_data['lectureName'],
                            'fusion' => $_data['fusion'],
                            'creative' => $_data['creative'],
                            'professionalism' => $_data['professionalism'],
                            'informationCommunication' => $_data['informationCommunication'],
                            'problemPrediction' => $_data['problemPrediction'],
                            'utilizationNewTechnology' => $_data['utilizationNewTechnology'],
                            'expression' => $_data['expression'],
                            'collaboration' => $_data['collaboration'],
                            'discrimination' => $_data['discrimination'],
                            'adaptation' => $_data['adaptation'],
                            'solution' => $_data['solution'],
                        ]);
                    } else {
                        $secondPer->lectureName = $_data['lectureName'];
                        $secondPer->fusion = $_data['fusion'];
                        $secondPer->creative = $_data['creative'];
                        $secondPer->professionalism = $_data['professionalism'];
                        $secondPer->informationCommunication = $_data['informationCommunication'];
                        $secondPer->problemPrediction = $_data['problemPrediction'];
                        $secondPer->utilizationNewTechnology = $_data['utilizationNewTechnology'];
                        $secondPer->expression = $_data['expression'];
                        $secondPer->collaboration = $_data['collaboration'];
                        $secondPer->discrimination = $_data['discrimination'];
                        $secondPer->adaptation = $_data['adaptation'];
                        $secondPer->solution = $_data['solution'];

                        $secondPer->save();
                    }
                }
            }
        }

        return ['fail' => false, 'data' => [route('admin.per2View', ['year' => $request['year'], 'month' => $request['month']])]];
    }

    public function per2Delete (Request $request) {
        $secondPer = SecondPer::find($request['id']);
        if (!$secondPer || $secondPer->userId != $request['userId'] || $secondPer->year != $request['year'] || $secondPer->month != $request['month']) {
            return false;
        }

        $secondPer->delete();

        return $request['id'];
    }

    public function per3View (Request $request) {
        $univ = User::select('id', 'univName')->where('admin', '0')->get();

        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $thirdPer = null;
        $userId = null;
        if ($request->has('userId')) {
            $userId = $request['userId'];
            $thirdPer = ThirdPer::where('userId', $userId)->where('year', $year)->where('month', $month)->first();
        }

        return view('admin.per.per3', ['univ' => $univ, 'userId' => $userId, 'year' => $year, 'month' => $month, 'thirdPer' => $thirdPer]);
    }

    public function per3Write (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
            'content' => ['array'],
            'content.*' => ['required', 'string'],
            'image' => ['image'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput();
        }

        $user = User::find($request['userId']);
        if (!$user) {
            return redirect()->route('admin.per3View');
        }

        $thirdPer = ThirdPer::where('year', $request['year'])->where('month', $request['month'])->where('userId', $request['userId'])->first();
        if (!$thirdPer) {
            if ($request->hasFile('image')) {
                $filePathName = uniqid();
                $request->file('image')->storeAs('per3', $filePathName, 'local');

                ThirdPer::create([
                    'year' => $request['year'],
                    'month' => $request['month'],
                    'userId' => $request['userId'],
                    'imageName' => $request['image']->getClientOriginalName(),
                    'imagePathName' => $filePathName,
                    'content' => $request['content'],
                ]);
            }
            else {
                ThirdPer::create([
                    'year' => $request['year'],
                    'month' => $request['month'],
                    'userId' => $request['userId'],
                    'content' => $request['content'],
                ]);
            }
        }
        else {
            if ($request->hasFile('image')) {
                $filePathName = uniqid();
                $request->file('image')->storeAs('per3', $filePathName, 'local');

                Storage::disk('local')->delete('per3/'.$thirdPer->imagePathName);

                $thirdPer->content = $request['content'];
                $thirdPer->imageName = $request['image']->getClientOriginalName();
                $thirdPer->imagePathName = $filePathName;

                $thirdPer->save();
            }
            else {
                $thirdPer->content = $request['content'];

                $thirdPer->save();
            }
        }
        return redirect()->route('admin.per3View', ['userId' => $request['userId'], 'year' => $request['year'], 'month' => $request['month']]);
    }

    public function per4View (Request $request) {
        $univ = User::select('id', 'univName')->where('admin', '0')->get();

        $year = date("Y");
        $month = (int)date('m') > 6 ? 2 : 1;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $fourthPer = null;
        $userId = null;
        if ($request->has('userId')) {
            $userId = $request['userId'];
            $fourthPer = FourthPer::where('userId', $userId)->where('year', $year)->where('month', $month)->first();
        }

        return view('admin.per.per4', ['univ' => $univ, 'userId' => $userId, 'year' => $year, 'month' => $month, 'fourthPer' => $fourthPer]);
    }

    public function per4Write (Request $request) {
        $validator = Validator::make($request->all(), [
            'year' => ['required', 'integer', 'min:2010', 'max:2050'],
            'month' => ['required', 'integer', 'min:1', 'max:4'],
            'userId' => ['required', 'integer', 'min:1'],
            'image' => ['image'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput();
        }

        $user = User::find($request['userId']);
        if (!$user) {
            return redirect()->route('admin.per4View');
        }

        if ($request->hasFile('image')) {
            $fourthPer = FourthPer::where('year', $request['year'])->where('month', $request['month'])->where('userId', $request['userId'])->first();
            $filePathName = uniqid();
            $request->file('image')->storeAs('per4', $filePathName, 'local');

            if (!$fourthPer) {
                FourthPer::create([
                    'userId' => $user->id,
                    'year' => $request['year'],
                    'month' => $request['month'],
                    'imageName' => $request['image']->getClientOriginalName(),
                    'imagePathName' => $filePathName,
                ]);
            }
            else {
                Storage::disk('local')->delete('per4/'.$fourthPer->imagePathName);

                $fourthPer->imageName = $request['image']->getClientOriginalName();
                $fourthPer->imagePathName = $filePathName;

                $fourthPer->save();
            }

            return redirect()->route('admin.per4View', ['userId' => $request['userId'], 'year' => $request['year'], 'month' => $request['month']]);
        }
        else {
            return redirect()->back()->withErrors(['error' => 'image']);
        }
    }
}
