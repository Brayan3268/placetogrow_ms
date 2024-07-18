<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Fieldspaysite;
use Illuminate\Http\Request;
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

    public static function get_fields_site(int $site_id)
    {

        $site_config = Fieldspaysite::where('site_id', $site_id)->get();

        return $site_config;

    }

    public static function add_field_site(Request $request)
    {
        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = $request->name_field;
        $fieldpaysite->name_user_see = $request->name_field_useer_see;
        $fieldpaysite->type = $request->field_type;
        $fieldpaysite->is_optional = $request->is_optional;
        $fieldpaysite->values = $request->values;
        $fieldpaysite->site_id = $request->site_id;
        $fieldpaysite->save();
    }

    public static function delete_field_pay(int $id)
    {
        $field_pay = Fieldspaysite::find($id);
        $site_id = $field_pay->site_id;
        $field_pay->delete();

        return $site_id;
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
