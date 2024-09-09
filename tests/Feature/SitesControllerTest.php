<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SitesControllerTest extends TestCase
{
    use RefreshDatabase;

    private function seed_db()
    {
        Artisan::call('db:seed');
    }

    public function testItCannotListSitesWithUnauthenticated(): void
    {
        $response = $this->get(route('sites.index'));

        $response->assertRedirect(route('login'));
    }

    public function testItCanListSitesWithAuthenticated(): void
    {
        $this->seed_db();

        //TEST 1
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->get(route('sites.index'));

        $response->assertOk();

        //TEST 2
        $user = User::find(1);
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
        $user = User::find(3);
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

    }
}
