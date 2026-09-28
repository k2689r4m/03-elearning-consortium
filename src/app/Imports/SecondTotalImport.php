<?php

namespace App\Imports;

use App\Models\SecondTotal;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;

class SecondTotalImport implements ToCollection
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
//
//        if(SecondTotal::where('year', $this->year)->where('month', $this->month)->first()){
//            return '이미 존재한 데이터';
//        }

        foreach ($rows as $key => $val) {
            if($val[0] && count($val) === 14){
                $val[1] = (string)$val[1];
                $val[2] = (int)$val[2];
                $val[3] = (string)$val[3];
                $val[4] = (string)$val[4];
                $val[5] = (string)$val[5];
                $val[6] = $this->castType($val[6]);
                $val[7] = $this->castType($val[7]);
                $val[8] = $this->castType($val[8]);
                $val[9] = $this->castType($val[9]);
                $val[10] = $this->castType($val[10]);
                $val[11] = $this->castType($val[11]);
                $val[12] = $this->castType($val[12]);
                $val[13] = $this->castType($val[13]);
            }
            else{
                return redirect()->back()->withErrors(['error' => '잘못된 데이터 형식입니다.']);
            }
        }

        Validator::make($rows->toArray(), [
            '*.1' => 'string',
            '*.2' => 'integer',
            '*.3' => 'string',
            '*.4' => 'string',
            '*.5' => 'string',
            '*.6' => 'numeric|between:0,100',
            '*.7' => 'numeric|between:0,100',
            '*.8' => 'numeric|between:0,100',
            '*.9' => 'numeric|between:0,100',
            '*.10' => 'numeric|between:0,100',
            '*.11' => 'numeric|between:0,100',
            '*.12' => 'numeric|between:0,100',
            '*.13' => 'numeric|between:0,100',
        ])->validate();

        $totals = SecondTotal::where('year', $this->year)->where('month', $this->month);

        if(count($totals->get())){
            $totals->delete();
        }

        foreach ($rows as $row)
        {
            SecondTotal::create([
                'year' => $this->year,
                'month' => $this->month,
                'lectureName' => $row[1],
                'grades' => $row[2],
                'subjectClassification' => $row[3],
                'professorUnivName' => $row[4],
                'professorName' => $row[5],
                'overallSatisfactionRate' => $row[6],
                'selfEvaluationRate' => $row[7],
                'lectureSupportRate' => $row[8],
                'learningContentEvaluationRate' => $row[9],
                'evaluationRate' => $row[10],
                'operatorEvaluationRate' => $row[11],
                'systemEvaluationRate' => $row[12],
                'totalSatisfactionRate' => $row[13],
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
