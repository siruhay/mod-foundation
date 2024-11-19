<?php

namespace Module\Foundation\Policies;

use Module\System\Models\SystemUser;
use Module\Foundation\Models\FoundationSubdistrict;
use Illuminate\Auth\Access\Response;

class FoundationSubdistrictPolicy
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
        return $user->hasPermission('view-foundation-subdistrict');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, FoundationSubdistrict $foundationSubdistrict): bool
    {
        return $user->hasPermission('show-foundation-subdistrict');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-foundation-subdistrict');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, FoundationSubdistrict $foundationSubdistrict): bool
    {
        return $user->hasPermission('update-foundation-subdistrict');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, FoundationSubdistrict $foundationSubdistrict): bool
    {
        return $user->hasPermission('delete-foundation-subdistrict');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, FoundationSubdistrict $foundationSubdistrict): bool
    {
        return $user->hasPermission('restore-foundation-subdistrict');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, FoundationSubdistrict $foundationSubdistrict): bool
    {
        return $user->hasPermission('destroy-foundation-subdistrict');
    }
}
