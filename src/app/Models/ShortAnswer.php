<?php

namespace App\Models;

use App\Casts\PercentageCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'lectureId',
        'year',
        'month',
//        'lectureName',
        'positive',
        'negative',
        'wordCloudName',
        'wordCloudPathName',
    ];

    protected $casts = [
    ];

    public function getLectureName () {
        return $this->hasOne(ThirdTotal::class, 'id', 'lectureId')->select('id', 'lectureName')->first();
    }
}
