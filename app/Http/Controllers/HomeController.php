<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
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
    public function index(Request $request)
    {
        $user = User::find($request->user()->id);
        return view('home', compact('user'));
    }

    public function showProfile(Request $request, User $user = null)
    {
        return view('home', [
            'user' => $user ?? User::find($request->user()->id)
        ]);
    }

    public function showProfileForm(Request $request)
    {
        $user = User::find($request->user()->id);

        return view('profile.showForm', compact('user'));
    }

    public function updateProfile(Request $request, User $user)
    {
        $user = $request->user();

        $this->validate($request, [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'login' => 'required|email|string|unique:users,login,' . $user->id,
            'password' => 'required_with:password_confirmation|nullable|string|min:4|confirmed',
            'current_password' => ['required_with:password', 'nullable', function ($attribute, $value, $fail) use ($user) {
                if ($value && !password_verify($value, $user->mdp)) {
                    $fail('Old password didn\'t match');
                }
            }],
        ]);

        $data = $request->all();

        if (isset($data['password']) && $data['password']) {
            $data['mdp'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if ($user->update($data)) {

            return redirect()->route('home')->with(['success' => 'Votre profil a bien modifie']);
        }

        return redirect()->back()->with(['error' => 'Unexpected error while updating data']);
    }
}
