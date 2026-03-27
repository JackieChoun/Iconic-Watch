<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('role')
            ->select('id', 'name', 'email', 'id_role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = Role::query()->select('id', 'nom_role')->orderBy('id')->get();

        return Inertia::render('admin/Users', [
            'title' => 'Utilisateurs',
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function edit(User $user)
    {
        $user->load('role');
        $roles = Role::query()->select('id', 'nom_role')->orderBy('id')->get();

        return Inertia::render('admin/UserEdit', [
            'title' => 'Modifier utilisateur',
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'id_role' => ['required', 'exists:roles,id'],
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }
}
