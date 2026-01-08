<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'device_id' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $deviceId = $request->input('device_id');

        // 1. Find User by NIS (Student) or NIP (Teacher)
        $user = $this->findUserByNisOrNip($username);

        if (!$user) {
            // Fallback: Try to login with Name or Email directly if mapped
            $user = User::where('name', $username)->orWhere('email', $username)->first();
        }

        // 2. Validate Password
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Kombinasi NIS/NIP dan Password tidak cocok.'],
            ]);
        }

        // 3. Device Binding Check
        if ($user->device_id) {
            if ($user->device_id !== $deviceId) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'device_id' => ['Akun ini terkunci pada perangkat lain. Hubungi admin untuk reset.'],
                ]);
            }
        } else {
            // First time login, bind device
            $user->update(['device_id' => $deviceId]);
        }

        // 4. Authenticate
        Auth::login($user);

        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function findUserByNisOrNip($username)
    {
        // Check Student by NIS Lokal or NISN
        $student = Student::where('nis_lokal', $username)
            ->orWhere('nisn', $username)
            ->first();

        if ($student && $student->user_id) {
            return User::find($student->user_id);
        }

        // Check Teacher by NIP
        $teacher = Teacher::where('nip', $username)->first();

        if ($teacher && $teacher->user_id) {
            return User::find($teacher->user_id);
        }

        return null;
    }
}
