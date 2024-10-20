<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'users.index',
            'users.create',
            'users.edit',
            'users.delete',
            'users.store',
        ];
    
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guestRole = Role::firstOrCreate(['name' => 'guest']);

        $superAdminRole->givePermissionTo($permissions); // Asignar todos los permisos
        $adminRole->givePermissionTo(['users.index', 'users.create', 'users.edit']); // Asignar permisos específicos
        $guestRole->givePermissionTo('users.index'); // Solo permitir ver usuarios
    }

    public function test_super_admin_user_can_access_index()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $guestUser = User::factory()->create();
        $guestUser->assignRole('guest');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);

        $response->assertViewIs('users.index');

        $response->assertViewHas('super_admin_users', function ($users) use ($superAdminUser) {
            return $users->contains($superAdminUser);
        });

        $response->assertViewHas('admin_users', function ($users) use ($adminUser) {
            return $users->contains($adminUser);
        });

        $response->assertViewHas('guest_users', function ($users) use ($guestUser) {
            return $users->contains($guestUser);
        });
    }

    public function test_non_super_admin_user_cannot_access_index()
    {
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->actingAs($user);

        $response = $this->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_user_can_access_create()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $response = $this->get(route('users.create'));

        $response->assertStatus(200);

        //$response->assertViewIs('users.create');
    }

    public function test_store_creates_user_and_redirects()
    {
        $this->withoutExceptionHandling();
        
        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $response = $this->withSession([])->post(route('users.store'), [
            '_token' => csrf_token(),
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'document' => '123456789',
            'document_type' => 'CC',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('users.index'));

        $response->assertSessionHas('status', 'User created successfully!');
        $response->assertSessionHas('class', 'bg-green-500');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }


    /*public function testItCannotListUsersWithUnauthenticated(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login'));
    }*/

    //public function testItCanListUsersWithAuthenticated(): void
    //{
        //$this->seed_db();

        //TEST 1
      /*  $user = User::find(1);
        $response = $this->actingAs($user)
            ->get(route('users.index'));

        $response->assertOk();
*/
        //TEST 2
        /*$user = User::find(3);
        $response = $this->actingAs($user)
            ->get(route('users.index'));

        $response->assertRedirect(route('dashboard'));

        //TEST 3
        $user = User::find(2);
        $response = $this->actingAs($user)
            ->delete("/users/{$user->id}");

        $response->assertRedirect(route('users.index'));

        //TEST 4
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->delete("/users/{$user->id}");

        $response->assertRedirect(route('users.index'));

        //TEST 5
        $user = User::find(3);
        $response = $this->actingAs($user)
            ->delete("/users/{$user->id}");

        $response->assertRedirect(route('dashboard'));

        //TEST 6
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->get(route('users.create'));

        $response->assertOk();

        //TEST 7
        $user = User::find(3);
        $response = $this->actingAs($user)
            ->get(route('users.create'));

        $response->assertRedirect(route('dashboard'));

        //TEST 8
        $user = User::find(1);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => '12345678',
        ];
        $userData['password'] = bcrypt($userData['password']);

        $response = $this->actingAs($user)->post(route('users.store'), $userData);

        //$response->assertRedirect(route('users.index'));

        $role = Role::findByName('admin');

        $createdUser = User::where('email', 'john.doe@example.com')->first();
        $createdUser->assignRole($role);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        //$this->assertTrue(Hash::check('password123', $createdUser->password));

        //TEST 9
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->get(route('users.show', ['user' => $user->id]));

        $response->assertOk();

        //TEST 10
        $user = User::find(3);
        $response = $this->actingAs($user)
            ->get(route('users.show', ['user' => $user->id]));

        $response->assertRedirect(route('dashboard'));

        //TEST 11
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->get(route('users.edit', ['user' => $user->id]));

        $response->assertOk();

        //TEST 12
        $user = User::find(3);
        $response = $this->actingAs($user)
            ->get(route('users.edit', ['user' => $user->id]));

        $response->assertRedirect(route('dashboard'));

        //TEST 13
        $user = User::find(1);
        $user_ud = User::find(3);

        $newUserData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'role' => 'super_admin',
        ];

        if (empty($newUserData['password'])) {
            $response = $this->actingAs($user)->put(route('users.update', ['user' => $user_ud->id]), $newUserData);
        } else {
            $newUserData['password'] = '12345678';
            $response = $this->actingAs($user)->put(route('users.update', ['user' => $user_ud->id]), $newUserData);
        }
        $user_ud->syncRoles([$newUserData['role']]);
        //$response->assertRedirect(route('users.show'));

        $user_ud->refresh();

        $this->assertEquals('Jane Doe', $user_ud->name);
        $this->assertEquals('jane.doe@example.com', $user_ud->email);
        //$this->assertTrue(Hash::check('newpassword', $user_ud->password)); // Verifica que la nueva contraseña esté encriptada correctamente

        //TEST 14
        $user = User::find(1);
        $user_ud = User::find(3);

        $newUserData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => '1234456788',
            'role' => 'super_admin',
        ];

        if (empty($newUserData['password'])) {
            $response = $this->actingAs($user)->put(route('users.update', ['user' => $user_ud->id]), $newUserData);
        } else {
            $newUserData['password'] = '12345678';
            $response = $this->actingAs($user)->put(route('users.update', ['user' => $user_ud->id]), $newUserData);
        }
        $user_ud->syncRoles([$newUserData['role']]);
        //$response->assertRedirect(route('users.show'));

        $user_ud->refresh();

        $this->assertEquals('Jane Doe', $user_ud->name);
        $this->assertEquals('jane.doe@example.com', $user_ud->email);
        //$this->assertTrue(Hash::check('newpassword', $user_ud->password)); // Verifica que la nueva contraseña esté encriptada correctamente

        //TEST 15
        $user = User::find(3);
        $user_ud = User::find(1);
        $newUserData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'role' => 'super_admin',
        ];

        $response->assertStatus(302);
        //$response->assertRedirect(route('dashboard'));*/
    //}
}
