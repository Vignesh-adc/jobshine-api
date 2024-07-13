<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentHistory extends Model
{
    protected $table = 'employment_history';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jobseeker_id',
        'employer_org_name',
        'position_title',
        'position_type',
        'description',
        'start_date',
        'end_date',
    ];

    public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class, 'jobseeker_id');
    }
}
