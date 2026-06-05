<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q'));

        $users = User::query()
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($where) use ($query) {
                    $where->where('name', 'like', "%{$query}%")
                        ->orWhere('username', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('role', 'like', "%{$query}%");
                });
            })
            ->orderByRaw("case when role = 'admin' then 0 else 1 end")
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'query' => $query,
            'offices' => Office::pluck('name')->sort()->values()->all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $user = User::query()->create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?: null,
            'role' => $validated['role'],
            'office' => $validated['office'] ?? null,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => $request->boolean('email_verified_at') ? now() : null,
        ]);

        return back()->with('status', 'User account created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate($this->rules($user));

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?: null,
            'role' => $validated['role'],
            'office' => $validated['office'] ?? null,
            'email_verified_at' => $request->boolean('email_verified_at') ? ($user->email_verified_at ?: now()) : null,
        ]);

        return back()->with('status', 'User account updated successfully.');
    }

    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'User password updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->is($user)) {
            return back()->withErrors(['delete_user' => 'You cannot delete the account you are currently using.']);
        }

        $user->delete();

        return back()->with('status', 'User account deleted successfully.');
    }

    private function rules(?User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user)],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user),
                Rule::requiredIf(fn () => request('role') === 'user'),
            ],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'office' => ['nullable', 'string', 'max:255'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }
}
