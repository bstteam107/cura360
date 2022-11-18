<?php

namespace Webkul\GoogleShoppingFeed\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeGroupTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('attribute_groups')->insert([
            [
                'id'                  => '6',
                'name'                => 'Google Shopping',
                'position'            => '11',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1'
            ],
        ]);

        DB::table('attribute_group_mappings')->insert([
            [
                'attribute_id'        => '37',
                'attribute_group_id'  => '6',
                'position'            => '28',
            ],[
                'attribute_id'        => '38',
                'attribute_group_id'  => '6',
                'position'            => '29',
            ],[
                'attribute_id'        => '39',
                'attribute_group_id'  => '6',
                'position'            => '30',
            ],[
                'attribute_id'        => '40',
                'attribute_group_id'  => '6',
                'position'            => '31',
            ],[
                'attribute_id'        => '41',
                'attribute_group_id'  => '6',
                'position'            => '32',
            ]
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}