<?php

namespace App\Services;

use App\Models\ClassAwardConfiguration;
use App\Models\CourseDetail;
use App\Models\DepartmentLevelDetail;
use App\Models\SemesterPlacement;

class SchemeVerificationService
{
    public function verifyDepartment($schemeId, $departmentId)
    {

        $departmentLevel = DepartmentLevelDetail::where('department_id', $departmentId)
            // ->where('curriculum_year_id',$schemeId)
            ->exists();

        $courseDetails = CourseDetail::whereHas('departmentCourse', function ($q) use ($departmentId) {

            $q->where('department_id', $departmentId);

        })->exists();

        $classAward = ClassAwardConfiguration::where('department_id', $departmentId)
            ->where('curriculum_year_id', $schemeId)
            ->exists();

        $semesterPlacement = SemesterPlacement::where('department_id', $departmentId)
            ->where('curriculum_year_id', $schemeId)
            ->exists();

        return [

            'departmentLevel' => $departmentLevel,

            'courseDetails' => $courseDetails,

            'classAward' => $classAward,

            'semesterPlacement' => $semesterPlacement,

            'complete' => $departmentLevel &&
        $courseDetails &&
        $classAward &&
        $semesterPlacement,

        ];
    }
}
