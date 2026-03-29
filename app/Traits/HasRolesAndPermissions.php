<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRolesAndPermissions
{
    private ?array $cachedPermissions = null;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->orWhere('id', $role)->first();
        } elseif (is_numeric($role)) {
            $role = Role::find($role);
        }

        if (!$role) return;

        // Single role per user — sync replaces existing
        $this->roles()->sync([$role->id]);

        // Keep legacy column in sync
        $this->update(['role' => $role->slug]);

        // Clear cached permissions
        $this->cachedPermissions = null;
    }

    public function removeRole(): void
    {
        $this->roles()->detach();
        $this->update(['role' => 'learner']);
        $this->cachedPermissions = null;
    }

    public function hasPermissionTo(string $slug): bool
    {
        // SuperAdmin always bypasses
        if ($this->role === 'superadmin') {
            return true;
        }

        if ($this->cachedPermissions === null) {
            $this->cachedPermissions = $this->roles()
                ->with('permissions')
                ->get()
                ->pluck('permissions')
                ->flatten()
                ->pluck('slug')
                ->unique()
                ->toArray();
        }

        return in_array($slug, $this->cachedPermissions);
    }

    public function hasAnyPermission(array $slugs): bool
    {
        foreach ($slugs as $slug) {
            if ($this->hasPermissionTo($slug)) {
                return true;
            }
        }
        return false;
    }

    public function getPrimaryRole(): ?Role
    {
        return $this->roles->first();
    }
}
