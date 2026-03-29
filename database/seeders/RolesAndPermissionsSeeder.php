<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superadmin = Role::firstOrCreate(['slug' => 'superadmin'], ['name' => 'Super Admin', 'description' => 'Full platform access']);
        $admin = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'description' => 'Platform administration']);
        $creator = Role::firstOrCreate(['slug' => 'creator'], ['name' => 'Creator', 'description' => 'Content creator']);
        $learner = Role::firstOrCreate(['slug' => 'learner'], ['name' => 'Learner', 'description' => 'Default user role', 'is_default' => true]);

        // Define permissions by group
        $permissionGroups = [
            'Users' => [
                ['slug' => 'view_users', 'name' => 'View Users'],
                ['slug' => 'create_users', 'name' => 'Create Users'],
                ['slug' => 'edit_users', 'name' => 'Edit Users'],
                ['slug' => 'delete_users', 'name' => 'Delete Users'],
                ['slug' => 'impersonate_users', 'name' => 'Impersonate Users'],
            ],
            'Cohorts' => [
                ['slug' => 'view_cohorts', 'name' => 'View Cohorts'],
                ['slug' => 'create_cohorts', 'name' => 'Create Cohorts'],
                ['slug' => 'edit_cohorts', 'name' => 'Edit Cohorts'],
                ['slug' => 'delete_cohorts', 'name' => 'Delete Cohorts'],
                ['slug' => 'approve_cohorts', 'name' => 'Approve Cohorts'],
            ],
            'Products' => [
                ['slug' => 'view_products', 'name' => 'View Products'],
                ['slug' => 'create_products', 'name' => 'Create Products'],
                ['slug' => 'edit_products', 'name' => 'Edit Products'],
                ['slug' => 'delete_products', 'name' => 'Delete Products'],
                ['slug' => 'approve_products', 'name' => 'Approve Products'],
            ],
            'Memberships' => [
                ['slug' => 'view_memberships', 'name' => 'View Memberships'],
                ['slug' => 'approve_memberships', 'name' => 'Approve Memberships'],
            ],
            'Coaching' => [
                ['slug' => 'view_coaching', 'name' => 'View Coaching'],
                ['slug' => 'approve_coaching', 'name' => 'Approve Coaching'],
            ],
            'Payments' => [
                ['slug' => 'view_payments', 'name' => 'View Payments'],
                ['slug' => 'approve_payments', 'name' => 'Approve Payments'],
                ['slug' => 'reject_payments', 'name' => 'Reject Payments'],
            ],
            'Withdrawals' => [
                ['slug' => 'view_withdrawals', 'name' => 'View Withdrawals'],
                ['slug' => 'approve_withdrawals', 'name' => 'Approve Withdrawals'],
                ['slug' => 'reject_withdrawals', 'name' => 'Reject Withdrawals'],
            ],
            'Reviews' => [
                ['slug' => 'view_reviews', 'name' => 'View Reviews'],
                ['slug' => 'approve_reviews', 'name' => 'Approve Reviews'],
                ['slug' => 'delete_reviews', 'name' => 'Delete Reviews'],
            ],
            'Coupons' => [
                ['slug' => 'view_coupons', 'name' => 'View Coupons'],
                ['slug' => 'create_coupons', 'name' => 'Create Coupons'],
                ['slug' => 'edit_coupons', 'name' => 'Edit Coupons'],
                ['slug' => 'delete_coupons', 'name' => 'Delete Coupons'],
            ],
            'Reports' => [
                ['slug' => 'view_reports', 'name' => 'View Reports'],
            ],
            'Settings' => [
                ['slug' => 'manage_settings', 'name' => 'Manage Settings'],
            ],
            'Creator Applications' => [
                ['slug' => 'view_applications', 'name' => 'View Applications'],
                ['slug' => 'approve_applications', 'name' => 'Approve Applications'],
                ['slug' => 'reject_applications', 'name' => 'Reject Applications'],
            ],
            'Affiliates' => [
                ['slug' => 'view_affiliates', 'name' => 'View Affiliates'],
            ],
        ];

        // Create all permissions
        $allPermissionIds = [];
        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $perm) {
                $p = Permission::firstOrCreate(
                    ['slug' => $perm['slug']],
                    ['name' => $perm['name'], 'group' => $group]
                );
                $allPermissionIds[] = $p->id;
            }
        }

        // Assign all permissions to Admin role
        $admin->permissions()->sync($allPermissionIds);

        // Migrate existing users to role_user pivot
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                if ($user->roles()->count() === 0) {
                    $slug = $user->role ?? 'learner';
                    $role = Role::where('slug', $slug)->first();
                    if ($role) {
                        $user->roles()->syncWithoutDetaching([$role->id]);
                    }
                }
            }
        });
    }
}
