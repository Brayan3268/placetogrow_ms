<?php

namespace Database\Seeders;

use App\Models\Fieldspaysite;
use Illuminate\Database\Seeder;

class FieldspaysiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'description';
        $fieldpaysite->name_user_see = 'Pay\'s description';
        $fieldpaysite->type = 'text';
        $fieldpaysite->is_optional = true;
        $fieldpaysite->is_user_see = false;
        $fieldpaysite->site_id = 1;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'description';
        $fieldpaysite->name_user_see = 'Pay\'s description';
        $fieldpaysite->type = 'text';
        $fieldpaysite->is_optional = true;
        $fieldpaysite->is_user_see = false;
        $fieldpaysite->site_id = 2;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'description';
        $fieldpaysite->name_user_see = 'Pay\'s description';
        $fieldpaysite->type = 'text';
        $fieldpaysite->is_optional = true;
        $fieldpaysite->is_user_see = false;
        $fieldpaysite->site_id = 3;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'radio';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = 1;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'radio';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = 2;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'locale';
        $fieldpaysite->name_user_see = 'Language\'s session';
        $fieldpaysite->type = 'radio';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = 3;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = 1;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = 2;
        $fieldpaysite->save();

        $fieldpaysite = new Fieldspaysite();

        $fieldpaysite->name = 'total';
        $fieldpaysite->name_user_see = 'Amount to pay';
        $fieldpaysite->type = 'number';
        $fieldpaysite->is_optional = false;
        $fieldpaysite->is_user_see = true;
        $fieldpaysite->site_id = 3;
        $fieldpaysite->save();
    }
}
