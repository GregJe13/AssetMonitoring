<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Display user management page.
     * Manager: sees guest users (can assign/remove worker)
     * Admin: sees all users (can assign/remove all roles)
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $search = $request->input('search');
        $roleFilter = $request->input('role');

        $query = User::query()
            ->where('id', '!=', $currentUser->id) // Don't show self
            ->orderBy('name');

        // Manager can only see guests and workers
        if ($currentUser->isManager()) {
            $query->whereIn('role', [User::ROLE_GUEST, User::ROLE_WORKER]);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        $users = $query->paginate(15)->withQueryString();

        // Determine which roles the current user can manage
        $manageableRoles = $currentUser->isAdmin()
            ? [User::ROLE_GUEST, User::ROLE_WORKER, User::ROLE_MANAGER]
            : [User::ROLE_GUEST, User::ROLE_WORKER]; // Manager can only toggle guest/worker

        return view('users.index', compact('users', 'manageableRoles', 'currentUser'));
    }

    /**
     * Update a user's role.
     * Backend implementation — placeholder for now.
     */
    public function updateRole(Request $request, User $user)
    {
        // Backend will be implemented later
        $request->validate([
            'role' => 'required|in:guest,worker,manager',
        ]);

        $currentUser = Auth::user();
        $newRole = $request->input('role');

        // Manager can only assign/remove worker role
        if ($currentUser->isManager()) {
            if (!in_array($newRole, [User::ROLE_GUEST, User::ROLE_WORKER])) {
                abort(403, 'Manager hanya dapat mengubah role guest/worker.');
            }
            if (!in_array($user->role, [User::ROLE_GUEST, User::ROLE_WORKER])) {
                abort(403, 'Manager tidak dapat mengubah role user ini.');
            }
        }

        // Prevent changing own role
        if ($user->id === $currentUser->id) {
            abort(403, 'Tidak dapat mengubah role sendiri.');
        }

        // Prevent non-admin from assigning manager
        if ($newRole === User::ROLE_MANAGER && !$currentUser->isAdmin()) {
            abort(403, 'Hanya admin yang dapat memberikan role manager.');
        }

        // Prevent changing admin role
        if ($user->isAdmin()) {
            abort(403, 'Tidak dapat mengubah role administrator.');
        }

        $user->update(['role' => $newRole]);

        return redirect()->route('users.index')
            ->with('success', "Role {$user->name} berhasil diubah menjadi {$user->role_label}.");
    }
}
