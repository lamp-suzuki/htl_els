<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderThanks;
use App\Mail\OrderAdmin;
use App\Mail\OrderFax;

use Twilio\Rest\Client; // Twilio

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
        $data_total_amount = 0;
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
            $data_product = DB::table('products')->find($request['cart_'.$i.'_id']);
            $cart[] = [
                'product_id' => $request['cart_'.$i.'_id'],
                'product_name' => $data_product->name,
                'product_price' => $data_product->price,
                'quantity' => $request['cart_'.$i.'_quantity'],
                'options' => $options,
                'option_data' => $option_data,
            ];
            $thumbnails[] = $data_product->thumbnail_1;
            $data_price = 0;
            if (count($option_data) > 0) {
                foreach ($option_data as $o_data) {
                    $data_price += $o_data[1] * (int)$request['cart_'.$i.'_quantity'];
                }
            }
            $data_price += $data_product->price * (int)$request['cart_'.$i.'_quantity'];
            $data_total_amount += $data_price;

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
        if ($request->has('shop_id') && $request['shop_id'] !== null) {
            $shop_info = DB::table('shops')->find($request['shop_id']);
            $shops_id = $shop_info->id;
            $shop_fax = $shop_info->fax;
            $shop_email = $shop_info->email;
            $twiml_shop = $shop_info->name;
        } else {
            $temp_shops = DB::table('shops')->where('manages_id', $manages->id)->first();
            $shop_info = null;
            $shops_id = $temp_shops->id;
            $shop_fax = $temp_shops->fax;
            $shop_email = $temp_shops->email;
            $twiml_shop = null;
        }

        // 最終金額計算
        $total_amount = (int)($data_total_amount + (int)session('cart.shipping') + (int)$request['okimochi']);
        if ((int)session('form_payment.use_points') > 0) {
            $use_points = (int)session('form_payment.use_points');
            $total_amount -= $use_points;
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
                // \Payjp\Payjp::setApiKey('sk_test_0b5384bfababd3af6117d2fc');
                \Payjp\Payjp::setApiKey('sk_live_5963e853c01db0b226dea143951c11ffd40be055415d2ec5ea068ae5');
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
        $users_id = null;
        $get_point = 0;

        // 注文データ作成
        try {
            $order_id = DB::table('orders')->insertGetId([
                'manages_id' => $manages->id,
                'shops_id' => $shops_id,
                'carts' => json_encode($cart),
                'service' => $service,
                'okimochi' => (isset($request['okimochi']) && $request['okimochi'] != null) ? (int)$request['okimochi'] : 0,
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
        } catch (\Throwable $th) {
            session()->flash('error', '入力項目をご確認の上再度お試しください。');
            return redirect()->route('shop.confirm');
        }

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

        if ($sub_domain == 'juicys') {
            $hotel_name = 'ホテルエルセラーン大阪';
        } elseif ($sub_domain == 'juicys-castle' || $sub_domain == 'mamaindianrestaurant-castle' || $sub_domain == 'kinjyokaku' || $sub_domain == 'kazenomachi-rouge') {
            $hotel_name = '大阪キャッスルホテル';
        } else {
            $hotel_name = 'ホテルエルセラーン大阪';
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
            'okimochi' => session('cart.okimochi') != null ? session('cart.okimochi') : 0,
            'use_points' => $use_points,
            'get_point' => $get_point,
            'shipping' => $shipping,
            'hotel_name' => $hotel_name,
        ];

        // セッション削除
        $request->session()->forget(['form_payment', 'form_cart', 'form_order', 'receipt', 'cart']);
        $request->session()->regenerateToken();

        // 電話の通知
        if ($manages->noti_tel != null) { // 電話番号があれば
            $tel_noti_flag = true;
            if ($manages->noti_start_time != null && $manages->noti_end_time != null) {
                if (strtotime($manages->noti_start_time) <= strtotime(date('H:i:s')) && strtotime($manages->noti_end_time) >= strtotime(date('H:i:s'))) {
                    $tel_noti_flag = true; // 指定の時間内
                } else {
                    $tel_noti_flag = false; // 指定の時間外
                }
            }
        } else {
            $tel_noti_flag = false;
        }
        if ($tel_noti_flag && ($services == 'takeout' || $services == 'delivery')) {
            $twiml = '<Response><Say language="ja-jp">こんにちは、テイクイーツです。';
            $twiml .= $request['furigana'].'様より';
            if ($twiml_shop != null) {
                $twiml .= $twiml_shop.'へ';
            }
            $twiml .= $service.'のご注文がございます。';
            $twiml .= '</Say></Response>';
            $sid = config("app.twilio_sid");
            $token = config("app.twilio_token");
            try {
                $twilio = new Client($sid, $token);
                $call = $twilio->calls
                    ->create(
                        toInternational($manages->noti_tel), // to
                        config("app.twilio_from"), // from
                        [
                            'phoneNumberSid' => config('app.twilio_phone_sid'),
                            'voice' => 'alice',
                            'language' => 'ja-JP',
                            'timeout' => 30,
                            'twiml' => $twiml,
                        ]
                    );
            } catch (\Throwable $th) {
            }
        }

        // メール送信
        // お客様
        try {
            $subject = '【'.$manages->name.'】ご注文内容のご確認';
            Mail::to($request['email'])->send(new OrderThanks($subject, $manages, $user, $shop_info, $service, $data));
        } catch (\Throwable $th) {
            report($th);
        }

        // 店舗様
        $even_more_bcc = [ // bcc
            'booking@hineli.jp'
        ];
        if ($shop_email != null && $shop_email != '') {
            $even_more_bcc[] = $shop_email;
        }
        if ($sub_domain == 'rubbersoul') {
            $even_more_bcc[] = 'hello-brand-new-world@docomo.ne.jp';
            $even_more_bcc[] = 'guizhil923@gmail.com';
        } elseif ($sub_domain == 'mamaindianrestaurant-castle') {
            $even_more_bcc[] = 'kimee0703@gmail.com';
        }
        try {
            Mail::to($manages->email)
                ->bcc($even_more_bcc)
                ->send(new OrderAdmin($manages, $user, $shop_info, $service, $data));
        } catch (\Throwable $th) {
            report($th);
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
