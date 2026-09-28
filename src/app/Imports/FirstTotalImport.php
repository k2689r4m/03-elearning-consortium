<?php

namespace App\Imports;

use App\Models\FirstTotal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;


class FirstTotalImport implements ToCollection
{
    public $year;
    public $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection(Collection $rows)
    {
        unset($rows[0]);

        if(!count($rows)){
            return '잘못된 파일';
        }

        foreach ($rows as $key => $val) {
            if($val[0] && (count($val) === 22 || count($val) === 21)){
                $val[0] = (int)$val[0];
                $val[1] = (int)$val[1];
                $val[2] = $this->castType($val[2]);
                $val[3] = $this->castType($val[3]);
                $val[4] = $this->castType($val[4]);
                $val[5] = $this->castType($val[5]);
                //$val[6] = $this->castType($val[6]);
                $val[6] = $this->castType($val[6]);
                $val[7] = $this->castType($val[7]);
                $val[8] = $this->castType($val[8]);
                $val[9] = $this->castType($val[9]);
                $val[10] = $this->castType($val[10]);
                $val[11] = $this->castType($val[11]);
                $val[12] = $this->castType($val[12]);
                $val[13] = $this->castType($val[13]);
                $val[14] = $this->castType($val[14]);
                $val[15] = $this->castType($val[15]);
                $val[16] = $this->castType($val[16]);
                $val[17] = $this->castType($val[17]);
                $val[18] = $this->castType($val[18]);
                $val[19] = $this->castType($val[19]);
            }else{
                return redirect()->back()->withErrors(['error' => '잘못된 데이터 형식입니다.']);
            }
        }

        Validator::make($rows->toArray(), [
            '*.0' => 'integer',
            '*.1' => 'integer',
            '*.2' => 'numeric|between:0,100',
            '*.3' => 'numeric|between:0,100',
            '*.4' => 'numeric|between:0,100',
            '*.5' => 'numeric|between:0,100',
            //'*.6' => 'numeric|between:0,100',
            '*.6' => 'numeric|between:0,100',
            '*.7' => 'numeric|between:0,100',
            '*.8' => 'numeric|between:0,100',
            '*.9' => 'numeric|between:0,100',
            '*.10' => 'numeric|between:0,100',
            '*.11' => 'numeric|between:0,100',
            '*.12' => 'numeric|between:0,100',
            '*.13' => 'numeric|between:0,100',
            '*.14' => 'numeric|between:0,100',
            '*.15' => 'numeric|between:0,100',
            '*.16' => 'numeric|between:0,100',
            '*.17' => 'numeric|between:0,100',
            '*.18' => 'numeric|between:0,100',
            '*.19' => 'numeric|between:0,100',
        ])->validate();

        $total = FirstTotal::where('year', $this->year)->where('month', $this->month)->first();

        if($total){
            $total->delete();
        }


        FirstTotal::create([
            'year' => $this->year,
            'month' => $this->month,
            'lectureCount' => $rows[1][0],                          //전체과목수
            'memberCount' => $rows[1][1],                           //전체인원수
            'attendanceRate' => $rows[1][2],                        //전체출석률
            'lateRate' => $rows[1][3],                              //전체지각률
            'absenceRate' => $rows[1][4],                           //전체결석률
            'learningProgressRate' => $rows[1][5],                  //전체학습진도율
            'completionRate' => $rows[1][6],                        //전체최종수료율
            //'overSixtyRate' => $rows[1][7],                         //전체최종성적 60점 이상수료율
            'testApplicationRate' => $rows[1][7],                   //전체시험응시율
            'taskPerformanceRate' => $rows[1][8],                   //전체과제수행률
            'discussionParticipationRate' => $rows[1][9],          //전체토론참여률
            'quizProgressRate' => $rows[1][10],                     //전체퀴즈수행률
            'learningParticipationRate' => $rows[1][11],            //전체학습참여도
            'satisfactionRate' => $rows[1][12],                     //전체종합만족도
            'selfEvaluationSatisfactionRate' => $rows[1][13],       //전체자기평가만족도
            'lectureSupportSatisfactionRate' => $rows[1][14],       //전체강의지원만족도
            'overallSatisfactionRate' => $rows[1][15],              //전체학습내용/교수자평가만족도
            'learningEvaluationSatisfactionRate' => $rows[1][16],   //전체학습평가만족도
            'educationOperationSatisfactionRate' => $rows[1][17],   //전체교육운영만족도
            'overallSystemSatisfactionRate' => $rows[1][18],        //전체시스템만족도
            'totalSatisfactionRate' => $rows[1][19],                //전체전반적인만족도
        ]);

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
