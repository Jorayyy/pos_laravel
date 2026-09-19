<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->search($request->search)
            ->with('role')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'photo' => 'nullable|image|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);
            AuditLogHelper::log('created', $user, null, collect($validated)->except('password')->toArray());
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('role');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
                \Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        DB::transaction(function () use ($user, $validated) {
            $old = $user->only(['name', 'email', 'role_id', 'is_active']);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);
            AuditLogHelper::log('updated', $user, $old, collect($validated)->except('password')->toArray());
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        DB::transaction(function () use ($user) {
            if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
                \Storage::disk('public')->delete($user->photo);
            }
            AuditLogHelper::log('deleted', $user, $user->toArray(), null);
            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account.']);
        }

        DB::transaction(function () use ($user) {
            $old = ['is_active' => $user->is_active];
            $user->update(['is_active' => !$user->is_active]);
            AuditLogHelper::log('status_toggled', $user, $old, ['is_active' => $user->is_active]);
        });

        return back()->with('success', 'User status updated.');
    }

    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $old = ['role_id' => $user->role_id];
            $user->update($validated);
            AuditLogHelper::log('role_assigned', $user, $old, $validated);
        });

        return back()->with('success', 'Role assigned successfully.');
    }
}
