<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiLeaveRequestController extends Controller
{
    protected $leaveService;

    public function __construct(LeaveRequestService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    /**
     * Get user's leave requests
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'type' => $leave->type,
                    'start_date' => $leave->start_date,
                    'end_date' => $leave->end_date,
                    'reason' => $leave->reason,
                    'status' => $leave->status,
                    'attachment' => $leave->attachment ? asset('storage/' . $leave->attachment) : null,
                    'approved_by' => $leave->approvedBy ? $leave->approvedBy->name : null,
                    'approved_at' => $leave->approved_at,
                    'rejection_note' => $leave->rejection_note,
                    'created_at' => $leave->created_at,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'leave_requests' => $leaveRequests,
            ],
        ]);
    }

    /**
     * Store a new leave request
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sakit,izin',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment_base64' => 'nullable|string',
        ]);

        try {
            $data = $request->all();

            // Handle base64 image attachment
            if ($request->input('attachment_base64')) {
                $image = $request->input('attachment_base64');
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'leave_' . time() . '_' . $request->user()->id . '.jpg';
                $imagePath = 'leave-attachments/' . $imageName;
                Storage::disk('public')->put($imagePath, base64_decode($image));
                $data['attachment'] = $imagePath;
            }

            $this->leaveService->create($request->user(), $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan izin berhasil dikirim.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim pengajuan: ' . $e->getMessage(),
            ], 400);
        }
    }
}
