<?php

namespace Module\Foundation\Policies;

use Module\System\Models\SystemUser;
use Module\Foundation\Models\FoundationPosmap;
use Illuminate\Auth\Access\Response;

class FoundationPosmapPolicy
{
    /**
    * Perform pre-authorization checks.
    */
    public function before(SystemUser $user, string $ability): bool|null
    {
        if ($user->hasLicenseAs('foundation-superadmin')) {
            return true;
        }
    
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function view(SystemUser $user): bool
    {
        return $user->hasPermission('view-foundation-posmap');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, FoundationPosmap $foundationPosmap): bool
    {
        return $user->hasPermission('show-foundation-posmap');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-foundation-posmap');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, FoundationPosmap $foundationPosmap): bool
    {
        return $user->hasPermission('update-foundation-posmap');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, FoundationPosmap $foundationPosmap): bool
    {
        return $user->hasPermission('delete-foundation-posmap');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, FoundationPosmap $foundationPosmap): bool
    {
        return $user->hasPermission('restore-foundation-posmap');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, FoundationPosmap $foundationPosmap): bool
    {
        return $user->hasPermission('destroy-foundation-posmap');
    }
}
