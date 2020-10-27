<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderThanks;
use App\Mail\OrderAdmin;
use App\Mail\OrderFax;
use App\Mail\CreateUser;

class ThanksController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $url = $_SERVER['HTTP_HOST'];
        $domain_array = explode('.', $url);
        $sub_domain = $domain_array[0];
        $manages = DB::table('manages')->where('domain', $sub_domain)->first(); // 店舗アカウント

        if (session('receipt.service') == 'takeout') {
            $service = 'お持ち帰り';
            $services = 'takeout';
        } elseif (session('receipt.service') == 'delivery') {
            $service = 'デリバリー';
            $services = 'delivery';
        } else {
            $service = 'お取り寄せ';
            $services = 'ec';
        }

        // 商品情報
        $i = 0;
        $cart = [];
        $thumbnails = [];
        while ($request->has('cart_'.$i.'_id')) {
            $options = [];
            $option_data = [];
            if ($request['cart_'.$i.'_options'] !== null) {
                foreach (explode(',', $request['cart_'.$i.'_options']) as $opt_id) {
                    if ($opt_id != '') {
                        $options[] = (int)$opt_id;
                        $temp_opt = DB::table('options')->find((int)$opt_id);
                        $option_data[] = [$temp_opt->name, $temp_opt->price];
                    }
                }
            }
            $cart[] = [
                'product_id' => $request['cart_'.$i.'_id'],
                'quantity' => $request['cart_'.$i.'_quantity'],
                'options' => $options,
            ];
            $data_product = DB::table('products')->find($request['cart_'.$i.'_id']);
            $thumbnails[] = $data_product->thumbnail_1;
            $data_price = 0;
            if (count($option_data) > 0) {
                foreach ($option_data as $o_data) {
                    $data_price += ($data_product->price + $o_data[1]) * (int)$request['cart_'.$i.'_quantity'];
                }
            } else {
                $data_price += $data_product->price;
            }

            $data['carts'][] = [
                'name' => $data_product->name,
                'quantity' => $request['cart_'.$i.'_quantity'],
                'price' => $data_product->price,
                'amount' => $data_price,
                'options' => $option_data,
            ];
            ++$i;
        }

        // 店舗ID設定
        $shop_fax = '';
        $shop_email = '';
        if ($request['shop_id'] !== null) {
            $shop_info = DB::table('shops')->find($request['shop_id']);
            $shops_id = $shop_info->id;
            $shop_fax = $shop_info->fax;
            $shop_email = $shop_info->email;
        } else {
            $temp_shops = DB::table('shops')->where('manages_id', $manages->id)->first();
            $shop_info = null;
            $shops_id = $temp_shops->id;
            $shop_fax = $temp_shops->fax;
            $shop_email = $temp_shops->email;
        }

        // 最終金額計算
        $total_amount = $request['total_amount'] + (int)session('cart.shipping');
        if ((int)session('form_payment.use_points') !== 0) {
            $total_amount -= (int)session('form_payment.use_points');
            $use_points = (int)session('form_payment.use_points');
        } else {
            $use_points = 0;
        }
        // 送料設定
        if ((int)session('cart.shipping') !== 0) {
            $shipping = (int)session('cart.shipping');
        } else {
            $shipping = 0;
        }

        $data['total_amount'] = $total_amount;
        $data['date_time'] = $request['delivery_time'];

        // 決済処理
        if (session('form_payment.pay') == 0) {
            if (session('form_payment.payjp-token') != null) {
                // テスト
                \Payjp\Payjp::setApiKey('sk_test_0b5384bfababd3af6117d2fc');
                // 本番
                // \Payjp\Payjp::setApiKey('sk_live_5963e853c01db0b226dea143951c11ffd40be055415d2ec5ea068ae5');
                try {
                    $charge = \Payjp\Charge::create(array(
                        "card" => session('form_payment.payjp-token'),
                        "amount" => $total_amount,
                        "currency" => "jpy",
                        "capture" => true,
                        "description" => $manages->name,
                    ));
                } catch (\Throwable $th) {
                    session()->flash('error', 'クレジットカード決済ができませんでした。クレジットカード情報をご確認の上、再度お試しください。');
                    return redirect()->route('shop.confirm');
                }
            } else {
                session()->flash('error', 'クレジットカード決済ができませんでした。クレジットカード情報をご確認の上、再度お試しください。');
                return redirect()->route('shop.confirm');
            }
        }

        // 会員処理
        if (Auth::check()) {
            $users_id = Auth::id();
            // ポイント付与
            if ($manages->point_flag) {
                DB::table('points')->updateOrInsert(
                    ['manages_id' => $manages->id, 'users_id' => $users_id],
                    [
                        'count' => +floor($total_amount*0.01),
                        'updated_at' => now(),
                    ]
                );
                $get_point = floor($total_amount*0.01);
                if (session('form_payment.use_points') > 0) {
                    DB::table('points')
                    ->where(['manages_id' => $manages->id, 'users_id' => $users_id])
                    ->update(
                        ['count' => -(int)session('form_payment.use_points')]
                    );
                }
            } else {
                $get_point = 0;
            }
        } else {
            $users_id = null;
            $get_point = 0;
        }

        // 注文データ作成
        $order_id = DB::table('orders')->insertGetId([
            'manages_id' => $manages->id,
            'shops_id' => $shops_id,
            'carts' => json_encode($cart),
            'service' => $service,
            'okimochi' => (isset($request['okimochi'])&&$request['okimochi'] != null) ? $request['okimochi'] : 0,
            'shipping' => $shipping,
            'delivery_time' => $request['delivery_time'],
            'total_amount' => $total_amount,
            'payment_method' => $request['payment_method'],
            'users_id' => $users_id,
            'name' => $request['name'],
            'furigana' => $request['furigana'],
            'tel' => $request['tel'],
            'email' => $request['email'],
            'zipcode' => $request['zipcode'],
            'pref' => $request['pref'],
            'address1' => $request['address1'],
            'address2' => $request['address2'],

            'charge_id' => isset($charge) ? $charge->id : null,

            'receipt' => $request['set_receipt'],
            'receipt_name' => $request['receipt_name'] != null ? $request['receipt_name'] : null,
            'other_content' => $request['other_content'],

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 在庫処理
        foreach ($cart as $item) {
            DB::table('stock_customers')->insert([
                'manages_id' => $manages->id,
                'products_id' => $item['product_id'],
                'orders_id' => $order_id,
                'shops_id' => $shops_id,
                'stock' => (int)$item['quantity'],
                'date' => date('Y-m-d', strtotime(session('receipt.date')))
            ]);
        }

        // メール用データ
        $user = [
            'name' => $request['name'],
            'furigana' => $request['furigana'],
            'room_id' => $request['room_id'],
            'tel' => $request['tel'],
            'email' => $request['email'],
            'zipcode' => $request['zipcode'],
            'pref' => $request['pref'],
            'address1' => $request['address1'],
            'address2' => $request['address2'],
            'payment' => $request['payment_method'] == 0 ? 'クレジットカード決済' : '店舗でお支払い',
            'receipt' => $request['set_receipt'] == 0 ? 'なし' : 'あり',
            'receipt_name' => $request['receipt_name'] != null ? $request['receipt_name'] : '',
            'other' => session('form_cart.other_content') != null ? session('form_cart.other_content') : 'なし',
            'okimochi' => session('form_cart.okimochi'),
            'use_points' => $use_points,
            'get_point' => $get_point,
            'shipping' => $shipping,
        ];

        // 会員登録処理
        if (session('form_order.member_check') == 1) {
            $pass = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 8);
            $users_id = DB::table('users')->insertGetId([
                'name' => $request['name'],
                'furigana' => $request['furigana'],
                'tel' => $request['tel'],
                'email' => $request['email'],
                'zipcode' => $request['zipcode'],
                'pref' => $request['pref'],
                'address1' => $request['address1'],
                'address2' => $request['address2'],
                'password' => Hash::make($pass),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $register_user = DB::table('users')->find($users_id);
            // メール送信
            $subject_user = '【'.$manages->name.'】会員登録完了のお知らせ';
            Mail::to($request['email'])->send(new CreateUser($subject_user, $register_user, $manages, $pass));
        }

        // セッション削除
        $request->session()->forget(['form_payment', 'form_cart', 'form_order', 'receipt', 'cart']);
        $request->session()->regenerateToken();

        // メール送信
        // お客様
        try {
            $subject = '【'.$manages->name.'】ご注文内容のご確認';
            Mail::to($request['email'])->send(new OrderThanks($subject, $manages, $user, $shop_info, $service, $data));
        } catch (\Throwable $th) {
            report($th);
        }

        // 店舗様
        try {
            Mail::to($manages->email)->send(new OrderAdmin($manages, $user, $shop_info, $service, $data));
        } catch (\Throwable $th) {
            report($th);
        }
        if ($shop_email != null && $shop_email != '') {
            try {
                Mail::to($shop_email)->send(new OrderAdmin($manages, $user, $shop_info, $service, $data));
            } catch (\Throwable $th) {
                report($th);
            }
        }

        // FAX
        if ($manages->fax != null && $manages->fax != '') {
            $tofax = str_replace('-', '', $manages->fax);
            try {
                Mail::to('fax843780@ecofax.jp')->send(new OrderFax($tofax, $manages, $user, $shop_info, $service, $data));
            } catch (\Throwable $th) {
                report($th);
            }
        }
        if ($shop_fax != null && $shop_fax != '') {
            $tofax = str_replace('-', '', $shop_fax);
            try {
                Mail::to('fax843780@ecofax.jp')->send(new OrderFax($tofax, $manages, $user, $shop_info, $service, $data));
            } catch (\Throwable $th) {
                report($th);
            }
        }

        return view('shop.thanks', [
            'order_id' => $order_id,
            'cart' => $cart,
            'date_time' => $request['delivery_time'],
            'total_amount' => $total_amount,
            'service' => $services,
            'thumbnails' => $thumbnails,
            'shop_info' => $shop_info,
            'get_point' => $get_point,
        ]);
    }
}
