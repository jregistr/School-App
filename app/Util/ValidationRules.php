<?php

namespace App\Util;


class ValidationRules
{

    public $gradeRules = array(
        'weight_id' =>  'bail|required|integer',
        'student_id' => 'bail|required|integer',
        'assignment' => 'bail|required|alpha_num',
        'grade' =>      'bail|required|between:0.00,100.00'
    );

    public $weightRules = array(
        'student_id' => 'required|integer',
        'section_id' => 'required|integer',
        'category' => 'required|alpha|max:191',
        'points' => 'required|between:0.00,100.00'
    );


    public $schoolRules = array(
        'name' => 'required|max:191',
        'country' => 'max:3',
        'state' => 'max:2',
        'city' => 'max:191'
    );

    public $sectionRules = array(
        'class_id' => 'required|integer',
        'start_time' => 'required|date_format:"HH:MM"|before:end_time',
        'end_time' => 'required|date_format:"HH:MM"|after:start_time',
        'days' => 'required|max:191',
        'professor' => 'max:191',
        'building' => 'integer',
        'room_number' => 'integer'
    );

}