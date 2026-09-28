<?php

namespace App\Imports;

use App\Models\ThirdTotal;
use App\Models\User;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;

class ThirdTotalImport implements ToCollection
{
    public $year;
    public $month;
    public $userId;

    public function __construct($year, $month, $userId)
    {
        $this->year = $year;
        $this->month = $month;
        $this->userId = $userId;
    }

    public function collection(Collection $rows)
    {
//        return dd($rows);
        unset($rows[0]);
        if (!count($rows)) {
            return '잘못된 파일';
        }

        if(!User::find($this->userId)){
            return '해당 유저를 찾을 수 없음';
        }

//        if (ThirdTotal::where('year', $this->year)->where('month', $this->month)->where('userId', $this->userId)->first()) {
//            return '이미 존재한 데이터';
//        }

        $data = [];
        foreach ($rows as $key => $val) {
            if(count($rows[$key]) === 26){
                $test = null;
                foreach($rows[$key] as $r) {
                    if (!is_null($r)) {
                        $test = $r;
                    }
                }

                if (!is_null($test)) {
                    $test = [];
                    $test[1] = (string)$val[1];                     //과목명
                    $test[2] = (int)$val[2];                        //학점
                    $test[3] = (string)$val[3];                     //과목구분
                    $test[4] = (string)$val[4];                     //담당교수 소속대학
                    $test[5] = (string)$val[5];                     //교수명
                    $test[6] = (int)$val[6];                        //수강생 수
                    $test[7] = (int)$val[7];                        //수료자 수
                    $test[8] = $this->castType($val[8]);            //최종 수료율
                    //추가
                    $test[9] = (int)$val[9];                      //최종성적 60점이상 취득자수
                    $test[10] = $this->castType($val[10]);         //최종성적 60점이상 취득률
//                    $test[9] = (int)str_replace(',','',(string)$val[9]);    //이수 합계???    Delete
                    $test[11] = $this->castType($val[11]);          //학습 진요율(%)
                    $test[12] = $this->castType($val[12]);          //출석률(%)
                    $test[13] = $this->castType($val[13]);          //지각률(%)
                    $test[14] = $this->castType($val[14]);          //결석률(%)
                    $test[15] = (int)$val[15];                      //시험 응시수(중간)
                    $test[16] = $this->castType($val[16]);          //시험 응시율(중간)(%)
                    $test[17] = (int)$val[17];                      //시험 응시수(기말)
                    $test[18] = $this->castType($val[18]);          //시험 응시율(기말)(%)
                    $test[19] = $this->castType($val[19]);          //시험 응시율(전체)(%)
                    $test[20] = $this->castType($val[20]);          //과제 수행율(%)
                    $test[21] = $this->castType($val[21]);          //토론 참여율(%)
                    $test[22] = $this->castType($val[22]);          //퀴즈 수행율(%)
                    $test[23] = $this->castType($val[23]);          //학습 참여도(%)
                    $data[] = $test;
                }
            }
            else{
                return redirect()->back()->withErrors(['error' => '잘못된 데이터 형식입니다.']);
            }
        }

        $validator = Validator::make($data, [
            '*.1' => 'string',                                  //과목명
            '*.2' => 'integer',                                 //학점
            '*.3' => 'string',                                  //과목구분
            '*.4' => 'string',                                  //담당교수 소속대학
            '*.5' => 'string',                                  //교수명
            '*.6' => 'integer',                                 //수강생 수
            '*.7' => 'integer',                                 //수료자 수
            '*.8' => 'numeric|between:0,100',                   //최종 수료율
            //추가 0621
            '*.9' => 'integer',                                 //최종성적 60점 이상 취득자수
            '*.10' => 'numeric|between:0,100',                  //최종성적 60점 이상 취득률
            //
            '*.11' => 'numeric|between:0,100',                  //학습 진도율
            '*.12' => 'numeric|between:0,100',                  //출석률(%)
            '*.13' => 'numeric|between:0,100',                  //지각률(%)
            '*.14' => 'numeric|between:0,100',                  //결석률(%)
            '*.15' => 'integer',                                //시험 응시수(중간)
            '*.16' => 'numeric|between:0,100',                  //시험 응시율(중간)(%)
            '*.17' => 'integer',                                //시험 응시수(기말)
            '*.18' => 'numeric|between:0,100',                  //시험 응시율(기말)(%)
            '*.19' => 'numeric|between:0,100',                  //시험 응시율(전체)(%)
            '*.20' => 'numeric|between:0,100',                  //과제 수행율(%)
            '*.21' => 'numeric|between:0,100',                  //토론 참여율(%)
            '*.22' => 'numeric|between:0,100',                  //퀴즈 수행율(%)
            '*.23' => 'numeric|between:0,100',                  //학습 참여도(%)
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

//        return dd($rows);
//        return dd($rows[6][8]);
//        return dd($validator->errors());

        $totals = ThirdTotal::where('year', $this->year)->where('month', $this->month)->where('userId', $this->userId);

        if(count($totals->get())){
            $totals->delete();
        }

        foreach ($data as $row) {
            ThirdTotal::create([
                'year' => $this->year,
                'month' => $this->month,
                'userId' => $this->userId,
                'lectureName' => $row[1],                           //과목명
                'grades' => $row[2],                                //학점
                'subjectClassification' => $row[3],                 //과목구분
                'professorUnivName' => $row[4],                     //담당교수 소속대학
                'professorName' => $row[5],                         //교수명
                'memberCount' => $row[6],                           //수강생 수
                'graduatesCount' => $row[7],                        //수료자 수
                'finalCompletionRate' => $row[8],                   //최종수료율
                'FinalScoreSixtyPointTakers' => $row[9],             //최종성적 60점 이상 취득자수?
                'FinalScoreSixtyPointApplicantRate' => $row[10],     //최종성적 60점 이상 취득률?
                //'sum' => $row[9],                                 //이수합계
                'learningProgressRate' => $row[11],                 //학습 진도율
                'attendanceRate' => $row[12],                       //출석률
                'lateRate' => $row[13],                             //지각률
                'absenceRate' => $row[14],                          //결석률
                'midtermTestTakers' => $row[15],                    //시험 응시수(중간)
                'midtermTestApplicationRate' => $row[16],           //시험 응시율(중간)
                'finalTestTakers' => $row[17],                      //시험 응시수(기말)
                'finalTestApplicationRate' => $row[18],             //시험 응시율(기말)
                'testApplicationRate' => $row[19],                  //시험 응시율(전체)
                'taskPerformanceRate' => $row[20],                  //과제 수행율
                'discussionParticipationRate' => $row[21],          //토론 참여율
                'quizProgressRate' => $row[22],                     //퀴즈 수행율
                'learningParticipationRate' => $row[23],            //학습 참여율
            ]);
        }
    }

    private function castType($data){
        //integer
        //string
        //double
        if(gettype($data) === 'string'){
            return (float)$data;
        }
        else if(gettype($data) === 'double'){
            if((int)floor($data*10000) === 0){
                return 0.0;
            }
            else if(100 - (int)floor($data*100) <= 100 && 0 <= 100 - (int)floor($data*100)){
                return $data*100;
            }
            else{
                return $data;
            }
        }
        else if(gettype($data) === 'integer'){
            if((int)floor($data*10000) === 0){
                return 0.0;
            }
            else if(100 - (int)floor($data*100) <= 100 && 0 <= 100 - (int)floor($data*100)){
                return (float)$data*100;
            }
            else{
                return (float)$data;
            }
        }
        else{
            return -1;
        }


    }
}




/*
 ThirdTotal::create([
                'year' => $this->year,
                'month' => $this->month,
                'userId' => $this->userId,
                'lectureName' => $row[1],   //과목명
                'grades' => $row[2],    //학점
                'subjectClassification' => $row[3], //과목구분
                'professorUnivName' => $row[4], //담당교수 소속대학
                'professorName' => $row[5], //교수명
                'memberCount' => $row[6],   //수강생 수
                'graduatesCount' => $row[7],    //수료자 수
                'finalCompletionRate' => $row[8],   //최종수료율
                //FinalScoreSixtyPointTakers => $row[9]    //최종성적 60점 이상 취득자수
                //FinalScoreSixtyPointApplicantRate => $row[10] //최종성적 60점 이상 취득률
                //'sum' => $row[9],   //이수합계
                'learningProgressRate' => $row[10], //학습 진도율
                'attendanceRate' => $row[11],   //출석률
                'lateRate' => $row[12], //지각률
                'absenceRate' => $row[13],  //결석률
                'midtermTestTakers' => $row[14],    //시험 응시수(중간)
                'midtermTestApplicationRate' => $row[15],//시험 응시율(중간)
                'finalTestTakers' => $row[16],  //시험 응시수(기말)
                'finalTestApplicationRate' => $row[17], //시험 응시율(기말)
                'testApplicationRate' => $row[18],
                'taskPerformanceRate' => $row[19],  //과제 수행율
                'discussionParticipationRate' => $row[20],  //토론 참여율
                'quizProgressRate' => $row[21], //퀴즈 수행율
                'learningParticipationRate' => $row[22],    //학습 참여율
            ]);
 * */
