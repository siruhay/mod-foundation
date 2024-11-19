<?php

namespace Module\Foundation\Policies;

use Module\System\Models\SystemUser;
use Module\Foundation\Models\FoundationMember;
use Illuminate\Auth\Access\Response;

class FoundationMemberPolicy
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
        return $user->hasPermission('view-foundation-member');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, FoundationMember $foundationMember): bool
    {
        return $user->hasPermission('show-foundation-member');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-foundation-member');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, FoundationMember $foundationMember): bool
    {
        return $user->hasPermission('update-foundation-member');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, FoundationMember $foundationMember): bool
    {
        return $user->hasPermission('delete-foundation-member');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, FoundationMember $foundationMember): bool
    {
        return $user->hasPermission('restore-foundation-member');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, FoundationMember $foundationMember): bool
    {
        return $user->hasPermission('destroy-foundation-member');
    }
}
