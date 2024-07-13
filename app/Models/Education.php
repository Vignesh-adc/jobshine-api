<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jobseeker_id',
        'university_institution',
        'degree_speciality',
        'start_date',
        'end_date',
        'description',
    ];

    public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class, 'jobseeker_id');
    }
}
