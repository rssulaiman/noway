<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
    //    protected $redirectTo = RouteServiceProvider::HOME;

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return [
            'login' => $request->email,
            'password' => $request->password
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->type) {
            auth()->logout();
            return redirect()->route('login')->with(['error' => 'Votre compte n\'a pas encore ete confirme']);
        }
        $data = ['success' => 'Bienvenue'];
        if ($user->isAdmin) {
            return redirect()->route('admin.index')->with($data);
        }
        if ($user->isEnseignant) {
            return redirect()->route('enseignants.index')->with($data);
        }
        if ($user->isEtudiant) {
            return redirect()->route('etudiants.index')->with($data);
        }

        return redirect()->route('home')->with($data);
    }
}
