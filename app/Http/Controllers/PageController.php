<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

class PageController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct(Auth $auth, Database $database)
    {
        $this->auth = app('firebase.auth');
        $this->database = $database;

    }

    public function toHome() {
        return view('user/home');
    }

    public function toLogin() {
        return view('login');
    }

    public function toProfile() {
        return view('user/profile');
    }

    public function toConcessions() {
        return view('user/concessions');
    }

    public function toSaldo() {
        return view('user/saldo');
    }
    
    public function toDashboard() {
        return view('admin/dashboard/dashboard');
    }

    public function viewtickets() {
        return view('admin/history/tickets/history');
    }

    public function detailtickets() {
        return view('admin/history/tickets/details');
    }

    public function viewseatings() {
        return view('admin/history/seatings/history');
    }

    public function detailseatings() {
        return view('admin/history/seatings/details');
    }

    public function viewconcessions() {
        return view('admin/history/concessions/history');
    }

    public function detailconcessions() {
        return view('admin/history/concessions/details');
    }
}
