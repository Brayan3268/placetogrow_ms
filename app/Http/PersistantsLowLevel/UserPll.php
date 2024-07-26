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

    public static function get_specific_user(string $id)
    {
        $user = Cache::get('user.'.$id);
        if (is_null($user)) {
            $user = User::find($id);

            Cache::put('user.'.$id, $user);
        }
        //dd($user);
        $role_name = $user->getRoleNames();
        //dd($role_name);

        return ['user' => $user, 'role' => $role_name];
    }

    public static function save_user(StoreUserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->document_type = $request->document_type;
        $user->document = $request->document;
        $user->save();

        return $user;
    }

    public static function update_user_with_password(User $user, $data)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'document_type' => $data['document_type'],
            'document' => $data['document'],
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
            'document_type' => $data['document_type'],
            'document' => $data['document'],
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
