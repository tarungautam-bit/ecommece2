<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
       

       
        return view('auth.register');
    }


    public static function generate_captcha($length = 6)
    {
        // Generate random code
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        $time  = strtotime('+10 seconds');

        // Store in session
        session([
            'captcha_code' => $code,
            'expiring_time' =>$time,
        ]);


        // Create simple SVG image
        $svg = '
        <svg xmlns="http://www.w3.org/2000/svg" width="160" height="50">
            <rect width="100%" height="100%" fill="#f2f2f2"/>
            <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
                  font-size="24" font-family="Arial" fill="#000">
                '.$code.'
            </text>
        </svg>';

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    // public function register(Request $request)
    // {

    
    //     $this->validator($request->all())->validate();

    //     $user = $this->create($request->all());

    //     auth()->login($user);

    //     return redirect()->route('home');
    // }

    // public function register(Request $request)
    // {
    //     // 1️⃣ Validate normal fields
    //     $this->validator($request->all())->validate();

    //     // 2️⃣ Validate captcha
    //    if (
    //         !$request->has('captcha') ||
    //         !session()->has('captcha_code') ||
    //         !session()->has('expiring_time') ||
    //         time() > session('expiring_time') ||   // expired check
    //         strtolower(trim(session('captcha_code'))) !== strtolower(trim($request->captcha))
    //     ) {


    //         return back()
    //             ->withErrors(['captcha' => 'Invalid Captcha'])
    //             ->withInput();
    //     }

    //     // 3️⃣ Create user
    //     $user = $this->create($request->all());

    //     auth()->login($user);

    //     return redirect()->route('home');
    // }

    public function register(Request $request)
{
    // 1️⃣ Validate normal fields
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // 2️⃣ Validate captcha
    if (
        !$request->has('captcha') ||
        !session()->has('captcha_code') ||
        !session()->has('expiring_time') ||
        time() > session('expiring_time') ||
        strtolower(trim(session('captcha_code'))) !== strtolower(trim($request->captcha))
    ) {
        return response()->json([
            'status' => false,
            'errors' => [
                'captcha' => ['Invalid or Expired Captcha']
            ]
        ], 422);
    }

    // 3️⃣ Create user
    $user = $this->create($request->all());

    auth()->login($user);

    session()->forget(['captcha_code', 'expiring_time']);

    return response()->json([
        'status' => true,
        'message' => 'Registration successful',
        'redirect' => route('home')
    ], 200);
}


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
