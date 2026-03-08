<?php

namespace App\Policies;

use App\Models\ApplicationDocument;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationDocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, $doc): bool
    {
        // owner boleh view
        if ($doc->application->user_id === $user->id) {
            return true;
        }

        // role backoffice boleh view
        return $user->hasAnyRole(['urusetia','penyemak','pelulus','admin','pengurusan-atasan']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ApplicationDocument $applicationDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ApplicationDocument $applicationDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ApplicationDocument $applicationDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ApplicationDocument $applicationDocument): bool
    {
        return false;
    }
}
