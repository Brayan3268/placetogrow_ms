<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Fieldspaysite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FieldpaysitePll extends PersistantLowLevel
{
    private const SECONDS = 300;

    public static function save_default_fields(int $site_id, string $site_type)
    {
        $fieldpaysite = new Fieldspaysite;

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'select';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = 'es_CO,es_EC,es_PR,en_US';
        $fieldpaysite->site_id = $site_id;
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = true;
        $fieldpaysite->save();

        if ($site_type == 'CLOSED') {
            $fieldpaysite = new Fieldspaysite;

            $fieldpaysite->name = 'currency';
            $fieldpaysite->name_user_see = 'Currency for pay';
            $fieldpaysite->type = 'select';
            $fieldpaysite->is_optional = false;
            $fieldpaysite->values = 'COP,CLP,USD,CRC';
            $fieldpaysite->is_mandatory = true;
            $fieldpaysite->is_modify = false;
            $fieldpaysite->site_id = $site_id;
            $fieldpaysite->save();
        }

        $fieldpaysite = new Fieldspaysite;

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = '';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = ($site_type == 'CLOSED') ? false : true;
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
        $fieldpaysite = new Fieldspaysite;

        $fieldpaysite->name = $request->name_field;
        $fieldpaysite->name_user_see = $request->name_field_user_see;
        $fieldpaysite->type = $request->field_type;
        $fieldpaysite->is_optional = $request->is_optional;
        $fieldpaysite->values = $request->values;
        $fieldpaysite->is_mandatory = false;
        $fieldpaysite->site_id = $request->site_id;
        $fieldpaysite->is_modify = $request->is_modify;
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
        Cache::remember($name, self::SECONDS, function () use ($data) {
            return $data;
        });
    }

    public static function get_cache(string $name)
    {
        return Cache::remember($name, self::SECONDS, function () use ($name) {
            return Cache::get($name);
        });
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }
}
