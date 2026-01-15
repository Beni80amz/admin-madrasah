<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'required|string',
        ]);

        if (is_numeric($request->email)) {
            // Priority 1: Check Teacher Table by NIP
            $teacherByNip = Teacher::where('nip', $request->email)->first();
            if ($teacherByNip && $teacherByNip->user_id) {
                $user = User::find($teacherByNip->user_id);
            }

            // Priority 2: Check Student Table by NIS
            if (!isset($user)) {
                $studentByNis = Student::where('nis_lokal', $request->email)->first();
                if ($studentByNis && $studentByNis->user_id) {
                    $user = User::find($studentByNis->user_id);
                }
            }

            // Priority 3: Try suffixes (fallback)
            if (!isset($user)) {
                $user = User::where('email', $request->email)
                    ->orWhere('email', $request->email . '@teacher.com')
                    ->orWhere('email', $request->email . '@student.com')
                    ->orWhere('email', $request->email . '@madrasah.sch.id')
                    ->first();
            }
        } else {
            $user = User::where('email', $request->email)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak sesuai.'],
            ]);
        }

        // Revoke all old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken($request->device_name)->plainTextToken;

        // Get user profile data
        $teacher = Teacher::where('user_id', $user->id)->first();
        $student = Student::where('user_id', $user->id)->first();

        $profileData = null;
        $userType = 'user';

        if ($teacher) {
            $userType = 'teacher';
            $profileData = [
                'id' => $teacher->id,
                'nama_lengkap' => $teacher->nama_lengkap,
                'nip' => $teacher->nip,
                'nuptk' => $teacher->nuptk,
                'jabatan' => $teacher->jabatan,
                'photo' => $teacher->photo ? asset('storage/' . $teacher->photo) : null,
            ];
        } elseif ($student) {
            $userType = 'student';
            $profileData = [
                'id' => $student->id,
                'nama_lengkap' => $student->nama_lengkap,
                'nis_lokal' => $student->nis_lokal,
                'nisn' => $student->nisn,
                'kelas' => $student->kelas,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'user_type' => $userType,
                'profile' => $profileData,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        $student = Student::where('user_id', $user->id)->first();

        $profileData = null;
        $userType = 'user';

        if ($teacher) {
            $userType = 'teacher';
            $profileData = [
                'id' => $teacher->id,
                'nama_lengkap' => $teacher->nama_lengkap,
                'nip' => $teacher->nip,
                'nuptk' => $teacher->nuptk,
                'jabatan' => $teacher->jabatan,
                'photo' => $teacher->photo ? asset('storage/' . $teacher->photo) : null,
            ];
        } elseif ($student) {
            $userType = 'student';
            $profileData = [
                'id' => $student->id,
                'nama_lengkap' => $student->nama_lengkap,
                'nis_lokal' => $student->nis_lokal,
                'nisn' => $student->nisn,
                'kelas' => $student->kelas,
                'photo' => $student->photo ? asset('storage/' . $student->photo) : null,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'user_type' => $userType,
                'profile' => $profileData,
            ],
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil',
        ]);
    }
}
