<?php

namespace App\Services;

use App\Models\ClassAwardConfiguration;
use App\Models\CourseDetail;
use App\Models\ProgrammeLevelDetail;
use App\Models\SemesterPlacement;

class SchemeVerificationService
{
    public function verifyProgramme($schemeId, $programmeId)
    {

        $programmeLevel = ProgrammeLevelDetail::where('programme_id', $programmeId)
            // ->where('curriculum_year_id',$schemeId)
            ->exists();

        $courseDetails = CourseDetail::whereHas('programmeCourse', function ($q) use ($programmeId) {

            $q->where('programme_id', $programmeId);

        })->exists();

        $classAward = ClassAwardConfiguration::where('programme_id', $programmeId)
            ->where('curriculum_year_id', $schemeId)
            ->exists();

        $semesterPlacement = SemesterPlacement::where('programme_id', $programmeId)
            ->where('curriculum_year_id', $schemeId)
            ->exists();

        return [

            'programmeLevel' => $programmeLevel,

            'courseDetails' => $courseDetails,

            'classAward' => $classAward,

            'semesterPlacement' => $semesterPlacement,

            'complete' => $programmeLevel &&
        $courseDetails &&
        $classAward &&
        $semesterPlacement,

        ];
    }
}
