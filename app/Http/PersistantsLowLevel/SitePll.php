<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SitePll extends PersistantLowLevel
{
    public static function get_all_sites()
    {
        $sites = Cache::get('sites.index');
        if (is_null($sites)) {
            $sites = Site::whereIn('site_type', ['open', 'close', 'suscription'])
                ->with('category:id,name')
                ->select('name', 'category_id', 'site_type', 'id')
                ->get();

            Cache::put('sites.index', $sites);
        }

        return $sites;
    }

    public static function get_sites_enum_field_values(string $field)
    {
        return DB::select("SHOW COLUMNS FROM sites WHERE Field = '".$field."'")[0]->Type;
    }

    public static function save_site(Request $request, string $image_name)
    {
        $site = new Site();

        $site->slug = $request->slug;
        $site->name = $request->name;
        $site->document_type = $request->document_type;
        $site->document = $request->document;
        $site->category_id = $request->category;
        $site->expiration_time = $request->expiration_time;
        $site->current_type = $request->current;
        $site->site_type = $request->site_type;
        $site->image = 'storage/site_images/'.$image_name;
        $site->save();
    }

    public static function get_specific_site(string $id)
    {
        $site = Cache::get('site.'.$id);
        if (is_null($site)) {
            $site = Site::find($id);

            Cache::put('site.'.$id, $site);
        }

        return $site;
    }

    public static function update_site(Site $site, $data)
    {
        if (array_key_exists('image', $data)) {
            $site->update([
                'slug' => $data['slug'],
                'name' => $data['name'],
                'document_type' => $data['document_type'],
                'document' => $data['document'],
                'category_id' => $data['category_id'],
                'expiration_time' => $data['expiration_time'],
                'current_type' => $data['current_type'],
                'site_type' => $data['site_type'],
                'image' => $data['image'],
            ]);
        } else {
            $site->update([
                'slug' => $data['slug'],
                'name' => $data['name'],
                'document_type' => $data['document_type'],
                'document' => $data['document'],
                'category_id' => $data['category_id'],
                'expiration_time' => $data['expiration_time'],
                'current_type' => $data['current_type'],
                'site_type' => $data['site_type'],
            ]);
        }
    }

    public static function delete_site(Site $site)
    {
        $site->delete();
    }

    public static function save_cache(string $name, $data)
    {
        Cache::put($name, $data);
    }

    public static function get_cache(string $name)
    {
        return Cache::get($name);
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }
}
