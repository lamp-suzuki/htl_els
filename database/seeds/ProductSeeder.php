<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // カテゴリーの追加
        DB::table('categories')->insert([
            [
                'manages_id' => 1,
                'name' => 'パスタ・ピザ',
                'sort_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);

        // オプションの追加
        DB::table('options')->insert([
            [
                'categories_id' => 1,
                'name' => '大盛り',
                'price' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'categories_id' => 1,
                'name' => 'ドリンクセット',
                'price' => 150,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);

        // 商品の追加
        DB::table('products')->insert([
            [
                'manages_id' => 1,
                'categories_id' => 1,
                'options_id' => '1,',
                'shops_id' => '1,',
                'name' => 'トマトとエビのパスタ（お持ち帰り）',
                'price' => 980,
                'unit' => '個',
                'explanation' => 'トマトの酸味とエビのうまみが合わさったパスタです。',
                'stock' => 99,
                'lead_time' => 30,
                'status' => 'public',
                'thumbnail_1' => '/images/c1item_1.png',
                'takeout_flag' => 1,
                'delivery_flag' => 0,
                'ec_flag' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'manages_id' => 1,
                'categories_id' => 1,
                'options_id' => '1,2,',
                'shops_id' => '1,',
                'name' => 'バジルソースのパスタ（お持ち帰り）',
                'price' => 980,
                'unit' => '個',
                'explanation' => 'バジルソースのパスタです。',
                'stock' => 99,
                'lead_time' => 30,
                'status' => 'public',
                'thumbnail_1' => '/images/c1item_2.png',
                'takeout_flag' => 1,
                'delivery_flag' => 0,
                'ec_flag' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'manages_id' => 1,
                'categories_id' => 1,
                'options_id' => '1,',
                'shops_id' => '1,',
                'name' => 'トマトとエビのパスタ（デリバリー）',
                'price' => 980,
                'unit' => '個',
                'explanation' => 'トマトの酸味とエビのうまみが合わさったパスタです。',
                'stock' => 99,
                'lead_time' => 30,
                'status' => 'public',
                'thumbnail_1' => '/images/c1item_1.png',
                'takeout_flag' => 0,
                'delivery_flag' => 1,
                'ec_flag' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'manages_id' => 1,
                'categories_id' => 1,
                'options_id' => '1,2,',
                'shops_id' => '1,',
                'name' => 'バジルソースのパスタ（デリバリー）',
                'price' => 980,
                'unit' => '個',
                'explanation' => 'バジルソースのパスタです。',
                'stock' => 99,
                'lead_time' => 30,
                'status' => 'public',
                'thumbnail_1' => '/images/c1item_2.png',
                'takeout_flag' => 0,
                'delivery_flag' => 1,
                'ec_flag' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'manages_id' => 1,
                'categories_id' => 1,
                'options_id' => '1,',
                'shops_id' => '1,',
                'name' => 'トマトとエビのパスタ（お取り寄せ）',
                'price' => 980,
                'unit' => '個',
                'explanation' => 'トマトの酸味とエビのうまみが合わさったパスタです。',
                'stock' => 99,
                'lead_time' => 30,
                'status' => 'public',
                'thumbnail_1' => '/images/c1item_1.png',
                'takeout_flag' => 0,
                'delivery_flag' => 0,
                'ec_flag' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'manages_id' => 1,
                'categories_id' => 1,
                'options_id' => '1,2,',
                'shops_id' => '1,',
                'name' => 'バジルソースのパスタ（お取り寄せ）',
                'price' => 980,
                'unit' => '個',
                'explanation' => 'バジルソースのパスタです。',
                'stock' => 99,
                'lead_time' => 30,
                'status' => 'public',
                'thumbnail_1' => '/images/c1item_2.png',
                'takeout_flag' => 0,
                'delivery_flag' => 0,
                'ec_flag' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
