<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/admin/login';

    public function __construct()
    {
        //  $this->middleware('guest:web')->only(['showLoginForm', 'userLogin']);
        // $this->middleware('guest:admin')->only(['adminLogin', 'showAdminLoginForm']);
    }


    
    /* =======================
       ADMIN LOGIN (admins table)
       ======================= */
    
    public function showAdminLoginForm()
    {   
        // exit;
        if(auth('admin')->check()){
            return redirect('admin/dashboard');
        }
        return view('auth.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required',
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return response()->json([
                'error' => 'invalid',
                'message' => 'Invalid admin credentials.',
            ]);
        }

        $admin = Auth::guard('admin')->user();

        return $this->checkStatusAndRespond($admin, 'admin');
    }

    /* =======================
       STATUS CHECK (Common)
       ======================= */
    protected function checkStatusAndRespond($user, $guard)
    {
        return match ($user->status) {
            'active' => response()->json([
                'success' => true,
                'message' => 'Login successful.',
            ]),

            'inactive' => $this->logoutWithError($guard, 'deactivated', 'Your account is deactivated.'),
            'pending'  => $this->logoutWithError($guard, 'pending', 'Your account is not verified.'),
            'trashed'  => $this->logoutWithError($guard, 'trashed', 'Your account has been deleted.'),

            default => $this->logoutWithError($guard, 'unknown', 'Unknown account status.'),
        };
    }

    protected function logoutWithError($guard, $error, $message)
    {
        Auth::guard($guard)->logout();

        return response()->json([
            'error' => $error,
            'message' => $message,
        ]);
    }

    public function adminLogout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        return redirect('/admin/login');
    }

    

    /* =======================
       Guest LOGIN (users table)
       ======================= */
    public function showGuestLoginForm()
    {   
        // exit;
        if(auth()->check()){
            return redirect('/');
        }
        return view('frontend.login');
    }

    
    public function guestLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => 'invalid',
                'message' => 'Invalid email or password.',
            ]);
        }

        $user = Auth::user();
        return $this->checkStatusAndRespond($user, 'web');
    }

    public function guestLogout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        return redirect('/');
    }
}
