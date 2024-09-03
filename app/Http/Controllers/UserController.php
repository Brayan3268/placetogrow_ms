<?php

namespace App\Http\Controllers;

use App\Http\PersistantsLowLevel\RolePll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(): RedirectResponse|View
    {
        $this->authorize('viewAny', User::class);

        $response = UserPll::get_all_users();

        $super_admin_users = $response['super_admin_users'];
        $admin_users = $response['admin_users'];
        $guest_users = $response['guest_users'];

        return view('users.index', compact(['super_admin_users', 'admin_users', 'guest_users']));
    }

    public function create(): View|RedirectResponse
    {
        $this->authorize('create', User::class);

        $datos = $this->get_enums();
        $document_types = $datos['document_types'];

        return view('users.create', compact('document_types'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('update', User::class);

        $user = UserPll::save_user($request);
        $role = RolePll::get_specific_role($request->role);

        $user->assignRole($role);

        return redirect()->route('users.index')
            ->with('status', 'User created successfully!')
            ->with('class', 'bg-green-500');
    }

    public function show(string $id): View|RedirectResponse
    {
        $this->authorize('view', User::class);

        $userData = UserPll::get_specific_user_with_role($id);

        return view('users.show', [
            'user' => $userData['user'],
            'role_name' => $userData['role'],
        ]);
    }

    public function edit(string $id): View|RedirectResponse
    {
        $this->authorize('update', User::class);

        $datos = $this->get_enums();
        $document_types = $datos['document_types'];

        $userData = UserPll::get_specific_user_with_role($id);

        return view('users.edit', ['user' => $userData['user'], 'document_types' => $document_types, 'role' => $userData['role']]);
    }

    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', User::class);

        $user = (empty($request['password'])) ? UserPll::update_user_without_password($user, $request) : UserPll::update_user_with_password($user, $request);

        return redirect()->route('users.index')
            ->with('status', 'User updated successfully')
            ->with('class', 'bg-green-500');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', User::class);

        if ($this->valide_last_super_admin($user)) {
            UserPll::delete_user($user);

            return redirect()->route('users.index')
                ->with('status', 'User deleted successfully')
                ->with('class', 'bg-green-500');
        } else {
            return redirect()->route('users.index')
                ->with('status', 'User not deleted because not exist more super admins users')
                ->with('class', 'bg-yellow-500');
        }
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
