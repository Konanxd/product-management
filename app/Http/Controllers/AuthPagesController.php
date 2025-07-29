<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthPagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function register()
    {
        return view('pages.auth.register');
    }

    public function login()
    {
        return view('pages.auth.login');
    }
}
