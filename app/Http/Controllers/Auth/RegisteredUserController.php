<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Log registration attempt
        Log::info('Registration attempt', ['email' => $request->email]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                'min:8', // Minimum length
            ],
            'captcha' => ['required', 'captcha'],
        ]);

        Log::info('Validation passed');

        // Check password strength
        $password = $request->password;
        $strength = 0;
        if (strlen($password) >= 8) $strength++;
        if (preg_match('/[A-Z]/', $password)) $strength++;
        if (preg_match('/[0-9]/', $password)) $strength++;
        if (preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) $strength++;

        if ($strength < 3) { // Accept only medium or strong passwords
            Log::info('Password too weak', ['strength' => $strength]);
            return back()->withErrors(['password' => 'The password is too weak. Please choose a stronger password.']);
        }

        Log::info('Creating user');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'user', // Set default usertype
            'bio' => null, // Set bio to blank
            'avatar' => 'img/default-dp.jpg', // Set default avatar
        ]);

        Log::info('User created', ['user_id' => $user->id]);

        event(new Registered($user));

        Log::info('Event fired');

        Auth::login($user);

        Log::info('User logged in, redirecting to verification.notice');

        return redirect()->route('verification.notice')->with('email', $request->email);
    }

    protected function showRegistrationForm()
{
    $captcha = [
        'num1' => rand(1, 10),
        'num2' => rand(1, 10),
    ];
    session(['captcha_result' => $captcha['num1'] + $captcha['num2']]);
    return view('auth.register', compact('captcha'));
}

protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[A-Z]/', // At least one uppercase letter
            'regex:/[a-z]/', // At least one lowercase letter
            'regex:/[0-9]/', // At least one number
            'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least one special character
            'not_in:' . $data['name'] . ',' . $data['email'], // Not the same as name or email
            'confirmed',
        ],
        'captcha' => [
            'required',
            function ($attribute, $value, $fail) {
                if ((int)$value !== session('captcha_result')) {
                    $fail('The captcha answer is incorrect.');
                }
            },
        ],
    ]);
}



}
