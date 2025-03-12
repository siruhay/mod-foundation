<?php

namespace Module\Foundation\Policies;

use Module\System\Models\SystemUser;
use Module\Foundation\Models\FoundationBiodata;
use Illuminate\Auth\Access\Response;

class FoundationBiodataPolicy
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
        return $user->hasPermission('view-foundation-biodata');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, FoundationBiodata $foundationBiodata): bool
    {
        return $user->hasPermission('show-foundation-biodata');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-foundation-biodata');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, FoundationBiodata $foundationBiodata): bool
    {
        return $user->hasPermission('update-foundation-biodata');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, FoundationBiodata $foundationBiodata): bool
    {
        return $user->hasPermission('delete-foundation-biodata');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, FoundationBiodata $foundationBiodata): bool
    {
        return $user->hasPermission('restore-foundation-biodata');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, FoundationBiodata $foundationBiodata): bool
    {
        return $user->hasPermission('destroy-foundation-biodata');
    }
}
