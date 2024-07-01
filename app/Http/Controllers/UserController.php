<?php

namespace App\Http\Controllers;

use App\Http\PersistantsLowLevel\RolePll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): RedirectResponse|View
    {
        if ($this->validate_role()) {
            $response = UserPll::get_all_users();

            $super_admin_users = $response['super_admin_users'];
            $admin_users = $response['admin_users'];
            $guest_users = $response['guest_users'];

            return view('users.index', compact(['super_admin_users', 'admin_users', 'guest_users']));
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-yellow-500');
    }

    public function create(): View|RedirectResponse
    {
        if ($this->validate_role()) {
            return view('users.create');
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->validate_role()) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = UserPll::save_user($request->name, $request->email, $request->password);
            $role = RolePll::get_specific_role($request->role);

            $user->assignRole($role);

            RolePll::forget_cache('users.roles');

            return redirect()->route('users.index')
                ->with('status', 'User created successfully!')
                ->with('class', 'bg-green-500');
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function show(string $id): View|RedirectResponse
    {
        if ($this->validate_role()) {
            $userData = UserPll::get_specific_user($id);

            return view('users.show', [
                'user' => $userData['user'],
                'role_name' => $userData['role'],
            ]);
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function edit(string $id): View|RedirectResponse
    {
        if ($this->validate_role()) {
            $userData = UserPll::get_specific_user($id);
            RolePll::forget_cache('users.roles');

            return view('users.edit', ['user' => $userData['user']]);
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($this->validate_role()) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,'.$user->id,
                'password' => 'nullable|string|min:8',
                'role' => 'required|in:super_admin,admin,guest',
            ]);

            if (empty($validatedData['password'])) {
                $data = [
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'role' => $validatedData['role'],
                ];

                $user = UserPll::update_user_without_password($user, $data);
            } else {
                $data = [
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => bcrypt($validatedData['password']),
                    'role' => $validatedData['role'],
                ];

                $user = UserPll::update_user_with_password($user, $data);
            }

            UserPll::forget_cache('user.'.$user->id);
            RolePll::forget_cache('users.roles');

            return redirect()->route('users.index')
                ->with('status', 'User updated successfully')
                ->with('class', 'bg-green-500');
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($this->validate_role()) {
            if ($this->valide_last_super_admin($user)) {
                UserPll::delete_user($user);
                UserPll::forget_cache('user.'.$user->id);
                RolePll::forget_cache('users.roles');

                return redirect()->route('users.index')
                    ->with('status', 'User deleted successfully')
                    ->with('class', 'bg-green-500');
            } else {
                return redirect()->route('users.index')
                    ->with('status', 'User not deleted because not exist more super admins users')
                    ->with('class', 'bg-yellow-500');
            }
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    private function valide_last_super_admin(User $user): bool
    {
        $role_name = UserPll::get_role_names($user);

        if ($role_name[0] === 'super_admin') {
            return (RolePll::count_super_admin_users() > 1) ? true : false;
        } else {
            return true;
        }
    }

    private function validate_role(): bool
    {
        $role_name = UserPll::get_user_auth();

        return ($role_name[0] === 'super_admin' || $role_name[0] === 'admin') ? true : false;
    }
}
