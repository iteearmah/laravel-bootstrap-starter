<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    // set team_id on login

    protected function authenticated($request, $user)
    : RedirectResponse {
        // redirect to select team page if user has more than one team
        if ($user->teams()->count() > 1)
        {
            return redirect()->route('teams.select-team');
        }
        $userOnlyTeam = $user->teams()->first();
        session()->put('team_id', $userOnlyTeam['id']);
        setPermissionsTeamId($userOnlyTeam);

        return redirect()->route('dashboard');
    }

    protected function validateLogin(Request $request)
    {
        // validate email or username
        $request->validate([
            'email' => 'required|string',
            'username' => 'nullable|string|exists:users,username',
            'password' => 'required|string',
        ]);
    }

    public function findUsername()
    {

        $login = request()?->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()?->merge([$fieldType => $login]);
        return $fieldType;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */

    public function username()
    {
        return $this->username;
    }
}
