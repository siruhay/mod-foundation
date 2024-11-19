<?php

namespace Module\Foundation\Policies;

use Module\System\Models\SystemUser;
use Module\Foundation\Models\FoundationCommunity;
use Illuminate\Auth\Access\Response;

class FoundationCommunityPolicy
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
        return $user->hasPermission('view-foundation-community');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, FoundationCommunity $foundationCommunity): bool
    {
        return $user->hasPermission('show-foundation-community');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-foundation-community');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, FoundationCommunity $foundationCommunity): bool
    {
        return $user->hasPermission('update-foundation-community');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, FoundationCommunity $foundationCommunity): bool
    {
        return $user->hasPermission('delete-foundation-community');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, FoundationCommunity $foundationCommunity): bool
    {
        return $user->hasPermission('restore-foundation-community');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, FoundationCommunity $foundationCommunity): bool
    {
        return $user->hasPermission('destroy-foundation-community');
    }
}
