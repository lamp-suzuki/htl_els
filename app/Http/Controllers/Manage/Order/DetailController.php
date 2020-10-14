<?php

namespace App\Http\Controllers\Manage\Order;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function index($id)
    {
        $order = DB::table('orders')->find($id);
        $shop = DB::table('shops')->find($order->shops_id);

        $products = [];
        foreach (json_decode($order->carts) as $data) {
            $product = DB::table('products')->find($data->product_id);
            $options = [];
            $amount = 0;
            $amount += $product->price;
            foreach ($data->options as $key => $opt_id) {
                $opt = DB::table('options')->find($opt_id);
                $options[] = [
                    'name' => $opt->name,
                    'price' => $opt->price,
                ];
                $amount += $opt->price;
            }
            $amount *= (int)$data->quantity;
            $products[] = [
                'name' => $product->name,
                'thumbnail' => $product->thumbnail_1,
                'quantity' => (int)$data->quantity,
                'amount' => $amount,
                'options' => $options,
            ];
        }

        // dd($order);

        return view('manage.order.detail', [
            'order' => $order,
            'shop' => $shop,
            'products' => $products,
        ]);
    }
}
