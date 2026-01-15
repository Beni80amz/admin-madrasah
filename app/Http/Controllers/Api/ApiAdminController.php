<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiAdminController extends Controller
{
    // List Users (Searchable)
    public function getUsers(Request $request)
    {
        $query = User::query();

        // Search by name, email, or linked Teacher NIP
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($q) use ($search) {
                        $q->where('nip', 'like', "%{$search}%")
                            ->orWhere('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $users = $query->with(['teacher', 'student'])->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    // Reset Device ID
    public function resetDevice($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $user->device_id = null;
        $user->save();

        // Optional: Revoke tokens to force login again
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Perangkat berhasil direset. User dapat login kembali di perangkat baru.'
        ]);
    }
}
