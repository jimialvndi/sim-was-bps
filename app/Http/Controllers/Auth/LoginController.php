<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    protected function authenticated(Request $request, $user)
    {
        if ($user->role == 'admin' || $user->role == 'ketua') {
            // Admin (Ketua Tim) & Viewer (Kepala BPS) masuk ke Dashboard Monitoring
            return redirect()->route('dashboard.admin');
        } 
        
        // User Biasa (Pengawas Lapangan) masuk ke Dashboard Input
        return redirect()->route('dashboard.pengawas');
    }

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
