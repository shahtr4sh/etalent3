<?php

namespace App\Policies;

use App\Models\ApplicationDocument;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationDocumentPolicy
{
    public function view($user, $doc): bool
    {
        // owner boleh view
        if ($doc->application->user_id === $user->id) {
            return true;
        }

        // role backoffice boleh view
        return $user->hasAnyRole(['urusetia','penyemak','pelulus','admin','pengurusan-atasan']);
    }
}
