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
            return redirect("/addclass");
        } else {
            return view('landing');
        }
    }

    public function dashboard()
    {
        return view('/addclass');
    }

    public function editProfile()
    {
        return view('editprofile');
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
