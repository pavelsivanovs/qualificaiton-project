<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Throwable;

/**
 * @AuthController
 */
class AuthController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required'
        ]);

        /** @var User $user */
        $user = User::with('email', $request['email'])->first();

        if (!$user) {
            return redirect('/login')->with('error', 'E-pasta adrese vai parole ir nepareiza!');
        }

        if (!$user->isActive) {
            return redirect('/login')->with('error', 'Lietotāja konts ir izslēgts.');
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        return redirect('/login')->with('error', 'E-pasta adrese vai parole ir nepareiza!');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function logout()
    {
        try {
            Session::flush();
            Auth::logout();

            return redirect('/login');
        } catch (Throwable $exception) {
            return redirect('/register')->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string|max:30',
            'email' => 'required|email|max:254|unique:users',
            'password' => 'required|string|min:8|max:30|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'password_repeated' => 'required|same:password',
            'telephone_number' => 'required|string|min:10|max:15',
            'profile_picture' => 'nullable|string|max:255'
        ]);

        $user = new User();
        $user->name = $request['name'];
        $user->surname = $request['surname'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->telephoneNumber = $request['telephone_number'];
        $user->profilePicture = $request['profile_picture'];

        try {
            $user->saveOrFail();
        } catch (Throwable $e) {
            Log::error('Error creating user', $user->toArray());

            return redirect('/register')->with('error', 'Notikusi sistēmas kļūda. Lūdzu, mēģiniet vēlreiz!');
        }

        Mail::to($user)->send(new UserRegistered($user, $request['password']));

        return redirect('/login')->with('message', 'Lietotāja konts ir sekmīgi izveidots!');
    }
}
