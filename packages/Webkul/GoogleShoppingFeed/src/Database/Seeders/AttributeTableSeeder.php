<?php

namespace Webkul\GoogleShoppingFeed\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeTableSeeder extends Seeder
{

    public function run()
    {

        $now = Carbon::now();

        DB::table('attributes')->insert([
             [
                'id'                  => '37',
                'code'                => 'gtin',
                'admin_name'          => 'GTIN',
                'type'                => 'text',
                'validation'          => NULL,
                'position'            => '28',
                'is_required'         => '0',
                'is_unique'           => '1',
                'value_per_locale'    => '0',
                'value_per_channel'   => '0',
                'is_filterable'       => '0',
                'is_configurable'     => '0',
                'is_user_defined'     => '1',
                'is_visible_on_front' => '1',
                'use_in_flat'         => '1',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_comparable'       => '0',
             ], [
                'id'                  => '38',
                'code'                => 'mpn',
                'admin_name'          => 'MPN',
                'type'                => 'text',
                'validation'          => NULL,
                'position'            => '29',
                'is_required'         => '0',
                'is_unique'           => '0',
                'value_per_locale'    => '0',
                'value_per_channel'   => '0',
                'is_filterable'       => '0',
                'is_configurable'     => '0',
                'is_user_defined'     => '1',
                'is_visible_on_front' => '1',
                'use_in_flat'         => '1',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_comparable'       => '0',
             ],[
                'id'                  => '39',
                'code'                => 'age_group',
                'admin_name'          => 'Age Group',
                'type'                => 'select',
                'validation'          => NULL,
                'position'            => '30',
                'is_required'         => '1',
                'is_unique'           => '0',
                'value_per_locale'    => '0',
                'value_per_channel'   => '0',
                'is_filterable'       => '0',
                'is_configurable'     => '1',
                'is_user_defined'     => '1',
                'is_visible_on_front' => '0',
                'use_in_flat'         => '1',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_comparable'       => '0',
            ], [
                'id'                  => '40',
                'code'                => 'available_for',
                'admin_name'          => 'Product avilable for',
                'type'                => 'select',
                'validation'          => NULL,
                'position'            => '31',
                'is_required'         => '1',
                'is_unique'           => '0',
                'value_per_locale'    => '0',
                'value_per_channel'   => '0',
                'is_filterable'       => '0',
                'is_configurable'     => '1',
                'is_user_defined'     => '1',
                'is_visible_on_front' => '0',
                'use_in_flat'         => '1',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_comparable'       => '0',
            ], [
                'id'                  => '41',
                'code'                => 'condition',
                'admin_name'          => 'Product Condition',
                'type'                => 'select',
                'validation'          => NULL,
                'position'            => '32',
                'is_required'         => '1',
                'is_unique'           => '0',
                'value_per_locale'    => '0',
                'value_per_channel'   => '0',
                'is_filterable'       => '0',
                'is_configurable'     => '1',
                'is_user_defined'     => '1',
                'is_visible_on_front' => '0',
                'use_in_flat'         => '1',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_comparable'       => '0',
            ]
        ]);

        DB::table('attribute_translations')->insert([
         [
            'id'           => '105',
            'locale'       => 'en',
            'name'         => 'GTIN',
            'attribute_id' => '37',
         ],[
            'id'           => '106',
            'locale'       => 'en',
            'name'         => 'MPN',
            'attribute_id' => '38',
         ],[
            'id'           => '107',
            'locale'       => 'en',
            'name'         => 'Age Group',
            'attribute_id' => '39',
         ],[
            'id'           => '108',
            'locale'       => 'en',
            'name'         => 'Product Available For',
            'attribute_id' => '40',
         ],[
            'id'           => '109',
            'locale'       => 'en',
            'name'         => 'Product Condition',
            'attribute_id' => '41',
         ]
        ]);
    }
}