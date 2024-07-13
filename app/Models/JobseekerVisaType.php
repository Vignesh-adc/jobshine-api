<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerVisaType extends Model
{
    protected $table = 'jobseeker_visa_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'jobseeker_id',
        'visa_type_id',
    ];

    public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class, 'jobseeker_id');
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class, 'visa_type_id');
    }
}
