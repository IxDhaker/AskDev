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

    use AuthenticatesUsers;

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // If this is an AJAX request, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => $this->redirectPath()
            ]);
        }

        // Otherwise, let the default behavior handle it
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        // Check if user is admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return route('admin.dashboard');
        }

        // Check if there's a redirect parameter in the request
        if (request()->has('redirect')) {
            return request()->get('redirect');
        }

        // Otherwise use the intended URL or default to home
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // If this is an AJAX request, return JSON error
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'errors' => [
                    $this->username() => [trans('auth.failed')]
                ]
            ], 422);
        }

        // Otherwise use the default behavior
        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

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
