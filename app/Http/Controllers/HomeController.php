<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); //只允许登录用户进入
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        echo 'start';
        var_dump(Auth::user());
        var_dump(Auth::id());
        var_dump($request->user());
        var_dump(Auth::check());
        exit;
        return view('home');
    }
}
