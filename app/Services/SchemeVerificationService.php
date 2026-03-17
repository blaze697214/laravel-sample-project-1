<?php

namespace App\Services;

use App\Models\ClassAwardConfiguration;
use App\Models\CourseDetail;
use App\Models\DepartmentCourse;
use App\Models\DepartmentLevelDetail;
use App\Models\SemesterPlacement;

class SchemeVerificationService
{
    public function verifyDepartment($schemeId, $departmentId)
    {

        $departmentLevel = DepartmentLevelDetail::where('department_id', $departmentId)
            ->exists();

        $requiredCourses = DepartmentCourse::where('department_id', $departmentId)
            ->count();

        $configuredCourses = CourseDetail::whereHas('departmentCourse', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->count();

        $courseDetails = $requiredCourses > 0 && $requiredCourses == $configuredCourses;

        $classAward = ClassAwardConfiguration::where('department_id', $departmentId)
            ->where('curriculum_year_id', $schemeId)
            ->exists();

        $semesterPlacement = SemesterPlacement::where('department_id', $departmentId)
            ->where('curriculum_year_id', $schemeId)
            ->exists();

        return [

            'departmentLevel' => $departmentLevel,

            'courseDetails' => $courseDetails,

            'configuredCourses' => $configuredCourses,

            'classAward' => $classAward,

            'semesterPlacement' => $semesterPlacement,

            'complete' => $departmentLevel &&
                $courseDetails &&
                $classAward &&
                $semesterPlacement,
        ];
    }
}
