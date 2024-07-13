<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $table = 'job_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'job_category_name',
    ];

    public function jobseekerJobCategories()
    {
        return $this->hasMany(JobseekerJobCategory::class, 'job_category_id');
    }
}
