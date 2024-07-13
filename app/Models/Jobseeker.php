<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobseeker extends Model
{
    protected $table = 'jobseekers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jobseeker_unique_id',
        'full_name',
        'desired_salary_cleaned',
        'location',
        'personal_summary',
        'num_year_of_birth',
        'skills',
        'zip_code',
        'year_of_birth',
        'phone_cleaned',
        'resume_url',
        'jobseeker_email',
        'have_resume',
        'have_phone',
        'have_email',
        'previously_revealed',
        'has_shown_irratic_behaviour',
        'identifier_irratic_behaviour',
        'is_physically_present',
        'picked_up_phone',
        'profile_type',
    ];

    public function education()
    {
        return $this->hasMany(Education::class, 'jobseeker_id');
    }

    public function employmentHistory()
    {
        return $this->hasMany(EmploymentHistory::class, 'jobseeker_id');
    }

    public function jobCategories()
    {
        return $this->hasMany(JobseekerJobCategory::class, 'jobseeker_id');
    }

    // Define the relationship to JobseekerVisaType
    public function visaTypes()
    {
        return $this->hasMany(JobseekerVisaType::class, 'jobseeker_id');
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class, 'jobseeker_id');
    }
}
