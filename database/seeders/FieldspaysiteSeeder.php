<?php

namespace Database\Seeders;

use App\Models\Fieldspaysite;
use Illuminate\Database\Seeder;

class FieldspaysiteSeeder extends Seeder
{
    public function run(): void
    {
        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'select';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = 'es_CO,es_EC,es_PR,en_US';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = true;
        $fieldpaysite->site_id = 1;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = '';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = true;
        $fieldpaysite->site_id = 1;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'select';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = 'es_CO,es_EC,es_PR,en_US';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = true;
        $fieldpaysite->site_id = 2;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = '';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = false;
        $fieldpaysite->site_id = 2;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'currency';
        $fieldpaysite->name_user_see = 'Currency for pay';
        $fieldpaysite->type = 'select';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = 'COP,CLP,USD,CRC';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = false;
        $fieldpaysite->site_id = 2;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'select';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = 'es_CO,es_EC,es_PR,en_US';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = true;
        $fieldpaysite->site_id = 3;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->values = '';
        $fieldpaysite->is_mandatory = true;
        $fieldpaysite->is_modify = false;
        $fieldpaysite->site_id = 3;
        $fieldpaysite->save();
    }
}
