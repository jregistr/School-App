<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class ClassController extends Controller{
    protected $redirectTo = '/class';

    public function store(){
        $input = Input::only('section');
        $selected = strtoupper($input['section']);
        $courses = Course::where('name', 'like', "%$selected%")->get();

       /* foreach($courses as $course){
            $courses = $course->sectionsWithMeetings();
            //courses->name,crn,credits
          //  echo $courses;
            $sections = $courses->sections;
           // echo $sections;
            foreach($sections as $section){
               // echo $section;
            }
        }*/


       // echo $courses->sectionsWithMeetings();

        return View::make('selectclass')->with('classes', $courses);

        //echo $classes;
    }
}