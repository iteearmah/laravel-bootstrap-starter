<?php

namespace App\Http\Controllers;

use App\Facades\Helper;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->hasRole(Helper::superAdminRoleName()))
        {
            $totalUsers = User::query()->count();
        }
        else
        {
            $totalUsers = auth()->user()?->teamMembers()->count();
        }
        $totalTeams = Team::query()->get()->count();

        return view('dashboard', compact('totalUsers', 'totalTeams'));
    }
}
