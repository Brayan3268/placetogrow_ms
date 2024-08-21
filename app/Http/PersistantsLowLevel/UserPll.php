<?php

namespace App\Http\PersistantsLowLevel;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserPll extends PersistantLowLevel
{
    public static function get_all_users()
    {
        $roles = RolePll::get_all_users_roles();

        return ['super_admin_users' => $roles[0]->users,
            'admin_users' => $roles[1]->users,
            'guest_users' => $roles[2]->users];
    }

    public static function get_specific_user(int $id)
    {
        $user = Cache::get('user.'.$id);
        if (is_null($user)) {
            $user = User::find($id);

            Cache::put('user.'.$id, $user);
        }

        RolePll::forget_cache('users.roles');

        return $user;
    }

    public static function get_specific_user_with_role(string $id)
    {
        $user = Cache::get('user.role.'.$id);
        if (is_null($user)) {
            $user = User::find($id);

            Cache::put('user.role.'.$id, $user);
        }

        $role_name = $user->getRoleNames();

        RolePll::forget_cache('users.roles');

        return ['user' => $user, 'role' => $role_name];
    }

    public static function get_users_guest()
    {
        $roles = RolePll::get_all_users_roles();

        return $roles[2]->users;
    }

    public static function save_user(StoreUserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->document_type = $request->document_type;
        $user->document = $request->document;
        $user->phone = $request->phone;
        $user->save();

        RolePll::forget_cache('users.roles');

        return $user;
    }

    public static function update_user_with_password(User $user, StoreUserRequest $request)
    {
        $user->update([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'document_type' => $request['document_type'],
            'document' => $request['document'],
            'password' => bcrypt($request['password']),
            'phone' => $request['phone'],
        ]);

        $user->syncRoles([$request['role']]);

        Cache::flush();

        return $user;
    }

    public static function update_user_without_password(User $user, StoreUserRequest $request)
    {
        $user->update([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'document_type' => $request['document_type'],
            'document' => $request['document'],
        ]);

        $user->syncRoles([$request['role']]);

        Cache::flush();

        return $user;
    }

    public static function delete_user(User $user)
    {
        $user->delete();

        UserPll::forget_cache('user.'.$user->id);
        RolePll::forget_cache('users.roles');
    }

    public static function get_role_names(User $user)
    {
        return $user->getRoleNames();
    }

    public static function get_user_auth()
    {
        $user = User::find(auth()->user()->id);
        $user = UserPll::get_role_names($user);

        return $user;
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }

    public static function get_users_enum_field_values(string $field)
    {
        return DB::select("SHOW COLUMNS FROM users WHERE Field = '".$field."'")[0]->Type;
    }

    public static function save_cache(string $name, $data)
    {
        Cache::put($name, $data);
    }
}
