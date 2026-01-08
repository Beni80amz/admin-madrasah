<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest; // Assuming model exists or to be created
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        return view('frontend.features.izin');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sakit,izin',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('leave_requests', 'public');
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'attachment' => $path,
            'status' => 'pending'
        ]);

        return redirect()->route('riwayat.index')->with('success', 'Permohonan Izin Berhasil Dikirim.');
    }
}
