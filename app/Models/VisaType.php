<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    protected $table = 'visa_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'visa_type_name',
    ];

    public function jobseekerVisaTypes()
    {
        return $this->hasMany(JobseekerVisaType::class, 'visa_type_id');
    }
}
