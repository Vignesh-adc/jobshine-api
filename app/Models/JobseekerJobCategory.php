<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerJobCategory extends Model
{
    protected $table = 'jobseeker_job_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jobseeker_id',
        'job_category_id',
    ];

    public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class, 'jobseeker_id');
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }
}
