<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $table = 'work_experience';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jobseeker_id',
        'title',
        'company',
        'start_date',
        'end_date',
        'description',
        'leaving_reason',
    ];

    public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class, 'jobseeker_id');
    }
}
