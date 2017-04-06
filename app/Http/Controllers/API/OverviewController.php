<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OverviewController extends Controller
{

    /**
     * OverviewController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function summary(Request $request)
    {
        return "HI";
    }

    public function getWeight(Request $request)
    {

    }

    public function addWeight(Request $request)
    {

    }

    public function updateWeight(Request $request)
    {

    }

    public function deleteWeight(Request $request)
    {
        
    }


}