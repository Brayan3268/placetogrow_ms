<?php

namespace Tests\Feature;

use App\Constants\CurrencyTypes;
use App\Constants\SiteTypes;
use App\Http\PersistantsLowLevel\SuscriptionPll;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SitesControllerTest extends TestCase
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
            'sites.index',
            'sites.create',
            'sites.store',
            'sites.show',
            'invoices.create',
            'site.manage',
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

    public function test_index_displays_sites_correctly()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $sites = Site::factory()->count(3)->create([
            'site_type' => 'OPEN',
        ]);

        $response = $this->get(route('sites.index'));

        $response->assertStatus(200);
        $response->assertViewIs('sites.index');
        $response->assertViewHasAll([
            'open_sites', 'close_sites', 'suscription_sites',
        ]);
    }

    public function test_create_displays_form_correctly()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $response = $this->get(route('sites.create'));

        $response->assertStatus(200);
        $response->assertViewIs('sites.create');
        $response->assertViewHasAll([
            'categories', 'currency_options', 'site_type_options',
        ]);
    }

    public function test_store_creates_new_site_with_image()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);
        Storage::fake('public');

        $file = UploadedFile::fake()->image('site.jpg');

        $category = Category::factory()->create();
        $siteData = [
            'slug' => Str::slug('Sitio de Prueba'),
            'name' => 'Sitio de Prueba',
            'category' => $category->id,
            'expiration_time' => rand(10, 30),
            'currency' => CurrencyTypes::toArray()[array_rand(CurrencyTypes::toArray())],
            'site_type' => SiteTypes::cases()[array_rand(SiteTypes::cases())]->name,
            'image' => $file,
        ];

        $response = $this->withSession([])->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post(route('sites.store'), $siteData);

        //$imageName = $file->getClientOriginalName() . time() . '.' . $file->getClientOriginalExtension();
        //$this->assertTrue(Storage::disk('public')->exists('site_images/' . $imageName));

        $response->assertRedirect(route('sites.index'));
        $response->assertSessionHas([
            'status' => 'Site created successfully!',
            'class' => 'bg-green-500',
        ]);
    }

    public function test_store_redirects_on_image_validation_failure()
    {
        $this->withoutExceptionHandling();

        $superAdminUser = User::factory()->create();
        $superAdminUser->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $superAdminUser */
        $this->actingAs($superAdminUser);

        $siteData = [
            'slug' => Str::slug('Sitio de Prueba'),
            'name' => 'Sitio de Prueba',
            'expiration_time' => rand(10, 30),
            'currency' => CurrencyTypes::toArray()[array_rand(CurrencyTypes::toArray())],
            'site_type' => SiteTypes::cases()[array_rand(SiteTypes::cases())]->name,
        ];

        $response = $this->withSession([])->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post(route('sites.store'), $siteData);

        $response->assertRedirect(route('sites.index'));
        $response->assertSessionHas([
            'status' => 'Site created unsuccessfully!',
            'class' => 'bg-red-500',
        ]);
    }

    public function test_show_displays_site_information()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $user->assignRole('super_admin');

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->actingAs($user);

        $category = Category::factory()->create();

        $site = Site::factory()->create([
            'site_type' => 'CLOSE',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('sites', [
            'id' => $site->id,
        ]);

        $payment = Payment::factory()->create([
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
        ]);

        $invoice = Invoice::factory()->create([
            'site_id' => $site->id,
            'user_id' => $user->id,
            'amount' => $payment->amount,
            'reference' => 'INV-'.uniqid(),
            'date_created' => now(),
            'date_expiration' => now()->addDays(30),
            'amount_surcharge' => 0,
            'date_surcharge' => now()->addDays(15),
        ]);

        $this->assertDatabaseHas('invoices', [
            'reference' => $invoice->reference,
            'site_id' => $site->id,
        ]);

        $suscription_plans = SuscriptionPll::get_site_suscription(intval($site->id));

        $this->assertNotNull($suscription_plans);

        $response = $this->get(route('sites.show', $site->id));

        $response->assertStatus(200);
        $response->assertViewIs('sites.show');
        $response->assertViewHas('site', $site);

        $invoices = $response->viewData('invoices');
        $this->assertCount(1, $invoices);

        $pay_exist = $response->viewData('pay_exist');
        $this->assertTrue($pay_exist);

        $pay = $response->viewData('pay');
        $this->assertNotEmpty($pay);

        $suscription_plans = $response->viewData('suscription_plans');
        $this->assertEmpty($suscription_plans);

        $user_plans_get_suscribe = $response->viewData('user_plans_get_suscribe');
        $this->assertEmpty($user_plans_get_suscribe);

        $user_plans_get_suscribe = $response->viewData('user_plans_get_suscribe');
        $this->assertEmpty($user_plans_get_suscribe);
    }

    /*public function testItCanListSitesWithAuthenticated(): void
    {
        $user = User::factory()->create();

    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo('sites.index');

    $user->assignRole('admin');
    //$this->seed_db();

    //TEST 1

    $response = $this->actingAs($user)
        ->get(route('sites.index'));

    $response->assertOk();

    //TEST 2
    /*$user = User::find(1);
    $response = $this->actingAs($user)
        ->get(route('sites.show', ['site' => 1]));

    $response->assertOk();

    //TEST 3
    $user = User::find(1);
    $response = $this->actingAs($user)->get(route('sites.create'));
    $response->assertStatus(200);
    $response->assertViewIs('sites.create');
    $response->assertViewHasAll([
        'categories',
        'current_options',
        'site_type_options',
        'document_types',
    ]);

    //TEST 4
    Storage::fake('public');
    $user = User::find(1);
    $category = Category::find(1);

    $requestData = [
        'slug' => 'test-site',
        'name' => 'Test Site',
        'document_type' => 'CIF',
        'document' => '12345678A',
        'category' => $category->id,
        'expiration_time' => '2024-12-31',
        'current' => 'AC',
        'site_type' => 'Type A',
        'image' => 'public/images/welcome.jpg',
    ];

    $response = $this->actingAs($user)->post(route('sites.store'), $requestData);

    $response->assertRedirect(route('sites.create'));

    /*$this->assertDatabaseHas('sites', [
        'slug' => 'test-site',
        'name' => 'Test Site',
        'document_type' => 'CIF',
        'document' => '12345678A',
        'category_id' => $category->id,
        'expiration_time' => '2024-12-31',
        'current_type' => 'AC',
        'site_type' => 'Type A',
        'image' => 'storage/site_images/test-image.jpg',
    ]);*/

    //$response->assertSessionHas('status', 'Site created successfully!');
    //$response->assertSessionHas('class', 'bg-green-500');

    //TEST 5
    /*$user = User::find(3);
    $site = Site::find(2);
    $response = $this->delete("/sites/{$site->id}");
    $response->assertRedirect(route('sites.index'));

    //TEST 5
    $user = User::find(1);
    $site = Site::find(1);
    $response = $this->actingAs($user)
        ->delete("/sites/{$site->id}");

    $response->assertRedirect(route('sites.index'));

    //TEST 6
    $user = User::find(1);
    $site = Site::find(3);

    $response = $this->actingAs($user)->get(route('sites.edit', ['site' => $site->id]));
    $response->assertStatus(200);
    $response->assertViewIs('sites.edit');

    //TEST 7
    $user = User::find(1);
    $site = Site::find(3);
    $category = Category::find(1);

    //Cache::put('site.'.$site->id, $site, $minutes = 1000);

    // Nuevos datos para actualizar el sitio
    $newData = [
        'slug' => 'updated-site-slug',
        'name' => 'Updated Site Name',
        'document_type' => 'NIT',
        'document' => '9876543210',
        'category' => $category->id,
        'expiration_time' => '2025-12-31',
        'current' => 'DC',
        'site_type' => 'Type B',
        'image' => 'public/images/welcome.jpg',
    ];

    if (empty($newData['image'])) {
        $response = $this->actingAs($user)->put(route('sites.update', ['site' => $site->id]), $newData);
        Cache::forget('site.'.$site->id);
        Cache::forget('sites.index');
        $response->assertRedirect(route('sites.index'));

    } else {
        $newData['image'] = 'public/images/welcome.jpg';
        $response = $this->actingAs($user)->put(route('sites.update', ['site' => $site->id]), $newData);
        Cache::forget('site.'.$site->id);
        Cache::forget('sites.index');
        //$response->assertRedirect(route('sites.index'));
    }

    //TEST 9
    $imagePath = storage_path('public/site_images/welcome.jpg');

    if (! empty($imagePath)) {
        $response = $this->actingAs(User::find(1))
            ->put(route('sites.update', ['site' => $site->id]), [
                'slug' => 'updated-site-slug',
                'name' => 'Updated Site Name',
                'image' => '/public/storage/images/welcome.jpg',
            ]);
        Cache::forget('site.'.$site->id);

        Cache::forget('sites.index');
        //$response->assertRedirect(route('sites.index'));
    }
    //$response->assertRedirect(route('sites.index'));
*/
    //}
}
