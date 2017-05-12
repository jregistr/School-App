<?php

namespace App\Http\Controllers;

use App\Services\MiscGetService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     * @param MiscGetService $service
     */
    public function __construct(MiscGetService $service)
    {
        $this->middleware('auth', ['except' => 'index']);
        $this->service = $service;
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
        return view('activities.schedules', ['user' => Auth::user()]);
    }

    public function overview()
    {
        return view('activities.overview', ['user' => Auth::user()]);
    }

    public function create()
    {
        return view('activities.create', ['user' => Auth::user()]);
    }

    public function editProfile()
    {
        return view('activities.editprofile', ['user' => Auth::user(), 'schools' => $this->service->getSchools()]);
    }

    public function addclass()
    {
        return view('addclass', ['user' => Auth::user()]);
    }

    public function selectClass()
    {
        return view('selectclass', ['user' => Auth::user()]);
    }

}
