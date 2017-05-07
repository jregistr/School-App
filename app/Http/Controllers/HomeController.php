<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            return redirect("/schedules");
        } else {
            return view('activities.landing');
        }
    }

    public function schedules()
    {
        return view('activities.schedules');
    }

    public function overview()
    {
        return view('activities.overview');
    }

    public function create()
    {
        return view('activities.create');
    }

    public function editProfile()
    {
        return view('activities.editprofile');
    }

    public function addclass()
    {
        return view('addclass');
    }
    
    public function selectClass()
    {
        return view('selectclass');
    }

}
