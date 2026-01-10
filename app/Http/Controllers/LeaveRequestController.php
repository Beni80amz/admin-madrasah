<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    protected $leaveService;

    public function __construct(LeaveRequestService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function index()
    {
        $user = Auth::user();

        // 1. My Requests History
        $myRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Pending Approvals (if eligible)
        $pendingApprovals = $this->leaveService->getPendingRequestsFor($user);

        return view('frontend.leave.index', compact('myRequests', 'pendingApprovals'));
    }

    public function create()
    {
        return view('frontend.leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sakit,izin',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment_base64' => 'nullable|string', // Prioritize base64 from camera
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // Fallback
        ]);

        try {
            $this->leaveService->create(Auth::user(), $request->all());
            return redirect()->route('leave.index')->with('success', 'Pengajuan berhasil dikirim.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim pengajuan: ' . $e->getMessage());
        }
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        // Add authorization check here if strictly needed
        $this->leaveService->approve($leaveRequest, Auth::user());
        return back()->with('success', 'Pengajuan disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['rejection_note' => 'required|string']);
        $this->leaveService->reject($leaveRequest, Auth::user(), $request->rejection_note);
        return back()->with('success', 'Pengajuan ditolak.');
    }
}
