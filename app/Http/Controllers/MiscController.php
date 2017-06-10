<?php

namespace App\Http\Controllers;


use App\Services\MiscGetService;
use App\Util\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MiscController extends Controller
{

    private $service;

    /**
     * MiscController constructor.
     * @param MiscGetService $service
     */
    public function __construct(MiscGetService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            C::STUDENT_ID => 'required|integer',
            'first' => 'sometimes|max:100',
            'last' => 'sometimes|max:100',
            'year' => 'sometimes',
            'major' => 'sometimes|max:100',
            'school' => 'max:100'
        ];

        $have = $request->input('school');

        if ($have == null || $have == -2) {
            $rules['name'] = 'required';
            $rules['school_country'] = 'required';
            $rules['school_state'] = 'required';
            $rules['school_city'] = 'required';
        }

        error_log($request->input('school_country'));
        error_log($request->input('school_state'));
        error_log($request->input('school_city'));
        error_log($request->input('school_name'));

        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        } else {
            if ($have != null && $have == 1) {
                $this->service->updateProfile(
                    $request->input(C::STUDENT_ID),
                    $request->input('first'),
                    $request->input('last'),
                    $request->input('year'),
                    $request->input('major'),
                    intval($request->input('school'))
                );
            } else {
                $this->service->updateProfileCreateSchool(
                    $request->input(C::STUDENT_ID),
                    $request->input('first'),
                    $request->input('last'),
                    $request->input('year'),
                    $request->input('major'),
                    $request->input('name'),
                    $request->input('school_country'),
                    $request->input('school_state'),
                    $request->input('school_city')
                );
            }
            return redirect()->back();
        }
    }

}