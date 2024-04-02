<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = $user->getAllPermissions();

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function create()
    : View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $roles = Role::all();
        $roles = $roles->filter(fn($role) => $role->name !== 'super-admin');
        $user = new User();
        $permissions = [];

        return view('users.create', compact('roles', 'user', 'permissions'));
    }

    public function store(Request $request)
    : RedirectResponse {
        $request->validate([
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'nullable|min:8',
            'roles' => 'required|min:1',
        ]);

        $user = User::query()->create($request->except('roles', 'permissions'));

        $this->syncPermissions($request, $user);
        flash()->addSuccess(__('User created successfully.'));

        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    : RedirectResponse {
        $request->validate([
            'name' => 'required|max:255|unique:users,name,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'roles' => 'required|min:1',
        ]);

        //if password is empty, remove it from the request
        if(empty($request->password))
        {
            $request->offsetUnset('password');
        }

        $user->update($request->except('roles', 'permissions'));
        $this->syncPermissions($request, $user);
        flash()->addSuccess(__('User updated successfully.'));

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');

        return view('users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        flash()->addSuccess(__('User deleted successfully.'));

        return redirect()->route('users.index');
    }

    public function getPermissions(Request $request)
    : JsonResponse {
        $roles = $request->input('roles');

        $permissions = [];
        foreach ($roles as $role)
        {
            $rolePermissions = Role::find($role)->permissions()->get(['id', 'name'])->toArray();
            $permissions += $rolePermissions;
        }

        return response()->json($permissions);
    }

    public function syncPermissions($request, $user)
    {
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);
        //convert collection values to integers

        $permissions = ($permissions) ? array_map('intval', $permissions) : [];
        $roles = Role::query()->find($roles);

        $user->syncPermissions($permissions);
        $user->syncRoles($roles);

        return $user;
    }
}
