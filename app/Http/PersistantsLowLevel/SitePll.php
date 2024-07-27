<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SitePll extends PersistantLowLevel
{
    public static function get_all_sites()
    {
        $sites = Cache::get('sites.index');
        if (is_null($sites)) {
            $sites = Site::whereIn('site_type', ['open', 'close', 'suscription'])
                ->with('category:id,name')
                ->select('name', 'slug', 'category_id', 'site_type', 'id')
                ->get();

            Cache::put('sites.index', $sites);
        }

        return $sites;
    }

    public static function get_sites_closed()
    {
        $sites = Cache::get('sites.closed');
        if (is_null($sites)) {
            $sites = Site::whereIn('site_type', ['close'])
                ->with('category:id,name')
                ->select('name', 'slug', 'category_id', 'site_type', 'id')
                ->get();

            Cache::put('sites.closed', $sites);
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
        $site->category_id = $request->category;
        $site->expiration_time = $request->expiration_time;
        $site->currency_type = $request->currency;
        $site->site_type = $request->site_type;
        $site->return_url = $request->return_url;
        $site->image = 'storage/site_images/'.$image_name;
        $site->save();

        FieldpaysitePll::save_default_fields($site->id);

        SitePll::forget_cache('sites.index');
        SitePll::forget_cache('sites.closed');
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

    public static function update_site(Site $site, Request $request)
    {
        $data = [
            'slug' => $request['slug'],
            'name' => $request['name'],
            'category_id' => $request['category'],
            'expiration_time' => $request['expiration_time'],
            'currency_type' => $request['currency'],
            'site_type' => $request['site_type'],
            'return_url' => $request['return_url'],
        ];

        if ($request->hasFile('image')) {
            if (Storage::exists(str_replace('storage', 'public', $site->image))) {
                Storage::delete(str_replace('storage', 'public', $site->image));
            }

            $image = $request->file('image');
            $image_name = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/site_images/', $image_name);

            $data['image'] = 'storage/site_images/'.$image_name;
        }

        if (array_key_exists('image', $data)) {
            $site->update([
                'slug' => $data['slug'],
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'expiration_time' => $data['expiration_time'],
                'currency_type' => $data['currency_type'],
                'site_type' => $data['site_type'],
                'return_url' => $data['return_url'],
                'image' => $data['image'],
            ]);
        } else {
            $site->update([
                'slug' => $data['slug'],
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'expiration_time' => $data['expiration_time'],
                'currency_type' => $data['currency_type'],
                'site_type' => $data['site_type'],
                'return_url' => $data['return_url'],
            ]);
        }
        SitePll::forget_cache('site.'.$site->id);
        SitePll::forget_cache('sites.index');
    }

    public static function delete_site(Site $site)
    {
        $site->delete();
        SitePll::forget_cache('site.'.$site->id);
        SitePll::forget_cache('sites.index');
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
