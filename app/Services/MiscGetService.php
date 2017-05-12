<?php

namespace App\Services;


use App\Models\School;
use App\Models\User;
use App\Util\C;
use function Psy\sh;

class MiscGetService
{

    public function getSchools()
    {
        return School::all();
    }

    public function addSchool($name, $country, $state, $city)
    {
        $school = School::create([C::NAME => $name, C::COUNTRY => $country, C::STATE => $state, C::CITY => $city]);
        return $school;
    }

    public function updateProfileCreateSchool($studentId, $first, $last, $year, $major, $sname, $country, $state, $city)
    {
        $school = School::where('name', $sname)->first();
        if ($school == null) {
            $school = $this->addSchool($sname, $country, $state, $city);
        }

        return $this->updateProfile($studentId, $first, $last, $year, $major, $school->id);
    }

    public function updateProfile($studentId, $first, $last, $year, $major, $schoolId)
    {
        $user = User::find($studentId);

        if ($first != null) {
            $user->first_name = $first;
        }

        if ($last != null) {
            $user->last_name = $last;
        }

        if ($year != null) {
            $user->year = $year;
        }

        if ($major != null) {
            $user->major = $major;
        }

        if ($schoolId != null && $schoolId >= 1) {
            $user->school_id = $schoolId;
        }

        error_log($schoolId);

        $user->save();

        return $user;
    }

}