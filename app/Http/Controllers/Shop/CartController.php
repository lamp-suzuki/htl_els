<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
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
        $manages = DB::table('manages')->where('domain', $sub_domain)->first();
        $shops = DB::table('shops')->where('manages_id', $manages->id)->get();

        // 送料設定
        if (session()->has('receipt.service') && session('receipt.service') != 'takeout') {
            if ($manages->{session('receipt.service').'_shipping_free'} != null && $manages->{session('receipt.service').'_shipping_free'} <= session('cart.amount')) {
                session()->put('cart.shipping', 0);
            } else {
                session()->put('cart.shipping', $manages->{session('receipt.service').'_shipping'});
            }
            // 最低購入金額
            if (session('receipt.service') == 'delivery') {
                $delivery_shipping_min = $manages->delivery_shipping_min;
            } else {
                $delivery_shipping_min = null;
            }
        } else {
            session()->put('cart.shipping', 0);
            $delivery_shipping_min = null;
        }

        $products = [];
        $options = [];
        // カート商品取得
        if (session('cart.products') != null) {
            foreach (session('cart.products') as $index => $product) {
                $products[$index] = DB::table('products')->find($product['id']);
                $temp_price = 0;
                if ($product['options'] !== null) {
                    foreach ($product['options'] as $key => $opt) {
                        $temp = DB::table('options')->find($opt);
                        $temp_price += $temp->price;
                        $options[$index]['name'][] = $temp->name;
                    }
                    $options[$index]['price'] = $temp_price;
                }
            }
            $cart_products = session('cart.products');
            foreach ($cart_products as $key => $product) {
                $flag = DB::table('products')->find($product['id']);
                if ($flag->{session('receipt.service').'_flag'} == 0) {
                    session()->put('cart.vali', 'bad');
                    session()->put('cart.vali_product', $flag->name);
                    break;
                } else {
                    if (session()->has('cart.vali')) {
                        session()->forget('cart.vali');
                        session()->forget('cart.vali_product');
                    }
                }
            }
            if (count($cart_products) < 1) {
                if (session()->has('cart.vali')) {
                    session()->forget('cart.vali');
                    session()->forget('cart.vali_product');
                }
            }
        }

        return view('shop.cart', [
            'manages' => $manages,
            'products' => $products,
            'options' => $options,
            'shops' => $shops,
            'delivery_shipping_min' => $delivery_shipping_min,
        ]);
    }

    // カート商品削除
    public function delete(Request $request)
    {
        $target = session('cart.products')[(int)$request['product_id']];
        $product = DB::table('products')->find($target['id']);
        $price = $product->price;
        if (is_array($target['options'])) {
            foreach ($target['options'] as $key => $option) {
                $opt_temp = DB::table('options')->find($option);
                $price += $opt_temp->price;
            }
        }
        $request->session()->put('cart.amount', session('cart.amount') - $price);
        $request->session()->put('cart.total', session('cart.total') - 1);

        $request->session()->forget('cart.products.'.$request['product_id']);

        return redirect()->route('shop.cart');
    }

    // カート商品数量変更
    public function change_quantity(Request $request)
    {
        $old_price = (int)$request['price'] * (int)$request['old_quantity'];
        $old_amount = (int)session('cart.amount');
        session()->put('cart.products.'.$request['index'].'.quantity', (int)$request['quantity']);
        session()->put('cart.amount', (int)$request['quantity'] * (int)$request['price'] + ($old_amount - $old_price));
    }

    // カート商品購入可能精査
    public function cart_vali()
    {
        $service = session('receipt.service');
        $cart_products = [];
        $cart_products = session('cart.products');
        foreach ($cart_products as $key => $product) {
            $flag = DB::table('products')->find($product['id']);
            if ($flag->{$service.'_flag'} == 0) {
                session()->put('cart.vali', 'bad');
                break;
            } else {
                if (session()->has('cart.vali')) {
                    session()->forget('cart.vali');
                }
            }
        }

        if (session()->has('cart.vali')) {
            return 'bad';
        } else {
            return 'ok';
        }
    }
}
