<?php

namespace Tests\Feature;

use App\Http\PersistantsLowLevel\UserPll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

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
            'users.show',
            'users.destroy',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guestRole = Role::firstOrCreate(['name' => 'guest']);

        $superAdminRole->givePermissionTo($permissions);
        $adminRole->givePermissionTo($permissions);
        $guestRole->givePermissionTo('users.index');
    }

    public function test_super_admin_user_can_access_index()
    {
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
        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $response = $this->get(route('users.create'));

        $response->assertStatus(200);
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

    public function test_show_user_and_redirects()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        $userToView = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $response = $this->get(route('users.show', [
            'user' => $userToView,
            'role_name' => [$userToView->getRoleNames()],
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user', $userToView);

        $roleName = $userToView->getRoleNames()->first();
        $response->assertViewHas('role_name', $roleName);
    }

    public function test_edit_returns_correct_view_with_user_data()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $user->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->actingAs($user);

        $userToEdit = User::factory()->create([
            'name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
            'document' => '987654321',
            'document_type' => 'CC',
        ]);
        $userToEdit->assignRole('admin');

        $response = $this->withSession([])->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->get(route('users.edit', $userToEdit->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user', $userToEdit);

        $roleData = UserPll::get_specific_user_with_role($userToEdit->id);
        $response->assertViewHas('role', $roleData['role']);
    }

    public function test_update_user_data_without_pass_and_redirects()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->actingAs($user);

        $userToUpdate = User::factory()->create([
            'name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
            'document' => '987654321',
            'document_type' => 'CC',
        ]);

        $data = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'document' => '123456789',
            'document_type' => 'PPT',
            'role' => 'admin',
            'password' => '',
        ];

        $response = $this->withSession([])->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put(route('users.update', $userToUpdate->id), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('status', 'User updated successfully');
        $response->assertSessionHas('class', 'bg-green-500');

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ]);
    }

    public function test_destroy_deletes_user_successfully()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        $userToDelete = User::factory()->create();
        $userToDelete->assignRole('admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $response = $this->withSession([])->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->delete(route('users.destroy', $userToDelete->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('status', 'User deleted successfully');
        $response->assertSessionHas('class', 'bg-green-500');

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_destroy_prevents_last_super_admin_deletion()
    {
        $this->withoutExceptionHandling();

        $superAdminUser1 = User::factory()->create();
        $superAdminUser1->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser1 */
        $this->actingAs($superAdminUser1);

        $response = $this->withSession([])->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->delete(route('users.destroy', $superAdminUser1->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('status', 'User not deleted because not exist more super admins users');
        $response->assertSessionHas('class', 'bg-yellow-500');

        $this->assertDatabaseHas('users', [
            'id' => $superAdminUser1->id,
        ]);
    }

    public function test_update_user_data_with_pass_and_redirects()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->actingAs($user);

        $userToUpdate = User::factory()->create([
            'name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
            'document' => '987654321',
            'document_type' => 'CC',
        ]);

        $data = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'document' => '123456789',
            'document_type' => 'PPT',
            'role' => 'admin',
            'password' => bcrypt('123333658'),
        ];

        $response = $this->withSession([])->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put(route('users.update', $userToUpdate->id), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('status', 'User updated successfully');
        $response->assertSessionHas('class', 'bg-green-500');

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ]);
    }
}
