<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admissions\Student;
use App\Models\Users\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import for using Auth facade

class LoginController extends Controller
{
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

    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string', // Use 'login' for flexibility
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $login = $request->input('email');

        $credentials = []; // Initialize empty credentials array

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            // check if user is student
            $exists = User::join('students', 'students.user_id', 'users.id')->where('email', $login)->exists();

            if($exists){
                // Redirect to 'staff' route if $exists is true
                return redirect()->route('login');
            }
            
            // Attempt login using email
            $credentials = [
                'email' => $login,
                'password' => $request->password, // Access password from request
            ];
        } else {
            // Attempt login using student_id
            $student = Student::where('id', $login)->first();
            if ($student) {
                $user = User::find($student->user_id);
                if ($user) {
                    $credentials = [
                        'email' => $user->email,
                        'password' => $request->password, // Access password from request
                    ];
                }
            }
        }

        // Attempt login using credentials array
        if (count($credentials) > 0) {
            return Auth::guard()->attempt($credentials, $request->filled('remember'));
        }

        return false; // Login failed
    }
}
