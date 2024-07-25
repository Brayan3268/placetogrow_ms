<?php

namespace App\Http\Controllers;

use App\Http\PersistantsLowLevel\RolePll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
        $datos = $this->get_enums();
        $document_types = $datos['document_types'];

        if ($this->validate_role()) {
            return view('users.create', compact('document_types'));
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');

    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        if ($this->validate_role()) {
            $user = UserPll::save_user($request);
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
            $datos = $this->get_enums();
            $document_types = $datos['document_types'];

            $userData = UserPll::get_specific_user($id);
            RolePll::forget_cache('users.roles');

            return view('users.edit', ['user' => $userData['user'], 'document_types' => $document_types, 'role' => $userData['role']]);
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        if ($this->validate_role()) {
            if (empty($request['password'])) {
                $data = [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'document_type' => $request['document_type'],
                    'document' => $request['document'],
                    'role' => $request['role'],
                ];

                $user = UserPll::update_user_without_password($user, $data);

            } else {
                $data = [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'document_type' => $request['document_type'],
                    'document' => $request['document'],
                    'password' => bcrypt($request['password']),
                    'role' => $request['role'],
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

    public function get_enums(): array
    {
        //if (is_null($categories)) {
        $enumDocumentTypeValues = UserPll::get_users_enum_field_values('document_type');
        preg_match('/^enum\((.*)\)$/', $enumDocumentTypeValues, $matches);
        $document_types = explode(',', $matches[1]);
        $document_types = array_map(fn ($value) => trim($value, "'"), $document_types);

        UserPll::save_cache('document_types', $document_types);
        //} else {
        //$document_types = SitePll::get_cache('document_types');
        //}

        return ['document_types' => $document_types];
    }
}
