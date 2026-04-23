<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Report $report)
    {
        // teachers can view their assigned reports; admins can view all
        if ($user->isAdmin()) return true;
        if ($user->isTeacher() && $user->approval_status === 'approved' && $user->school && $report->nama_sekolah === $user->school) {
            return true;
        }
        return false;
    }

    public function updateStatus(User $user, Report $report)
    {
        // only approved teachers can update reports from their school
        if (! $user->isTeacher() || $user->approval_status !== 'approved') {
            return false;
        }

        if (! empty($user->school) && $report->nama_sekolah !== $user->school) {
            return false;
        }

        // laporan harus sudah diklaim oleh guru ini dulu
        if (! $report->claimed_by || $report->claimed_by !== $user->id) {
            return false;
        }

        return true;
    }
}
