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
        // 錦城閣
        DB::table('manages')->insert([
            [
                'name' => '錦城閣',
                'domain' => 'kinjyokaku',
                'email' => 'info@kinjyokaku.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'tel' => '06-6941-2185',
                'fax' => '0669412187',
                'password' => Hash::make('e6X4uCZB'),
                'delivery_shipping' => 0,
                'delivery_preparation' => 60,
                'delivery_mon' => '17:00,18:45,19:00,20:00',
                'delivery_tue' => '17:00,18:45,19:00,20:00',
                'delivery_wed' => '17:00,18:45,19:00,20:00',
                'delivery_thu' => '17:00,18:45,19:00,20:00',
                'delivery_fri' => '17:00,18:45,19:00,20:00',
                'delivery_sat' => '17:00,18:45,19:00,20:00',
                'delivery_sun' => '17:00,18:45,19:00,20:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
        DB::table('shops')->insert([
            [
                'manages_id' => 8,
                'name' => 'キャッスルホテル',
                'zipcode' => '540-0032',
                'pref' => '大阪府',
                'address1' => '大阪市中央区天満橋京町',
                'address2' => '1番1号大阪キャッスルホテル3F',
                'email' => 'yoyaku@osaka-castle.co.jp',
                'tel' => '06-6941-2185',
                'fax' => '0669469043',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);

        // 風の街 ルージュ
        DB::table('manages')->insert([
            [
                'name' => '風の街 ルージュ',
                'domain' => 'kazenomachi-rouge',
                'email' => 'kaze.tenmabashi@gmail.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'tel' => '06-6947-0450',
                'fax' => '0669412187',
                'password' => Hash::make('eH76FUdN'),
                'delivery_shipping' => 0,
                'delivery_preparation' => 60,
                'delivery_mon' => '17:30,18:45,19:00,21:00',
                'delivery_tue' => '17:30,18:45,19:00,21:00',
                'delivery_wed' => '17:30,18:45,19:00,21:00',
                'delivery_thu' => '17:30,18:45,19:00,21:00',
                'delivery_fri' => '17:30,18:45,19:00,21:00',
                'delivery_sat' => '17:30,18:45,19:00,21:00',
                'delivery_sun' => '17:30,18:45,19:00,21:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
        DB::table('shops')->insert([
            [
                'manages_id' => 9,
                'name' => '京阪シティモール',
                'zipcode' => '540-0032',
                'pref' => '大阪府',
                'address1' => '大阪市中央区天満橋京町',
                'address2' => '1-1京阪シティモール8階',
                'email' => 'yoyaku@osaka-castle.co.jp',
                'tel' => '06-6947-0450',
                'fax' => '0669469043',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);

        // ジューシーズ ラボラトリー
        DB::table('manages')->insert([
            [
                'name' => 'ジューシーズ ラボラトリー',
                'domain' => 'juicys-castle',
                'email' => 'juicys.castle@gmail.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'tel' => '06-7508-5684',
                'fax' => '0675085684',
                'password' => Hash::make('cB6ZSinr'),
                'delivery_shipping' => 0,
                'delivery_preparation' => 60,
                'delivery_mon' => '11:00,14:45,15:00,18:00',
                'delivery_wed' => '11:00,14:45,15:00,18:00',
                'delivery_thu' => '11:00,14:45,15:00,18:00',
                'delivery_fri' => '11:00,14:45,15:00,18:00',
                'delivery_sat' => '11:00,14:45,15:00,18:00',
                'delivery_sun' => '11:00,14:45,15:00,18:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
        DB::table('shops')->insert([
            [
                'manages_id' => 10,
                'name' => '靱本町',
                'zipcode' => '550-0004',
                'pref' => '大阪府',
                'address1' => '大阪府大阪市西区靱本町',
                'address2' => '2-6-15 1F',
                'email' => 'yoyaku@osaka-castle.co.jp',
                'tel' => '06-7508-5684',
                'fax' => '0669469043',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
