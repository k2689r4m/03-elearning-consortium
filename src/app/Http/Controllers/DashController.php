<?php

namespace App\Http\Controllers;

use App\Models\Basic;
use App\Models\FirstTotal;
use App\Models\FourthTotal;
use App\Models\SecondTotal;
use App\Models\ThirdTotal;
use App\Models\FirstPer;
use App\Models\SecondPer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function basicView () {
        $basic = Basic::where('year', date('Y'))->where('month', (int)date('m') > 6 ? 2 : 1)->first();

        if (!$basic) {
            return view('my_page_view');
        }
        else {
            return view('my_page_view', ['image1' => $basic->firstImagePathName, 'image2' => $basic->secondImagePathName]);
        }
    }

    public function dashView(Request $request)
    {
//        $year = date("Y");
//        $month = (int)date('m') > 6 ? 2 : 1;
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];
            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $lectureCount = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->count();
        $totalLectureCount = SecondTotal::where('year', $year)->where('month', $month)->count();
        //0
        $data[] = [
            'lectureCount' => $lectureCount,
            'totalLectureCount' => $totalLectureCount,
        ];
        //1
        if (!$totalLectureCount) {
            $data[] = [
                ['name' => 'true', 'value' => 0],
                ['name' => 'false', 'value' => 100],
            ];
        }
        else {
            $lectureCountRate = floor(($lectureCount / $totalLectureCount) * 100 * 100) / 100;
            $data[] = [
                ['name' => 'true', 'value' => $lectureCountRate],
                ['name' => 'false', 'value' => 100 - $lectureCountRate],
            ];
        }

        $memberCount = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->sum('memberCount');
        $totalMemberCount = FirstTotal::where('year', $year)->where('month', $month)->sum('memberCount');
        //2
        $data[] = ['memberCount' => $memberCount, 'totalMemberCount' => number_format($totalMemberCount)];
        //3
        if (!$totalMemberCount) {
            $data[] = [
                ['name' => 'true', 'value' => 0],
                ['name' => 'false', 'value' => 100],
            ];
        }
        else {
            $memberCountRate = floor(($memberCount / $totalMemberCount) * 100 * 100) / 100;
            $data[] = [
                ['name' => 'true', 'value' => $memberCountRate],
                ['name' => 'false', 'value' => 100 - $memberCountRate],
            ];
        }

        //4
        $maxMemeberCount = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->max('memberCount');
        if (is_null($maxMemeberCount)) {
            $data[] = ['name' => '', 'value' => 0];
        }
        else {
            $maxMemeberCount = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->where('memberCount', $maxMemeberCount)->first();
            $data[] = ['name' => $maxMemeberCount->lectureName, 'value' => $maxMemeberCount->memberCount];
        }

        $attendanceRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('attendanceRate');
        $learningProgressRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('learningProgressRate');
        $finalCompletionRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('finalCompletionRate');
        $overSixtyRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('FinalScoreSixtyPointApplicantRate');;

        //5
        $data[] = [
            [ 'name' => '출석률', 'value' => is_null($attendanceRate) ? 0 : $attendanceRate / 100 / 100 ],
            [ 'name' => '학습진도율', 'value' => is_null($learningProgressRate) ? 0 : $learningProgressRate / 100 / 100 ],
            [ 'name' => '최종수료율', 'value' => is_null($finalCompletionRate) ? 0 : $finalCompletionRate / 100 / 100 ],
            [ 'name' => '최종성적', 'name2' => '60점 이상 취득률', 'value' => is_null($overSixtyRate) ? 0 : $overSixtyRate / 100 / 100 ],
        ];

        $testApplicationRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('testApplicationRate');
        $taskPerformanceRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('taskPerformanceRate');
        $discussionParticipationRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('discussionParticipationRate');
        $quizProgressRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('quizProgressRate');
        $learningParticipationRate = ThirdTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('learningParticipationRate');

        $totalTestApplicationRate = null;
        $totalTaskPerformanceRate = null;
        $totalDiscussionParticipationRate = null;
        $totalQuizProgressRate = null;
        $totalLearningParticipationRate = null;
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        if ($firstTotal) {
            $totalTestApplicationRate = $firstTotal->testApplicationRate;
            $totalTaskPerformanceRate = $firstTotal->taskPerformanceRate;
            $totalDiscussionParticipationRate = $firstTotal->discussionParticipationRate;
            $totalQuizProgressRate = $firstTotal->quizProgressRate;
            $totalLearningParticipationRate = $firstTotal->learningParticipationRate;
        }


        //6
        $data[] = [
            [ 'name' => '시험응시율', 'value' => is_null($totalTestApplicationRate) ? 0 : floor($totalTestApplicationRate), 'value2' => is_null($testApplicationRate) ? 0 : floor($testApplicationRate / 100)],
            [ 'name' => '과제수행률', 'value' => is_null($totalTaskPerformanceRate) ? 0 : floor($totalTaskPerformanceRate), 'value2' => is_null($taskPerformanceRate) ? 0 : floor($taskPerformanceRate / 100)],
            [ 'name' => '토론참여율', 'value' => is_null($totalDiscussionParticipationRate) ? 0 : floor($totalDiscussionParticipationRate), 'value2' => is_null($discussionParticipationRate) ? 0 : floor($discussionParticipationRate / 100)],
            [ 'name' => '퀴즈수행률', 'value' => is_null($totalQuizProgressRate) ? 0 : floor($totalQuizProgressRate), 'value2' => is_null($quizProgressRate) ? 0 : floor($quizProgressRate / 100)],
            [ 'name' => '학습참여도', 'value' => is_null($totalLearningParticipationRate) ? 0 : floor($totalLearningParticipationRate), 'value2' => is_null($learningParticipationRate) ? 0 : floor($learningParticipationRate / 100)],
        ];

        $overallSatisfactionRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('overallSatisfactionRate');
        $selfEvaluationRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('selfEvaluationRate');
        $lectureSupportRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('lectureSupportRate');
        $learningContentEvaluationRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('learningContentEvaluationRate');
        $evaluationRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('evaluationRate');
        $operatorEvaluationRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('operatorEvaluationRate');
        $systemEvaluationRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('systemEvaluationRate');
        $totalSatisfactionRate = FourthTotal::where('userId', Auth::id())->where('year', $year)->where('month', $month)->avg('totalSatisfactionRate');

        $totalOverallSatisfactionRate = null;
        $totalSelfEvaluationRate = null;
        $totalLectureSupportRate = null;
        $totalLearningContentEvaluationRate = null;
        $totalEvaluationRate = null;
        $totalOperatorEvaluationRate = null;
        $totalSystemEvaluationRate = null;
        $totalTotalSatisfactionRate = null;
        if ($firstTotal) {
            $totalOverallSatisfactionRate = $firstTotal->satisfactionRate;
            $totalSelfEvaluationRate = $firstTotal->selfEvaluationSatisfactionRate;
            $totalLectureSupportRate = $firstTotal->lectureSupportSatisfactionRate;
            $totalLearningContentEvaluationRate = $firstTotal->overallSatisfactionRate;
            $totalEvaluationRate = $firstTotal->learningEvaluationSatisfactionRate;
            $totalOperatorEvaluationRate = $firstTotal->educationOperationSatisfactionRate;
            $totalSystemEvaluationRate = $firstTotal->overallSystemSatisfactionRate;
            $totalTotalSatisfactionRate = $firstTotal->totalSatisfactionRate;
        }

        //7
        $data[] = [
            [ 'name' => '종합만족도', 'value' => is_null($totalOverallSatisfactionRate) ? 0 : floor($totalOverallSatisfactionRate), 'value2' => is_null($overallSatisfactionRate) ? 0 : floor($overallSatisfactionRate / 100)],
            [ 'name' => '자기평가', 'value' => is_null($totalSelfEvaluationRate) ? 0 : floor($totalSelfEvaluationRate), 'value2' => is_null($selfEvaluationRate) ? 0 : floor($selfEvaluationRate / 100)],
            [ 'name' => '강의지원', 'value' => is_null($totalLectureSupportRate) ? 0 : floor($totalLectureSupportRate), 'value2' => is_null($lectureSupportRate) ? 0 : floor($lectureSupportRate / 100)],
            [ 'name' => '학습내용/', 'name2' => '교수자평가', 'value' => is_null($totalLearningContentEvaluationRate) ? 0 : floor($totalLearningContentEvaluationRate), 'value2' => is_null($learningContentEvaluationRate) ? 0 : floor($learningContentEvaluationRate / 100)],
            [ 'name' => '학습평가', 'value' => is_null($totalEvaluationRate) ? 0 : floor($totalEvaluationRate), 'value2' => is_null($evaluationRate) ? 0 : floor($evaluationRate / 100)],
            [ 'name' => '교육운영', 'value' => is_null($totalOperatorEvaluationRate) ? 0 : floor($totalOperatorEvaluationRate), 'value2' => is_null($operatorEvaluationRate) ? 0 : floor($operatorEvaluationRate / 100)],
            [ 'name' => '시스템', 'value' => is_null($totalSystemEvaluationRate) ? 0 : floor($totalSystemEvaluationRate), 'value2' => is_null($systemEvaluationRate) ? 0 : floor($systemEvaluationRate / 100)],
            [ 'name' => '전반적', 'name2' => '만족도', 'value' => is_null($totalTotalSatisfactionRate) ? 0 : floor($totalTotalSatisfactionRate), 'value2' => is_null($totalSatisfactionRate) ? 0 : floor($totalSatisfactionRate / 100)],
        ];



        return view('dash.dash', ['year' => $year, 'month' => $month, 'data' => $data]);
    }

    public function dash2View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $lectures = ThirdTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();
        $totalLectures = ThirdTotal::where('year', $year)->where('month', $month)->get()->groupBy('userId');

        $maxLecture = $totalLectures->sortByDesc(function($data) {
            return $data->count();
        });

        $userCount = $lectures->sum(function($data){
            return $data->memberCount;
        });

        if(!is_null($maxLecture->keys()->first())){
            $maxUserCount = $maxLecture[$maxLecture->keys()->first()]->sum(function($data){
                return $data->memberCount;
            });
        }

        if (is_null($maxLecture->keys()->first())) {
            //0
            $data[] = [
                [ 'name' => Auth::user()->univName, 'value' => $lectures->count() ],
                [ 'name' => Auth::user()->univName, 'name2' => '(최다)', 'value' => $lectures->count() ],
            ];
            //1
            $data[] = [
                [ 'name' => Auth::user()->univName, 'value' => $userCount ],
                [ 'name' => Auth::user()->univName, 'name2' => '(최다)', 'value' => $userCount ],
            ];
        }
        else {
            $maxUser = User::find($maxLecture->keys()->first());
            //0
            $data[] = [
                [ 'name' => Auth::user()->univName, 'value' => $lectures->count() ],
                [ 'name' => $maxUser->univName, 'name2' => '(최다)', 'value' => $maxLecture[$maxLecture->keys()->first()]->count() ],
            ];
            //1
            $data[] = [
                [ 'name' => Auth::user()->univName, 'value' => $userCount ],
                [ 'name' => $maxUser->univName, 'name2' => '(최다)', 'value' => $maxUserCount ],
            ];
        }

        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();

        //2
        $data[] = $firstTotal ? $firstTotal->lectureCount : 0;
        //3
        $data[] = $firstTotal ? number_format($firstTotal->memberCount) : 0;

        $maxLectureCount = $lectures->sortByDesc(function($data){
            return $data -> memberCount;
        });
        if(!is_null($maxLectureCount->keys()->first())){
            //4, 5
            $data[] = $maxLectureCount[$maxLectureCount->keys()->first()]->lectureName;
            $data[] = number_format($maxLectureCount[$maxLectureCount->keys()->first()]->memberCount);
        }else{
            //4, 5
            $data[] = '';
            $data[] = '';
        }

        //6
        $data[] = [
            [ 'name' => '수료율', 'value' => $firstTotal ? floor($firstTotal->completionRate) : 0, 'value2' => floor($lectures->avg('finalCompletionRate')) ],
            [ 'name' => '최종성적', 'name2' => '60점 이상 수료율', 'value' => $firstTotal ? floor($firstTotal->overSixtyRate) : 0, 'value2' => floor($lectures->avg('FinalScoreSixtyPointApplicantRate')) ],
            [ 'name' => '학습진도율', 'value' => $firstTotal ? floor($firstTotal->learningProgressRate) : 0, 'value2' => floor($lectures->avg('learningProgressRate')) ],
            [ 'name' => '출석률', 'value' => $firstTotal ? floor($firstTotal->attendanceRate) : 0, 'value2' => floor($lectures->avg('attendanceRate')) ],
            [ 'name' => '지각률', 'value' => $firstTotal ? floor($firstTotal->lateRate) : 0, 'value2' => floor($lectures->avg('lateRate')) ],
            [ 'name' => '결석률', 'value' => $firstTotal ? floor($firstTotal->absenceRate) : 0, 'value2' => floor($lectures->avg('absenceRate')) ],
        ];
        //7(수료)
        $data[] = $lectures->sum('graduatesCount');

        //8
        $data[] = ['overSixty' => 0, 'overSixtyRate' => 0];
        $minAbsenceRate = $lectures->sortBy('absenceRate');
        if(!is_null($minAbsenceRate->keys()->first())){
            //9
            $data[] = [
                'lectureName' => $minAbsenceRate[$minAbsenceRate->keys()->first()]->lectureName,
                'absenceRate' => $minAbsenceRate[$minAbsenceRate->keys()->first()]->absenceRate,
            ];
        }
        else{
            //9
            $data[] = [
                'lectureName' => '',
                'absenceRate' => '',
            ];
        }

        return view('dash.dash2', ['data' => $data, 'year' => $year, 'month' => $month, 'lectures' => $lectures]);
    }

    public function dash3View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $lectures = ThirdTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($lectures) ? floor($lectures->avg('testApplicationRate')) : 0 ],
            [ 'name' => '참여대평균', 'value' => !is_null($firstTotal) ? floor($firstTotal->testApplicationRate) : 0 ],
        ];

        $testTopLectures = $lectures->sortByDesc('testApplicationRate')->keys()->first();
        if(!is_null($testTopLectures)){
            //1 ,2
            $data[] = [
                [ 'name' => 'true', 'value' => $lectures[$testTopLectures]->testApplicationRate ],
                [ 'name' => 'false', 'value' => 100 - $lectures[$testTopLectures]->testApplicationRate ],
            ];
            $data[] = $lectures[$testTopLectures]->lectureName;
        }else{
            //1, 2
            $data[] = [
                [ 'name' => 'true', 'value' => 0 ],
                [ 'name' => 'flase', 'value' => 100 ],
            ];
            $data[] = '';
        }

        //3
        $data[] = [
          'totalStu' => $lectures->sum('memberCount'),
          'midTest' => $lectures->sum('midtermTestTakers'),
          'finalTest' => $lectures->sum('finalTestTakers'),
          'avg' => floor($lectures->avg('testApplicationRate') * 100) / 100,
        ];

        //4
        $stuTopLectures = $lectures->sortByDesc('memberCount')->keys()->first();
        if(!is_null($stuTopLectures)) {
            $data[] = [
                'lectureName' => $lectures[$stuTopLectures]->lectureName,
                'stuTotal' => $lectures[$stuTopLectures]->memberCount
            ];
        }else{
            $data[] = [
                'lectureName' => '',
                'stuTotal' => 0
            ];
        }

        //5
        if(!is_null($firstTotal) && !is_null($lectures)){
            $data[] = [
                [ 'name' => '과제수행률', 'value' => floor($firstTotal->taskPerformanceRate), 'value2' => floor($lectures->avg('taskPerformanceRate')) ],
                [ 'name' => '토론참여율', 'value' => floor($firstTotal->discussionParticipationRate), 'value2' => floor($lectures->avg('discussionParticipationRate')) ],
                [ 'name' => '퀴즈수행률', 'value' => floor($firstTotal->quizProgressRate), 'value2' => floor($lectures->avg('quizProgressRate')) ],
                [ 'name' => '학습참여도', 'value' => floor($firstTotal->learningParticipationRate), 'value2' => floor($lectures->avg('learningParticipationRate')) ],
            ];
        }
        else if (!is_null($firstTotal)) {
            $data[] = [
                [ 'name' => '과제수행률', 'value' => floor($firstTotal->taskPerformanceRate), 'value2' => 0 ],
                [ 'name' => '토론참여율', 'value' => floor($firstTotal->discussionParticipationRate), 'value2' => 0 ],
                [ 'name' => '퀴즈수행률', 'value' => floor($firstTotal->quizProgressRate), 'value2' => 0 ],
                [ 'name' => '학습참여도', 'value' => floor($firstTotal->learningParticipationRate), 'value2' => 0 ],
            ];
        }
        else if (!is_null($lectures)) {
            $data[] = [
                [ 'name' => '과제수행률', 'value' => 0, 'value2' => floor($lectures->avg('taskPerformanceRate')) ],
                [ 'name' => '토론참여율', 'value' => 0, 'value2' => floor($lectures->avg('discussionParticipationRate')) ],
                [ 'name' => '퀴즈수행률', 'value' => 0, 'value2' => floor($lectures->avg('quizProgressRate')) ],
                [ 'name' => '학습참여도', 'value' => 0, 'value2' => floor($lectures->avg('learningParticipationRate')) ],
            ];
        }
        else {
            $data[] = [
                [ 'name' => '과제수행률', 'value' => 0, 'value2' => 0 ],
                [ 'name' => '토론참여율', 'value' => 0, 'value2' => 0 ],
                [ 'name' => '퀴즈수행률', 'value' => 0, 'value2' => 0 ],
                [ 'name' => '학습참여도', 'value' => 0, 'value2' => 0 ],
            ];
        }

        return view('dash.dash3', ['data' => $data, 'year' => $year, 'month' => $month, 'lectures' => $lectures, 'firstTotal' => $firstTotal]);
    }

    public function sat1View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];

        return view('dash.satisfaction.sat1', ['data' => $data, 'year' => $year, 'month' => $month]);
    }

    public function sat2View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();
        $totalCol = FourthTotal::where('year', $year)->where('month', $month)->get()->groupBy('userId');
        $totalColsort = $totalCol->sortByDesc(function($data) {
            return $data->avg('overallSatisfactionRate');
        });
        $largestCol = User::find($totalColsort->keys()->first());
        //return dd($totalColsort);
        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대 평균' , 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
            [ 'name' => !is_null($totalColsort->keys()->first()) ? $largestCol->univName : Auth::user()->univName, 'name2' => '(최다)', 'value' => !is_null($totalColsort->keys()->first()) ? floor($totalCol[$totalColsort->keys()->first()]->avg('overallSatisfactionRate')) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => 'true' , 'value' => !is_null($fourthTotal->first()) ? $fourthTotal->sortByDesc('overallSatisfactionRate')->first()->overallSatisfactionRate : 0],
            [ 'name' => 'false' , 'value' => !is_null($fourthTotal->first()) ? 100 - $fourthTotal->sortByDesc('overallSatisfactionRate')->first()->overallSatisfactionRate : 100],
        ];
        //2
        $data[] = !is_null($fourthTotal->first()) ? $fourthTotal->sortByDesc('overallSatisfactionRate')->first()->lectureName : '';
        //3
        $data[] = [
            [ 'name' => 'true' , 'value' => !is_null($fourthTotal->first()) ? $fourthTotal->sortBy('overallSatisfactionRate')->first()->overallSatisfactionRate : 0],
            [ 'name' => 'false' , 'value' => !is_null($fourthTotal->first()) ? 100 - $fourthTotal->sortBy('overallSatisfactionRate')->first()->overallSatisfactionRate : 100],
        ];
        //4
        $data[] = !is_null($fourthTotal->first()) ? $fourthTotal->sortBy('overallSatisfactionRate')->first()->lectureName : '';

        //5
        if(!is_null($fourthTotal->first())){
            $data[] = [
                [ 'name' => '자기평가' , 'value' => floor($fourthTotal->avg('selfEvaluationRate')), 'value2' => $firstTotal->selfEvaluationSatisfactionRate],
                [ 'name' => '강의지원' , 'value' => floor($fourthTotal->avg('lectureSupportRate')), 'value2' => $firstTotal->lectureSupportSatisfactionRate],
                [ 'name' => '학습내용/교수법' , 'value' => floor($fourthTotal->avg('learningContentEvaluationRate')), 'value2' => $firstTotal->overallSatisfactionRate],
                [ 'name' => '학습평가' , 'value' => floor($fourthTotal->avg('evaluationRate')), 'value2' => $firstTotal->learningEvaluationSatisfactionRate],
                [ 'name' => '교육운영' , 'value' => floor($fourthTotal->avg('operatorEvaluationRate')), 'value2' => $firstTotal->educationOperationSatisfactionRate],
                [ 'name' => '시스템 이용' , 'value' => floor($fourthTotal->avg('systemEvaluationRate')), 'value2' => $firstTotal->overallSystemSatisfactionRate],
                [ 'name' => '전반적 만족도' , 'value' => floor($fourthTotal->avg('totalSatisfactionRate')), 'value2' => $firstTotal->totalSatisfactionRate],
            ];
        }else{
            $data[] = [
                [ 'name' => '자기평가' , 'value' => '', 'value2' => ''],
                [ 'name' => '강의지원' , 'value' => '', 'value2' => ''],
                [ 'name' => '학습내용/교수법' , 'value' => '', 'value2' => ''],
                [ 'name' => '학습평가' , 'value' => '', 'value2' => ''],
                [ 'name' => '교육운영' , 'value' => '', 'value2' => ''],
                [ 'name' => '시스템 이용' , 'value' => '', 'value2' => ''],
                [ 'name' => '전반적 만족도' , 'value' => '', 'value2' => ''],
            ];
        }

        return view('dash.satisfaction.sat2', ['data' => $data, 'year' => $year, 'month' => $month]);

    }

    public function sat3View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $secondTotal = SecondTotal::where('year', $year)->where('month', $month)->get();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('selfEvaluationRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->selfEvaluationSatisfactionRate) : '' ],
        ];

        //2
        $maxLecture = $fourthTotal->sortByDesc('selfEvaluationRate');
        if(!is_null($maxLecture->first())){
            $totalMaxLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $maxLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($maxLecture->first()) ? floor($maxLecture->first()->selfEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($maxLecture->first()) ? ($firstTotal ? floor($totalMaxLecture->selfEvaluationRate) : '') : '' ],
        ];

        //3
        $minLecture = $fourthTotal->sortBy('selfEvaluationRate');
        if(!is_null($minLecture->first())) {
            $totalMinLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $minLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($minLecture->first()) ? floor($minLecture->first()->selfEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($minLecture->first()) ? ($firstTotal ? floor($totalMinLecture->selfEvaluationRate) : '') : '' ],
        ];

        //4, 5
        $data[] = !is_null($maxLecture->first()) ? $maxLecture->first()->lectureName : '';
        $data[] = !is_null($minLecture->first()) ? $minLecture->first()->lectureName : '';

        return view('dash.satisfaction.sat3', ['data' => $data, 'year' => $year, 'month' => $month, 'secondTotals' => $secondTotal, 'fourthTotals' => $fourthTotal]);
    }

    public function sat4View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $secondTotal = SecondTotal::where('year', $year)->where('month', $month)->get();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('lectureSupportRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->lectureSupportSatisfactionRate) : '' ],
        ];

        //2
        $maxLecture = $fourthTotal->sortByDesc('lectureSupportRate');
        if(!is_null($maxLecture->first())){
            $totalMaxLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $maxLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($maxLecture->first()) ? floor($maxLecture->first()->lectureSupportRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($maxLecture->first()) ? ($firstTotal ? floor($totalMaxLecture->lectureSupportRate) : '') : '' ],
        ];

        //3
        $minLecture = $fourthTotal->sortBy('lectureSupportRate');
        if(!is_null($minLecture->first())) {
            $totalMinLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $minLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($minLecture->first()) ? floor($minLecture->first()->lectureSupportRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($minLecture->first()) ? ($firstTotal ? floor($totalMinLecture->lectureSupportRate) : '') : '' ],
        ];

        //4, 5
        $data[] = !is_null($maxLecture->first()) ? $maxLecture->first()->lectureName : '';
        $data[] = !is_null($minLecture->first()) ? $minLecture->first()->lectureName : '';

        return view('dash.satisfaction.sat4', ['data' => $data, 'year' => $year, 'month' => $month, 'secondTotals' => $secondTotal, 'fourthTotals' => $fourthTotal]);
    }

    public function sat5View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $secondTotal = SecondTotal::where('year', $year)->where('month', $month)->get();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('learningContentEvaluationRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->overallSatisfactionRate) : '' ],
        ];

        //2
        $maxLecture = $fourthTotal->sortByDesc('learningContentEvaluationRate');
        if(!is_null($maxLecture->first())){
            $totalMaxLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $maxLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($maxLecture->first()) ? floor($maxLecture->first()->learningContentEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($maxLecture->first()) ? ($firstTotal ? floor($totalMaxLecture->learningContentEvaluationRate) : '') : '' ],
        ];

        //3
        $minLecture = $fourthTotal->sortBy('learningContentEvaluationRate');
        if(!is_null($minLecture->first())) {
            $totalMinLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $minLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($minLecture->first()) ? floor($minLecture->first()->learningContentEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($minLecture->first()) ? ($firstTotal ? floor($totalMinLecture->learningContentEvaluationRate) : '') : '' ],
        ];

        //4, 5
        $data[] = !is_null($maxLecture->first()) ? $maxLecture->first()->lectureName : '';
        $data[] = !is_null($minLecture->first()) ? $minLecture->first()->lectureName : '';

        return view('dash.satisfaction.sat5', ['data' => $data, 'year' => $year, 'month' => $month, 'secondTotals' => $secondTotal, 'fourthTotals' => $fourthTotal]);
    }

    public function sat6View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $secondTotal = SecondTotal::where('year', $year)->where('month', $month)->get();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('evaluationRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->learningEvaluationSatisfactionRate) : '' ],
        ];

        //2
        $maxLecture = $fourthTotal->sortByDesc('evaluationRate');
        if(!is_null($maxLecture->first())){
            $totalMaxLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $maxLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($maxLecture->first()) ? floor($maxLecture->first()->evaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($maxLecture->first()) ? ($firstTotal ? floor($totalMaxLecture->evaluationRate) : '') : '' ],
        ];

        //3
        $minLecture = $fourthTotal->sortBy('evaluationRate');
        if(!is_null($minLecture->first())) {
            $totalMinLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $minLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($minLecture->first()) ? floor($minLecture->first()->evaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($minLecture->first()) ? ($firstTotal ? floor($totalMinLecture->evaluationRate) : '') : '' ],
        ];

        //4, 5
        $data[] = !is_null($maxLecture->first()) ? $maxLecture->first()->lectureName : '';
        $data[] = !is_null($minLecture->first()) ? $minLecture->first()->lectureName : '';

        return view('dash.satisfaction.sat6', ['data' => $data, 'year' => $year, 'month' => $month, 'secondTotals' => $secondTotal, 'fourthTotals' => $fourthTotal]);
    }

    public function sat7View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $secondTotal = SecondTotal::where('year', $year)->where('month', $month)->get();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('operatorEvaluationRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->educationOperationSatisfactionRate) : '' ],
        ];

        //2
        $maxLecture = $fourthTotal->sortByDesc('operatorEvaluationRate');
        if(!is_null($maxLecture->first())){
            $totalMaxLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $maxLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($maxLecture->first()) ? floor($maxLecture->first()->operatorEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($maxLecture->first()) ? ($firstTotal ? floor($totalMaxLecture->operatorEvaluationRate) : '') : '' ],
        ];

        //3
        $minLecture = $fourthTotal->sortBy('operatorEvaluationRate');
        if(!is_null($minLecture->first())) {
            $totalMinLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $minLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($minLecture->first()) ? floor($minLecture->first()->operatorEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($minLecture->first()) ? ($firstTotal ? floor($totalMinLecture->operatorEvaluationRate) : '') : '' ],
        ];

        //4, 5
        $data[] = !is_null($maxLecture->first()) ? $maxLecture->first()->lectureName : '';
        $data[] = !is_null($minLecture->first()) ? $minLecture->first()->lectureName : '';

        return view('dash.satisfaction.sat7', ['data' => $data, 'year' => $year, 'month' => $month, 'secondTotals' => $secondTotal, 'fourthTotals' => $fourthTotal]);
    }

    public function sat8View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $secondTotal = SecondTotal::where('year', $year)->where('month', $month)->get();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('systemEvaluationRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->overallSystemSatisfactionRate) : '' ],
        ];

        //2
        $maxLecture = $fourthTotal->sortByDesc('systemEvaluationRate');
        if(!is_null($maxLecture->first())){
            $totalMaxLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $maxLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($maxLecture->first()) ? floor($maxLecture->first()->systemEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($maxLecture->first()) ? ($firstTotal ? floor($totalMaxLecture->systemEvaluationRate) : '') : '' ],
        ];

        //3
        $minLecture = $fourthTotal->sortBy('systemEvaluationRate');
        if(!is_null($minLecture->first())) {
            $totalMinLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $minLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($minLecture->first()) ? floor($minLecture->first()->systemEvaluationRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($minLecture->first()) ? ($firstTotal ? floor($totalMinLecture->systemEvaluationRate) : '') : '' ],
        ];

        //4, 5
        $data[] = !is_null($maxLecture->first()) ? $maxLecture->first()->lectureName : '';
        $data[] = !is_null($minLecture->first()) ? $minLecture->first()->lectureName : '';

        return view('dash.satisfaction.sat8', ['data' => $data, 'year' => $year, 'month' => $month, 'secondTotals' => $secondTotal, 'fourthTotals' => $fourthTotal]);
    }

    public function sat9View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];
        $firstTotal = FirstTotal::where('year', $year)->where('month', $month)->first();
        $secondTotal = SecondTotal::where('year', $year)->where('month', $month)->get();
        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('overallSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->satisfactionRate) : '' ],
        ];

        //1
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => floor($fourthTotal->avg('totalSatisfactionRate')) ],
            [ 'name' => '참여대', 'value' => $firstTotal ? floor($firstTotal->totalSatisfactionRate) : '' ],
        ];

        //2
        $maxLecture = $fourthTotal->sortByDesc('totalSatisfactionRate');
        if(!is_null($maxLecture->first())){
            $totalMaxLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $maxLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($maxLecture->first()) ? floor($maxLecture->first()->totalSatisfactionRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($maxLecture->first()) ? ($firstTotal ? floor($totalMaxLecture->totalSatisfactionRate) : '') : '' ],
        ];

        //3
        $minLecture = $fourthTotal->sortBy('totalSatisfactionRate');
        if(!is_null($minLecture->first())) {
            $totalMinLecture = SecondTotal::where('year', $year)->where('month', $month)->where('lectureName', $minLecture->first()->lectureName)->first();
        }
        $data[] = [
            [ 'name' => Auth::user()->univName, 'value' => !is_null($minLecture->first()) ? floor($minLecture->first()->totalSatisfactionRate) : '' ],
            [ 'name' => '참여대', 'value' => !is_null($minLecture->first()) ? ($firstTotal ? floor($totalMinLecture->totalSatisfactionRate) : '') : '' ],
        ];

        //4, 5
        $data[] = !is_null($maxLecture->first()) ? $maxLecture->first()->lectureName : '';
        $data[] = !is_null($minLecture->first()) ? $minLecture->first()->lectureName : '';

        return view('dash.satisfaction.sat9', ['data' => $data, 'year' => $year, 'month' => $month, 'secondTotals' => $secondTotal, 'fourthTotals' => $fourthTotal]);
    }

    public function sat0View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $data = [];

        return view('dash.satisfaction.sat0', ['data' => $data, 'year' => $year, 'month' => $month]);
    }

    public function result1View()
    {
        return view('dash.result.result1');
    }

    public function result2View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 2) {
                $month = 1;
            }
        }

        $_year = $month == 1 ? $year - 1 : $year;
        $_month = $month == 1 ? 2 : 1;

        $__year = $_month == 1 ? $_year - 1 : $_year;
        $__month = $_month == 1 ? 2 : 1;

        $data = [];

        $thirdTotal = ThirdTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();
        $_thirdTotal = ThirdTotal::where('year', $_year)->where('month', $_month)->where('userId', Auth::id())->get();
        $__thirdTotal = ThirdTotal::where('year', $__year)->where('month', $__month)->where('userId', Auth::id())->get();

        //0
        $data[] = [
            [ 'name' => $__year, 'name2' => $__month.'학기', 'value' => $__thirdTotal->sum('memberCount') ],
            [ 'name' => $_year, 'name2' => $_month.'학기', 'value' => $_thirdTotal->sum('memberCount') ],
            [ 'name' => $year, 'name2' => $month.'학기', 'value' => $thirdTotal->sum('memberCount') ],
        ];

        //1
        $data[] = [
            [ 'name' => $__year, 'name2' => $__month.'학기', 'value' => $__thirdTotal->count() ],
            [ 'name' => $_year, 'name2' => $_month.'학기', 'value' => $_thirdTotal->count() ],
            [ 'name' => $year, 'name2' => $month.'학기', 'value' => $thirdTotal->count() ],
        ];

        //2
        $data[] = [
            [
                'name' => '출석률',
                'value' => floor($__thirdTotal->avg('attendanceRate')),
                'value2' => floor($_thirdTotal->avg('attendanceRate')),
                'value3' => floor($thirdTotal->avg('attendanceRate')),
            ],
            [
                'name' => '학습진도율',
                'value' => floor($__thirdTotal->avg('learningProgressRate')),
                'value2' => floor($_thirdTotal->avg('learningProgressRate')),
                'value3' => floor($thirdTotal->avg('learningProgressRate')),
            ],
            [
                'name' => '수료율',
                'value' => floor($__thirdTotal->avg('finalCompletionRate')),
                'value2' => floor($_thirdTotal->avg('finalCompletionRate')),
                'value3' => floor($thirdTotal->avg('finalCompletionRate')),
            ],
            [
                'name' => '결석률',
                'value' => floor($__thirdTotal->avg('absenceRate')),
                'value2' => floor($_thirdTotal->avg('absenceRate')),
                'value3' => floor($thirdTotal->avg('absenceRate')),
            ],
        ];

        //3
        $data[] = [
            [
                'name' => '시험응시율',
                'value' => floor($__thirdTotal->avg('testApplicationRate')),
                'value2' => floor($_thirdTotal->avg('testApplicationRate')),
                'value3' => floor($thirdTotal->avg('testApplicationRate')),
            ],
            [
                'name' => '과제수행률',
                'value' => floor($__thirdTotal->avg('taskPerformanceRate')),
                'value2' => floor($_thirdTotal->avg('taskPerformanceRate')),
                'value3' => floor($thirdTotal->avg('taskPerformanceRate')),
            ],
            [
                'name' => '토론수행률',
                'value' => floor($__thirdTotal->avg('discussionParticipationRate')),
                'value2' => floor($_thirdTotal->avg('discussionParticipationRate')),
                'value3' => floor($thirdTotal->avg('discussionParticipationRate')),
            ],
            [
                'name' => '퀴즈수행률',
                'value' => floor($__thirdTotal->avg('quizProgressRate')),
                'value2' => floor($_thirdTotal->avg('quizProgressRate')),
                'value3' => floor($thirdTotal->avg('quizProgressRate')),
            ],
            [
                'name' => '학습참여도',
                'value' => floor($__thirdTotal->avg('learningParticipationRate')),
                'value2' => floor($_thirdTotal->avg('learningParticipationRate')),
                'value3' => floor($thirdTotal->avg('learningParticipationRate')),
            ],
        ];

        $fourthTotal = FourthTotal::where('year', $year)->where('month', $month)->where('userId', Auth::id())->get();
        $_fourthTotal = FourthTotal::where('year', $_year)->where('month', $_month)->where('userId', Auth::id())->get();
        $__fourthTotal = FourthTotal::where('year', $__year)->where('month', $__month)->where('userId', Auth::id())->get();

        //4
        $data[] = [
             [
                 'name1' => '종합',
                 'name2' => '만족도',
                 'value1' => floor($__fourthTotal->avg('overallSatisfactionRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('overallSatisfactionRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('overallSatisfactionRate') * 10) / 10,
             ],
             [
                 'name1' => '자기',
                 'name2' => '평가',
                 'value1' => floor($__fourthTotal->avg('selfEvaluationRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('selfEvaluationRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('selfEvaluationRate') * 10) / 10,
             ],
             [
                 'name1' => '강의',
                 'name2' => '지원',
                 'value1' => floor($__fourthTotal->avg('lectureSupportRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('lectureSupportRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('lectureSupportRate') * 10) / 10,
             ],
             [
                 'name1' => '내용/',
                 'name2' => '교수법',
                 'value1' => floor($__fourthTotal->avg('learningContentEvaluationRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('learningContentEvaluationRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('learningContentEvaluationRate') * 10) / 10,
             ],
             [
                 'name1' => '학습',
                 'name2' => '평가',
                 'value1' => floor($__fourthTotal->avg('evaluationRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('evaluationRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('evaluationRate') * 10) / 10,
             ],
             [
                 'name1' => '교육',
                 'name2' => '문명',
                 'value1' => floor($__fourthTotal->avg('operatorEvaluationRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('operatorEvaluationRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('operatorEvaluationRate') * 10) / 10,
             ],
             [
                 'name1' => '시스템',
                 'name2' => '',
                 'value1' => floor($__fourthTotal->avg('systemEvaluationRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('systemEvaluationRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('systemEvaluationRate') * 10) / 10,
             ],
             [
                 'name1' => '전반적',
                 'name2' => '만족도',
                 'value1' => floor($__fourthTotal->avg('totalSatisfactionRate') * 10) / 10,
                 'value2' => floor($_fourthTotal->avg('totalSatisfactionRate') * 10) / 10,
                 'value3' => floor($fourthTotal->avg('totalSatisfactionRate') * 10) / 10,
             ],
         ];

        $data[] = [
            'columns' => [
                'name1', 'name2', 'value1', 'value2', 'value3'
            ]
        ];

        $firstPer = FirstPer::where('year', $year)->where('month', $month)->where('userId', Auth::id())->first();

        return view('dash.result.result2', [
            'data' => $data,
            'year' => $year,
            'month' => $month,
            '_year' => $_year,
            '_month' => $_month,
            '__year' => $__year,
            '__month' => $__month,
            'firstPer' => $firstPer,
        ]);
    }

    public function result3View()
    {
        return view('dash.result.result3');
    }

    public function result4View(Request $request)
    {
        $year = (int)date('m') > 6 ? date("Y") : (int)date("Y") - 1;
        $month = (int)date('m') > 6 ? 1 : 2;
        if ($request->has('year') && $request->has('month')) {
            $year = $request['year'];
            $month = $request['month'];

            if ($month > 4) {
                $month = 1;
            }
        }

        $secondPer = SecondPer::where('year', $year)->where('month', $month)->get();

        return view('dash.result.result4', ['secondPer' => $secondPer, 'year' => $year, 'month' => $month]);
    }

    public function result5View()
    {
        return view('dash.result.result5');
    }

    public function result6View()
    {
        return view('dash.result.result6');
    }
}
