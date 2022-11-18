<?php

namespace Webkul\GoogleShoppingFeed\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOptionTableSeeder extends Seeder
{

    public function run()
    {

        DB::table('attribute_options')->insert([
            [
                'id'           => '65',
                'admin_name'   => 'Adult',
                'sort_order'   => '1',
                'attribute_id' => '30',
            ], [
                'id'           => '66',
                'admin_name'   => 'Child',
                'sort_order'   => '2',
                'attribute_id' => '30',
            ], [
                'id'           => '67',
                'admin_name'   => 'Male',
                'sort_order'   => '1',
                'attribute_id' => '31',
            ], [
                'id'           => '68',
                'admin_name'   => 'Female',
                'sort_order'   => '2',
                'attribute_id' => '31',
            ], [
                'id'           => '69',
                'admin_name'   => 'Kids',
                'sort_order'   => '3',
                'attribute_id' => '31',
            ], [
                'id'           => '70',
                'admin_name'   => 'New',
                'sort_order'   => '1',
                'attribute_id' => '32',
            ], [
                'id'           => '71',
                'admin_name'   => 'Old',
                'sort_order'   => '2',
                'attribute_id' => '32',
            ],
        ]);

        DB::table('attribute_option_translations')->insert([
            [
                'id'                  => '161',
                'locale'              => 'en',
                'label'               => 'Adult',
                'attribute_option_id' => '65',
            ], [
                'id'                  => '162',
                'locale'              => 'en',
                'label'               => 'Child',
                'attribute_option_id' => '66',
            ], [
                'id'                  => '163',
                'locale'              => 'en',
                'label'               => 'Male',
                'attribute_option_id' => '67',
            ], [
                'id'                  => '164',
                'locale'              => 'en',
                'label'               => 'Female',
                'attribute_option_id' => '68',
            ], [
                'id'                  => '165',
                'locale'              => 'en',
                'label'               => 'Kids',
                'attribute_option_id' => '69',
            ], [
                'id'                  => '166',
                'locale'              => 'en',
                'label'               => 'New',
                'attribute_option_id' => '70',
            ], [
                'id'                  => '167',
                'locale'              => 'en',
                'label'               => 'Old',
                'attribute_option_id' => '71',
            ]
        ]);
    }
}