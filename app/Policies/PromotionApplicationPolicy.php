<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PromotionApplication;
use Illuminate\Auth\Access\HandlesAuthorization;

class PromotionApplicationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PromotionApplication');
    }

    public function view(AuthUser $authUser, PromotionApplication $promotionApplication): bool
    {
        return $authUser->can('View:PromotionApplication');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PromotionApplication');
    }

    public function update(AuthUser $authUser, PromotionApplication $promotionApplication): bool
    {
        return $authUser->can('Update:PromotionApplication');
    }

    public function delete(AuthUser $authUser, PromotionApplication $promotionApplication): bool
    {
        return $authUser->can('Delete:PromotionApplication');
    }

    public function restore(AuthUser $authUser, PromotionApplication $promotionApplication): bool
    {
        return $authUser->can('Restore:PromotionApplication');
    }

    public function forceDelete(AuthUser $authUser, PromotionApplication $promotionApplication): bool
    {
        return $authUser->can('ForceDelete:PromotionApplication');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PromotionApplication');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PromotionApplication');
    }

    public function replicate(AuthUser $authUser, PromotionApplication $promotionApplication): bool
    {
        return $authUser->can('Replicate:PromotionApplication');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PromotionApplication');
    }

}