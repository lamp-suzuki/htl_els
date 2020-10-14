<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfirmController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request['pay'] == 0) {
            Validator::make($request->all(), [
                'payjp-token' => 'required',
            ])->validate();
        }

        Validator::make($request->all(), [
            'pay' => 'required',
        ])->validate();

        $request->session()->put('form_payment', $request->all());

        if (Auth::check()) {
            $url = $_SERVER['HTTP_HOST'];
            $domain_array = explode('.', $url);
            $sub_domain = $domain_array[0];
            $manages = DB::table('manages')->where('domain', $sub_domain)->first();
            $point_flag = $manages->point_flag;
        } else {
            $point_flag = 0;
        }

        $receipt = session('receipt'); // 受け取り設定
        if (session('receipt.service') == 'takeout') {
            $shop = DB::table('shops')->where('id', $receipt['shop_id'])->first(); // 店舗情報
        } else {
            $shop = null; // 店舗情報
        }
        $cart = session('cart'); // カート情報
        $form_order = session('form_order'); // お客様情報
        $payment = $request->all(); // 支払い情報

        $carts = [];
        $amount = 0;
        foreach ($cart['products'] as $product) {
            $item = DB::table('products')->find($product['id']);
            $price = $item->price;
            $options = [];
            $options_id = '';
            if (is_array($product['options'])) {
                foreach ($product['options'] as $option) {
                    $opt_temp = DB::table('options')->find($option);
                    $price += $opt_temp->price;
                    $options[] = $opt_temp->name;
                    $options_id .= $option.',';
                }
            }
            $carts[] = [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $product['quantity'],
                'price' => $price * (int)$product['quantity'],
                'options' => $options,
                'options_id' => $options_id,
            ];
            $amount += $price * (int)$product['quantity']; // 合計金額
        }

        return view('shop.confirm', [
            'receipt' => $receipt,
            'shop' => $shop,
            'carts' => $carts,
            'form_order' => $form_order,
            'payment' => $payment,
            'amount' => $amount,
            'point_flag' => $point_flag,
        ]);
    }
}
