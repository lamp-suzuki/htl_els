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
        // // ママインディアンレストラン
        // DB::table('manages')->insert([
        //     [
        //         'name' => 'ママインディアンレストラン',
        //         'domain' => 'mamaindianrestaurant',
        //         'email' => 'kimee0703@gmail.com',
        //         'email_verified_at' => date('Y-m-d H:i:s'),
        //         'tel' => '06-6364-2033',
        //         'fax' => '0663471485',
        //         'password' => Hash::make('vPHWgaJL'),

        //         'delivery_shipping' => 0,
        //         'delivery_preparation' => 60,
        //         'delivery_mon' => '11:30,15:00,17:30,22:00',
        //         'delivery_tue' => '11:30,15:00,17:30,22:00',
        //         'delivery_wed' => '11:30,15:00,17:30,22:00',
        //         'delivery_thu' => '11:30,15:00,17:30,22:00',
        //         'delivery_fri' => '11:30,15:00,17:30,22:00',
        //         'delivery_sat' => '11:30,15:00,17:30,22:00',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
        // DB::table('shops')->insert([
        //     [
        //         'manages_id' => 1,
        //         'name' => '西天満店',
        //         'zipcode' => '530-0047',
        //         'pref' => '大阪府',
        //         'address1' => '大阪市北区西天満４丁目',
        //         'address2' => '５−２３ 豊国ビル105',
        //         'email' => 'h-el-osaka@elsereine.jp',
        //         'tel' => '06-6364-2033',
        //         'fax' => '0663642033',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);

        // // 近江牛と有機野菜の呑処 ひだまり
        // DB::table('manages')->insert([
        //     [
        //         'name' => '近江牛と有機野菜の呑処 ひだまり',
        //         'domain' => 'hidamari',
        //         'email' => 'kokuninniku@gmail.com',
        //         'email_verified_at' => date('Y-m-d H:i:s'),
        //         'tel' => '06-6342-0411',
        //         'fax' => '0663471485',
        //         'password' => Hash::make('Y7udkvLP'),

        //         'delivery_shipping' => 0,
        //         'delivery_preparation' => 60,
        //         'delivery_mon' => '18:00,19:45,20:00,22:00',
        //         'delivery_tue' => '18:00,19:45,20:00,22:00',
        //         'delivery_wed' => '18:00,19:45,20:00,22:00',
        //         'delivery_thu' => '18:00,19:45,20:00,22:00',
        //         'delivery_fri' => '18:00,19:45,20:00,22:00',
        //         'delivery_sat' => '18:00,19:45,20:00,22:00',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
        // DB::table('shops')->insert([
        //     [
        //         'manages_id' => 2,
        //         'name' => '北新地店',
        //         'zipcode' => '530-0002',
        //         'pref' => '大阪府',
        //         'address1' => '大阪市北区曾根崎新地１丁目',
        //         'address2' => '５−９ REXビル501',
        //         'email' => 'h-el-osaka@elsereine.jp',
        //         'tel' => '06-6342-0411',
        //         'fax' => '0663420411',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);

        // // 餃子 新井
        // DB::table('manages')->insert([
        //     [
        //         'name' => '餃子 新井',
        //         'domain' => 'gyoza-arai',
        //         'email' => 'toy3.315033@gmail.com',
        //         'email_verified_at' => date('Y-m-d H:i:s'),
        //         'tel' => '06-6348-3919',
        //         'fax' => '0663471485',
        //         'password' => Hash::make('kA37ELpT'),

        //         'delivery_shipping' => 0,
        //         'delivery_preparation' => 60,
        //         'delivery_mon' => '18:00,19:45,20:00,21:00',
        //         'delivery_tue' => '18:00,19:45,20:00,21:00',
        //         'delivery_wed' => '18:00,19:45,20:00,21:00',
        //         'delivery_thu' => '18:00,19:45,20:00,21:00',
        //         'delivery_fri' => '18:00,19:45,20:00,21:00',
        //         'delivery_sat' => '18:00,19:45,20:00,21:00',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
        // DB::table('shops')->insert([
        //     [
        //         'manages_id' => 3,
        //         'name' => '北新地店',
        //         'zipcode' => '530-0002',
        //         'pref' => '大阪府',
        //         'address1' => '大阪市北区曾根崎新地１丁目',
        //         'address2' => '２ 谷安セストビル',
        //         'email' => 'h-el-osaka@elsereine.jp',
        //         'tel' => '06-6348-3919',
        //         'fax' => '0663483919',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);

        // // 北新地 海味
        // DB::table('manages')->insert([
        //     [
        //         'name' => '北新地 海味',
        //         'domain' => 'kitashinchiumi',
        //         'email' => 'tomokichi.san@icloud.com',
        //         'email_verified_at' => date('Y-m-d H:i:s'),
        //         'tel' => '06-6345-5551',
        //         'fax' => '0663471485',
        //         'password' => Hash::make('K3aXugnV'),

        //         'delivery_shipping' => 0,
        //         'delivery_preparation' => (60*24),
        //         'delivery_mon' => '15:00,19:45,20:00,21:00',
        //         'delivery_tue' => '15:00,19:45,20:00,21:00',
        //         'delivery_wed' => '15:00,19:45,20:00,21:00',
        //         'delivery_thu' => '15:00,19:45,20:00,21:00',
        //         'delivery_fri' => '15:00,19:45,20:00,21:00',
        //         'delivery_sat' => '15:00,19:45,20:00,21:00',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
        // DB::table('shops')->insert([
        //     [
        //         'manages_id' => 4,
        //         'name' => '北新地店',
        //         'zipcode' => '530-0002',
        //         'pref' => '大阪府',
        //         'address1' => '大阪市北区曽根崎新地1丁目',
        //         'address2' => '5-26 永楽リンデルビル1F',
        //         'email' => 'h-el-osaka@elsereine.jp',
        //         'tel' => '06-6345-5551',
        //         // 'fax' => '0663483919',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);

        // // 銀木犀
        // DB::table('manages')->insert([
        //     [
        //         'name' => '銀木犀',
        //         'domain' => 'ginmokusei',
        //         'email' => 'kamata.i@elsereine.jp',
        //         'email_verified_at' => date('Y-m-d H:i:s'),
        //         'tel' => '06-6347-1484',
        //         // 'fax' => '0663471485',
        //         'password' => Hash::make('P9vCz2q3'),

        //         'delivery_shipping' => 0,
        //         'delivery_preparation' => 60,
        //         'delivery_sun' => '11:00,14:45,15:00,17:00',
        //         'delivery_mon' => '11:00,14:45,15:00,17:00',
        //         'delivery_tue' => '11:00,14:45,15:00,17:00',
        //         'delivery_wed' => '11:00,14:45,15:00,17:00',
        //         'delivery_thu' => '11:00,14:45,15:00,17:00',
        //         'delivery_fri' => '11:00,14:45,15:00,17:00',
        //         'delivery_sat' => '11:00,14:45,15:00,17:00',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
        // DB::table('shops')->insert([
        //     [
        //         'manages_id' => 5,
        //         'name' => 'エルセラーン大阪',
        //         'zipcode' => '530-0003',
        //         'pref' => '大阪府',
        //         'address1' => '大阪市北区堂島１丁目',
        //         'address2' => '５−25号 ホテルエルセラーン大阪',
        //         'email' => 'h-el-osaka@elsereine.jp',
        //         'tel' => '06-6347-1484',
        //         // 'fax' => '0663483919',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);

        // // ジューシーズ ラボラトリー
        // DB::table('manages')->insert([
        //     [
        //         'name' => 'ジューシーズ ラボラトリー',
        //         'domain' => 'juicys',
        //         'email' => 'nakanishi@lino-a.com',
        //         'email_verified_at' => date('Y-m-d H:i:s'),
        //         'tel' => '06-7508-5684',
        //         'fax' => '0663471485',
        //         'password' => Hash::make('e3XjPfaD'),

        //         'delivery_shipping' => 0,
        //         'delivery_preparation' => 60,
        //         'delivery_sun' => '11:00,14:45,15:00,17:00',
        //         'delivery_mon' => '11:00,14:45,15:00,17:00',
        //         'delivery_wed' => '11:00,14:45,15:00,17:00',
        //         'delivery_thu' => '11:00,14:45,15:00,17:00',
        //         'delivery_fri' => '11:00,14:45,15:00,17:00',
        //         'delivery_sat' => '11:00,14:45,15:00,17:00',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);
        // DB::table('shops')->insert([
        //     [
        //         'manages_id' => 6,
        //         'name' => '靱本町店',
        //         'zipcode' => '550-0004',
        //         'pref' => '大阪府',
        //         'address1' => '大阪市西区靱本町２丁目',
        //         'address2' => '６−１５ 1F',
        //         'email' => 'nakanishi@lino-a.com',
        //         'tel' => '06-7508-5684',
        //         'fax' => '0675085684',

        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //     ],
        // ]);

        // ジューシーズ ラボラトリー
        DB::table('manages')->insert([
            [
                'name' => 'RUBBERSOUL',
                'domain' => 'rubbersoul',
                'email' => 'rubbersoul@lino-a.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'tel' => '06-6743−4565',
                'fax' => '0663471485',
                'password' => Hash::make('GvHgjRKe'),

                'delivery_shipping' => 0,
                'delivery_preparation' => 45,
                'delivery_sun' => '00:00,01:00,20:00,23:45',
                'delivery_mon' => '00:00,01:00,20:00,23:45',
                'delivery_wed' => '00:00,01:00,20:00,23:45',
                'delivery_thu' => '00:00,01:00,20:00,23:45',
                'delivery_fri' => '00:00,01:00,20:00,23:45',
                'delivery_sat' => '00:00,01:00,20:00,23:45',

                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
        DB::table('shops')->insert([
            [
                'manages_id' => 7,
                'name' => '靱本町店',
                'zipcode' => '530-0003',
                'pref' => '大阪府',
                'address1' => '大阪市北区堂島',
                'address2' => '1-4-26 玉家ビル B1F',
                'email' => 'nakanishi@lino-a.com',
                'tel' => '06-6743−4565',
                'fax' => '0667434565',

                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
