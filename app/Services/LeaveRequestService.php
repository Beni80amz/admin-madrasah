<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class LeaveRequestService
{
    /**
     * Create a new leave request.
     */
    public function create(User $user, array $data): LeaveRequest
    {
        // Handle File Upload
        $attachmentPath = null;
        if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
            $attachmentPath = $data['attachment']->store('leave_attachments', 'public');
        } elseif (isset($data['attachment_base64'])) {
            // Handle base64 from camera if sent as string
            $image = $data['attachment_base64'];
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'leave_' . time() . '_' . $user->id . '.jpg';
            $attachmentPath = 'leave_attachments/' . $imageName;
            Storage::disk('public')->put($attachmentPath, base64_decode($image));
        }

        return LeaveRequest::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);
    }

    /**
     * Approve a leave request.
     */
    public function approve(LeaveRequest $request, User $approver): bool
    {
        // Add logic here to verify if $approver has right to approve $request
        // e.g., if Student, approver must be Wali Kelas
        // e.g., if Teacher, approver must be Superadmin/TU

        $request->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
        ]);

        return true;
    }

    /**
     * Reject a leave request.
     */
    public function reject(LeaveRequest $request, User $approver, string $note): bool
    {
        $request->update([
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'rejection_note' => $note,
        ]);

        return true;
    }

    /**
     * Get pending requests for the approver.
     */
    public function getPendingRequestsFor(User $user)
    {
        // 1. Check if User is a Teacher (potential Wali Kelas)
        $teacher = Teacher::where('user_id', $user->id)->first();
        if ($teacher) {
            // Check if Wali Kelas
            $rombel = $teacher->rombelWaliKelas;
            if ($rombel) {
                // Return requests from students in this rombel
                $studentUserIds = \App\Models\Student::where('kelas_id', $rombel->kelas_id) // Assuming simple relation or use rombel->students relation
                    // Better: Get students via Rombel relation
                    ->where('rombel_id', $rombel->id)
                    ->whereNotNull('user_id')
                    ->pluck('user_id');

                return LeaveRequest::whereIn('user_id', $studentUserIds)
                    ->where('status', 'pending')
                    ->get();
            }
        }

        // 2. Check if User is Superadmin/Staf TU (approves Teachers)
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin PPDB')) {
            return LeaveRequest::whereHas('user', function ($q) {
                $q->role('Guru');
            })
                ->where('status', 'pending')
                ->get();
        }

        return collect([]);
    }
}
