@extends('layouts.app')

@section('page_title', 'Users')
@section('page_subtitle', 'Manage admin and user accounts')

@section('page_actions')
    <button type="button" class="btn-primary" onclick="document.getElementById('createUserModal').showModal()">Create User</button>
@endsection

@section('content')
    <dialog id="createUserModal" class="w-[min(92vw,560px)] rounded-[12px] border border-[#e8edf2] p-0 shadow-[0_24px_70px_rgba(15,23,42,0.24)] backdrop:bg-[#0f172a]/45">
        <section class="bg-white">
            <div class="flex items-center justify-between border-b border-[#e8edf2] px-5 py-4">
                <div>
                    <div class="text-base font-bold text-[#0f172a]">Create User</div>
                    <div class="mt-1 text-xs text-[#64748b]">Add an administrator or verified portal user.</div>
                </div>
                <button type="button" class="rounded-[8px] border border-[#e8edf2] px-3 py-1.5 text-sm font-semibold text-[#64748b]" onclick="document.getElementById('createUserModal').close()">Close</button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}" class="grid max-h-[75vh] gap-4 overflow-y-auto p-5">
                @csrf
                <div>
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" class="form-input">
                </div>
                <div>
                    <label class="form-label" for="username">Username</label>
                    <input id="username" name="username" value="{{ old('username') }}" class="form-input">
                </div>
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-input">
                    <p class="mt-1 text-xs text-[#64748b]">Required for user accounts. Optional for admin accounts.</p>
                </div>
                <div>
                    <label class="form-label" for="role">Role</label>
                    <select id="role" name="role" class="form-input">
                        <option value="admin" @selected(old('role', 'admin') === 'admin')>Admin</option>
                        <option value="user" @selected(old('role', 'admin') === 'user')>User</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-input">
                </div>
                <div>
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-input">
                </div>
                <label class="flex items-center gap-2 text-sm font-semibold text-[#475569]">
                    <input type="checkbox" name="email_verified_at" value="1" @checked(old('email_verified_at'))>
                    <span>Mark email as verified</span>
                </label>
                <button type="submit" class="btn-primary w-full">Create User</button>
            </form>
        </section>
    </dialog>

    <div>
        <section class="panel">
            <div class="panel-heading">User List</div>
            <div class="border-b border-[#e8edf2] p-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 md:flex-row">
                    <input name="q" value="{{ $query }}" class="form-input mt-0" placeholder="Search name, username, email, or role">
                    <button type="submit" class="btn-primary md:w-40">Search</button>
                </form>
            </div>

            @if ($errors->any())
                <div class="border-b border-[#fecaca] bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    Please review the user form fields and try again.
                </div>
            @endif
            @error('delete_user')
                <div class="border-b border-[#fecaca] bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $message }}</div>
            @enderror

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] border-collapse text-sm">
                    <thead class="bg-[#f8fafc] text-left text-[11px] font-bold uppercase tracking-[0.08em] text-[#64748b]">
                        <tr>
                            <th class="border-b border-[#e8edf2] px-4 py-3">Name</th>
                            <th class="border-b border-[#e8edf2] px-4 py-3">Username</th>
                            <th class="border-b border-[#e8edf2] px-4 py-3">Email</th>
                            <th class="border-b border-[#e8edf2] px-4 py-3">Role</th>
                            <th class="border-b border-[#e8edf2] px-4 py-3">Status</th>
                            <th class="border-b border-[#e8edf2] px-4 py-3">Created</th>
                            <th class="border-b border-[#e8edf2] px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e8edf2] bg-white">
                        @forelse ($users as $user)
                            <tr class="hover:bg-[#f8fafc]">
                                <td class="px-4 py-4 font-semibold text-[#0f172a]">{{ $user->name }}</td>
                                <td class="px-4 py-4 text-[#475569]">{{ $user->username }}</td>
                                <td class="px-4 py-4 text-[#475569]">{{ $user->email ?: 'N/A' }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full {{ $user->role === 'admin' ? 'bg-[#dcfce7] text-[#166534]' : 'bg-[#e0f2fe] text-[#075985]' }} px-3 py-1 text-xs font-bold uppercase">{{ $user->role }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full bg-[#f1f5f9] px-3 py-1 text-xs font-bold uppercase text-[#475569]">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</span>
                                </td>
                                <td class="px-4 py-4 text-[#64748b]">{{ optional($user->created_at)->format('M d, Y') ?: 'N/A' }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <details class="relative">
                                            <summary class="btn-secondary cursor-pointer list-none px-3 py-2 text-xs">Edit</summary>
                                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="absolute right-0 z-20 mt-2 grid w-[360px] gap-3 rounded-[8px] border border-[#e8edf2] bg-white p-4 shadow-[0_18px_40px_rgba(15,23,42,0.12)]">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="form-label">Name</label>
                                                    <input name="name" value="{{ old('name', $user->name) }}" class="form-input">
                                                </div>
                                                <div>
                                                    <label class="form-label">Username</label>
                                                    <input name="username" value="{{ old('username', $user->username) }}" class="form-input">
                                                </div>
                                                <div>
                                                    <label class="form-label">Email</label>
                                                    <input name="email" type="email" value="{{ old('email', $user->email) }}" class="form-input">
                                                </div>
                                                <div>
                                                    <label class="form-label">Role</label>
                                                    <select name="role" class="form-input">
                                                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                                                        <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                                                    </select>
                                                </div>
                                                <label class="flex items-center gap-2 text-sm font-semibold text-[#475569]">
                                                    <input type="checkbox" name="email_verified_at" value="1" @checked(old('email_verified_at', (bool) $user->email_verified_at))>
                                                    <span>Email verified</span>
                                                </label>
                                                <button type="submit" class="btn-primary w-full">Save Details</button>
                                            </form>
                                        </details>

                                        <button type="button" class="btn-secondary px-3 py-2 text-xs" onclick="document.getElementById('passwordUserModal{{ $user->id }}').showModal()">Password</button>
                                        <dialog id="passwordUserModal{{ $user->id }}" class="w-[min(92vw,420px)] rounded-[12px] border border-[#e8edf2] p-0 shadow-[0_24px_70px_rgba(15,23,42,0.24)] backdrop:bg-[#0f172a]/45">
                                            <section class="bg-white">
                                                <div class="flex items-center justify-between border-b border-[#e8edf2] px-5 py-4">
                                                    <div>
                                                        <div class="text-base font-bold text-[#0f172a]">Change Password</div>
                                                        <div class="mt-1 text-xs text-[#64748b]">{{ $user->name }}</div>
                                                    </div>
                                                    <button type="button" class="rounded-[8px] border border-[#e8edf2] px-3 py-1.5 text-sm font-semibold text-[#64748b]" onclick="document.getElementById('passwordUserModal{{ $user->id }}').close()">Close</button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.users.password', $user) }}" class="grid gap-3 p-5">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="form-label">New Password</label>
                                                    <input name="password" type="password" class="form-input">
                                                </div>
                                                <div>
                                                    <label class="form-label">Confirm Password</label>
                                                    <input name="password_confirmation" type="password" class="form-input">
                                                </div>
                                                <button type="submit" class="btn-secondary w-full">Update Password</button>
                                                </form>
                                            </section>
                                        </dialog>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-[8px] border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-50">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-[#64748b]">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-[#e8edf2] px-4 py-4">
                {{ $users->links() }}
            </div>
        </section>
    </div>
@endsection
