<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobseeker;

class JobseekerController extends Controller
{
    public function searchJobseekers(Request $request)
    {
        try {
            // Initialize the query
            $query = Jobseeker::query();

            // Ensure name and email are not empty
            $query->whereNotNull('full_name')
                ->whereNotNull('jobseeker_email')
                ->where('full_name', '!=', '')
                ->where('jobseeker_email', '!=', '');

            // Apply filters based on the 'name' and 'email'
            if ($request->filled('name') && $request->filled('email')) {
                $name = '%' . $request->input('name') . '%';
                $email = '%' . $request->input('email') . '%';

                $query->where(function ($query) use ($name, $email) {
                    $query->where('full_name', 'like', $name)
                        ->where('jobseeker_email', 'like', $email);
                });
            } elseif ($request->filled('name')) {
                $name = '%' . $request->input('name') . '%';
                $query->where(function ($query) use ($name) {
                    $query->where('full_name', 'like', $name)
                        ->orWhere('jobseeker_email', 'like', $name)
                        ->orWhere('phone_cleaned', 'like', $name)
                        ->orWhere('location', 'like', $name);
                });
            } elseif ($request->filled('email')) {
                $email = '%' . $request->input('email') . '%';
                $query->where('jobseeker_email', 'like', $email);
            }

            // Apply other filters
            if ($request->filled('zip_code')) {
                $query->where('zip_code', 'like', '%' . $request->input('zip_code') . '%');
            }

            if ($request->filled('min_salary')) {
                $query->where('desired_salary_cleaned', '>=', $request->input('min_salary'));
            }

            if ($request->filled('max_salary')) {
                $query->where('desired_salary_cleaned', '<=', $request->input('max_salary'));
            }

            if ($request->filled('job_category_ids')) {
                $jobCategoryIds = explode(',', $request->input('job_category_ids'));
                $query->whereHas('jobCategories', function ($q) use ($jobCategoryIds) {
                    $q->whereIn('job_category_id', $jobCategoryIds);
                });
            }

            if ($request->filled('visa_type_ids')) {
                $visaTypeIds = explode(',', $request->input('visa_type_ids'));
                $query->whereHas('visaTypes', function ($q) use ($visaTypeIds) {
                    $q->whereIn('visa_type_id', $visaTypeIds);
                });
            }

            // Use distinct to ensure unique emails
            $jobseekers = $query->distinct('jobseeker_email')
                                ->with(['jobCategories.jobCategory', 'visaTypes.visaType'])
                                ->get();

            // Construct the response JSON
            $response = [
                'error' => 0,
                'message' => 'Job seekers retrieved successfully',
                'data' => $jobseekers->map(function ($jobseeker) {
                    return [
                        'id' => $jobseeker->id,
                        'full_name' => $jobseeker->full_name,
                        'email' => $jobseeker->jobseeker_email,
                        'phone' => $jobseeker->phone_cleaned,
                        'location' => $jobseeker->location,
                        'zip_code' => $jobseeker->zip_code,
                        'resume_url' => $jobseeker->resume_url,
                        'desired_salary_cleaned' => $jobseeker->desired_salary_cleaned,
                        'job_categories' => $jobseeker->jobCategories->map(function ($jobCategory) {
                            return $jobCategory->jobCategory;
                        }),
                        'visa_types' => $jobseeker->visaTypes->map(function ($visaType) {
                            return $visaType->visaType;
                        }),
                    ];
                }),
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'error' => 1, 
                'message' => 'Internal server error.',
                'data' => []
            ], 500);
        }
    }

    public function getJobseekerDetails($id)
    {
        try {
            $jobseeker = Jobseeker::with([
                'jobCategories.jobCategory',
                'visaTypes.visaType',
                'education',
                'workExperiences',
                'employmentHistory'
            ])->findOrFail($id);

            $response = [
                'error' => 0,
                'message' => 'Job seeker details retrieved successfully',
                'data' => [
                    'id' => $jobseeker->id,
                    'full_name' => $jobseeker->full_name,
                    'email' => $jobseeker->jobseeker_email,
                    'phone' => $jobseeker->phone_cleaned,
                    'location' => $jobseeker->location,
                    'zip_code' => $jobseeker->zip_code,
                    'desired_salary_cleaned' => $jobseeker->desired_salary_cleaned,
                    'have_resume' => $jobseeker->have_resume,
                    'job_categories' => $jobseeker->jobCategories->map(function ($jobCategory) {
                        return $jobCategory->jobCategory->job_category_name;
                    })->implode(', '),
                    'visa_types' => $jobseeker->visaTypes->map(function ($visaType) {
                        return $visaType->visaType->visa_type_name;
                    })->implode(', '),
                    'education' => $jobseeker->education,
                    'work_experience' => $jobseeker->workExperiences,
                    'employment_history' => $jobseeker->employmentHistory,
                ],
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'error' => 1, 
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

}
