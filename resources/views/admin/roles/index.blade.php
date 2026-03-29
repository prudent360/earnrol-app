@extends('layouts.app')

@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & Permissions')
@section('page_subtitle', 'Manage user roles and their permissions')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
    {{-- Left Panel: Roles List --}}
    <div class="lg:col-span-2">
        <div class="card !p-0 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <h3 class="text-sm font-bold text-[#1a1a2e]">Roles ({{ $roles->count() }})</h3>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="text-xs font-semibold text-[#e05a3a] hover:underline">+ Add Role</a>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($roles as $role)
                <a href="{{ route('admin.roles.index', ['role' => $role->id]) }}"
                   class="block px-5 py-4 hover:bg-gray-50 transition-colors {{ $selectedRole && $selectedRole->id === $role->id ? 'bg-[#e05a3a]/5 border-l-2 border-[#e05a3a]' : '' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            @php
                                $badgeColor = match($role->slug) {
                                    'superadmin' => 'bg-red-100 text-red-700',
                                    'admin' => 'bg-blue-100 text-blue-700',
                                    'creator' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $badgeColor }}">{{ $role->name }}</span>
                            <p class="text-sm text-gray-500 mt-1">{{ $role->description ?? $role->name }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                {{ $role->slug === 'superadmin' ? 'All permissions' : $role->permissions_count . ' permissions' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-[#1a1a2e]">{{ $role->users_count }}</p>
                            <p class="text-[10px] text-gray-400">users</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Panel: Permissions --}}
    <div class="lg:col-span-3">
        @if($selectedRole)
        <div class="card !p-0 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-[#1a1a2e]">{{ $selectedRole->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $selectedRole->description }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if(!$selectedRole->isBuiltIn())
                    <a href="{{ route('admin.roles.edit', $selectedRole) }}" class="text-xs font-medium text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.roles.destroy', $selectedRole) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-red-600 hover:underline" onclick="return confirm('Delete this role?')">Delete</button>
                    </form>
                    @endif
                </div>
            </div>

            @if($selectedRole->slug === 'superadmin')
            <div class="px-5 py-8 text-center">
                <svg class="w-10 h-10 text-amber-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <p class="text-sm font-bold text-[#1a1a2e]">Super Admin has all permissions</p>
                <p class="text-xs text-gray-400 mt-1">This role bypasses all permission checks automatically.</p>
            </div>
            @else
            <form action="{{ route('admin.roles.permissions.update', $selectedRole) }}" method="POST">
                @csrf @method('PUT')
                <div class="max-h-[60vh] overflow-y-auto">
                    @php $rolePermissionIds = $selectedRole->permissions->pluck('id')->toArray(); @endphp

                    @foreach($groupedPermissions as $group => $permissions)
                    <div class="border-b border-gray-50">
                        <div class="px-5 py-3 bg-gray-50 flex items-center justify-between">
                            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $group }}</h4>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <span class="text-[10px] text-gray-400">All</span>
                                <input type="checkbox" class="rounded border-gray-300 text-[#e05a3a] focus:ring-[#e05a3a]"
                                       onchange="toggleGroup(this, '{{ Str::slug($group) }}')"
                                       {{ $permissions->every(fn($p) => in_array($p->id, $rolePermissionIds)) ? 'checked' : '' }}>
                            </label>
                        </div>
                        <div class="px-5 py-2 space-y-1">
                            @foreach($permissions as $permission)
                            <label class="flex items-center justify-between py-1.5 cursor-pointer hover:bg-gray-50 rounded px-2 -mx-2">
                                <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       class="rounded border-gray-300 text-[#e05a3a] focus:ring-[#e05a3a] group-{{ Str::slug($group) }}"
                                       {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="btn-primary text-sm py-2">Save Permissions</button>
                </div>
            </form>
            @endif
        </div>
        @else
        <div class="card text-center py-16">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <h3 class="text-base font-bold text-[#1a1a2e]">Select a Role</h3>
            <p class="text-sm text-gray-400 mt-1">Click on a role to view and manage its permissions.</p>
        </div>
        @endif
    </div>
</div>

<script>
function toggleGroup(checkbox, groupSlug) {
    document.querySelectorAll('.group-' + groupSlug).forEach(cb => {
        cb.checked = checkbox.checked;
    });
}
</script>
@endsection
