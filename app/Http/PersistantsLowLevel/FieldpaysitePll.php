<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Fieldspaysite;
use Illuminate\Support\Facades\Cache;

class FieldpaysitePll extends PersistantLowLevel
{
    public static function save_default_fields(int $site_id)
    {
        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'description';
        $fieldpaysite->name_user_see = 'Pay\'s description';
        $fieldpaysite->type = 'text';
        $fieldpaysite->is_optional = true;
        $fieldpaysite->is_user_see = false;
        $fieldpaysite->site_id = $site_id;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'radio';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = $site_id;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = $site_id;
        $fieldpaysite->save();
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
