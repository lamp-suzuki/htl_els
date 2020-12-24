<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ジューシーズ ラボラトリー
        // DB::table('manages')->insert([
        //     [
        //         'name' => 'ママインディアンレストラン',
        //         'domain' => 'mamaindianrestaurant-castle',
        //         'email' => 'kmamaindy.castle@gmail.com',
        //         'email_verified_at' => date('Y-m-d H:i:s'),
        //         'tel' => '06-6364-2033',
        //         'fax' => '0663642033',
        //         'password' => Hash::make('mama3318'),
        //         'delivery_shipping' => 0,
        //         'delivery_preparation' => 45,
        //         'delivery_mon' => '11:30,15:00,18:00,22:00',
        //         'delivery_wed' => '11:30,15:00,18:00,22:00',
        //         'delivery_thu' => '11:30,15:00,18:00,22:00',
        //         'delivery_fri' => '11:30,15:00,18:00,22:00',
        //         'delivery_sat' => '11:30,15:00,18:00,22:00',
        //         'delivery_sun' => '11:30,15:00,18:00,22:00',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
        // DB::table('shops')->insert([
        //     [
        //         'manages_id' => 1,
        //         'name' => '西天満',
        //         'zipcode' => '530-0047',
        //         'pref' => '大阪府',
        //         'address1' => '大阪市北区西天満４丁目',
        //         'address2' => '5-23 豊国ビル105号',
        //         'email' => 'yoyaku@osaka-castle.co.jp',
        //         'tel' => '06-6364-2033',
        //         'fax' => '0669469043',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
    }
}
