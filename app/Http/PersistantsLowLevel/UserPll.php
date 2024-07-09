<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

use function Laravel\Prompts\alert;

class UserPll extends PersistantLowLevel
{
    public static function get_all_users()
    {
        $roles = RolePll::get_all_users_roles();

        return ['super_admin_users' => $roles[0]->users,
            'admin_users' => $roles[1]->users,
            'guest_users' => $roles[2]->users];
    }

    public static function get_specific_user(string $id)
    {
        $user = Cache::get('user.'.$id);
        if (is_null($user)) {
            $user = User::find($id);

            Cache::put('user.'.$id, $user);
        }

        $role_name = $user->getRoleNames();

        return ['user' => $user, 'role' => $role_name];
    }

    public static function save_user(string $name, string $email, string $password)
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        return $user;
    }

    public static function update_user_with_password(User $user, $data)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->syncRoles([$data['role']]);

        return $user;
    }

    public static function update_user_without_password(User $user, $data)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $user->syncRoles([$data['role']]);

        return $user;
    }

    public static function delete_user(User $user)
    {
        $user->delete();
    }

    public static function get_role_names(User $user)
    {
        return $user->getRoleNames();
    }

    public static function get_user_auth()
    {
        alert('1');
        $user = User::find(auth()->user()->id);
        $user = UserPll::get_role_names($user);
        alert('2');

        return $user;
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }
}
