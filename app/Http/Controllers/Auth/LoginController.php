<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    }

    public function login(Request $request)
    {
        $error = false;
        $mensaje = '';

        $val_email = User::where('email', $request->email)->first();
        if ($val_email) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                $error = false;
                $mensaje = 'SI';
            } else {

                $error = true;
                $mensaje = 'Error! No coincide el correo ni la contraseña ingresada, intente de nuevo.';

            }

        } else {
            $error = true;
            $mensaje = 'Error! No existe ningun usuario registrado con este correo.';
        }

        echo json_encode(array('error' => $error, 'mensaje' => $mensaje));
    }
}
