<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortAnswer;
use App\Models\ThirdPer;
use App\Models\FourthPer;
use App\Models\Basic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SecondController extends Controller
{
    //

    public function sat0View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];
        }

        $shortAnswers = ShortAnswer::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        return view('dash.satisfaction.sat0', ['year' => $year, 'month' => $month, 'shortAnswers' => $shortAnswers]);
    }

    public function sat0Image (Request $request, $imagePathName) {
        $shortAnswer = ShortAnswer::where('wordCloudPathName', $imagePathName)->first();
        if (!$shortAnswer) {
            return null;
        }
        else if ($shortAnswer->userId != Auth::id() && Auth::user()->admin != 1) {
            return null;
        }

        return Storage::disk('local')->download('shortAnswer/'.$imagePathName);
    }

    public function result5Image (Request $request, $imagePathName) {
        $thirdPer = ThirdPer::where('imagePathName', $imagePathName)->first();
        if (!$thirdPer) {
            return null;
        }
        else if ($thirdPer->userId != Auth::id() && Auth::user()->admin != 1) {
            return null;
        }

        return Storage::disk('local')->download('per3/'.$imagePathName);
    }

    public function result6Image (Request $request, $imagePathName) {
        $fourthPer = FourthPer::where('imagePathName', $imagePathName)->first();
        if (!$fourthPer) {
            return null;
        }
        else if ($fourthPer->userId != Auth::id() && Auth::user()->admin != 1) {
            return null;
        }

        return Storage::disk('local')->download('per4/'.$imagePathName);
    }

    public function result5View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];
        }

        $thirdPer = ThirdPer::where('year', $year)->where('month', $month)->where('userId', Auth::id())->first();

        return view('dash.result.result5', ['year' => $year, 'month' => $month, 'thirdPer' => $thirdPer]);
    }

    public function result6View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];
        }

        $fourthPer = FourthPer::where('year', $year)->where('month', $month)->where('userId', Auth::id())->first();

        return view('dash.result.result6', ['year' => $year, 'month' => $month, 'fourthPer' => $fourthPer]);
    }
}
