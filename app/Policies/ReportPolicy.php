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
        if ($user->isTeacher() && $user->is_approved && $user->school && $report->nama_sekolah === $user->school) {
            return true;
        }
        return false;
    }

    public function updateStatus(User $user, Report $report)
    {
        // only approved teachers can update reports from their school
        if (! $user->isTeacher() || ! $user->is_approved) {
            return false;
        }

        if (! empty($user->school)) {
            return $report->nama_sekolah === $user->school;
        }

        return false;
    }
}
