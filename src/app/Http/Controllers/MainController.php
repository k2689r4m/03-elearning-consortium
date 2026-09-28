<?php

namespace App\Http\Controllers;

use App\Models\Basic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    //

    public function home (Request $request) {
//        $basic = Basic::where('year', date('Y'))->where('month', (int)date('m') > 6 ? 2 : 1)->first();
//
//        if (!$basic) {
//            return view('my_page_view');
//        }
//        else {
//            return view('my_page_view', ['image1' => $basic->firstImagePathName, 'image2' => $basic->secondImagePathName]);
//        }

        //기존 login Redirect만 했는데 리다이렉트 오류 떠서 분기 시킴
        if(Auth::user()){
            return redirect()->route('basicView');
        }else{
            return redirect()->route('login');
        }


    }
}
