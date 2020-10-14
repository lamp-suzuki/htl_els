<?php

namespace App\Http\Controllers\Manage\Order;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $manage = Auth::guard('manage')->user();
        $orders = DB::table('orders')->where('manages_id', $manage->id);
        // キーワード検索
        if ($request->has('ketwords') && $request->ketwords != '') {
            $orders->where('service', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('name', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('furigana', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('email', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('tel', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('zipcode', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('pref', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('address1', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('address2', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('memo', 'LIKE', "%{$request->ketwords}%");
        }

        // 期間指定
        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $orders->whereDate('created_at', date('Y-m-d'));
                    break;
                case 'lastday':
                    $orders->whereDate('created_at', date('Y-m-d', strtotime('-1 days')));
                    break;
                case 'thismonth':
                    $orders->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'));
                    break;
                case 'lastmonth':
                    $orders->whereYear('created_at', date('Y', strtotime('-1 months')))->whereMonth('created_at', date('m', strtotime('-1 months')));
                    break;
                default:
                break;
            }
        }

        $result = $orders->orderByDesc('created_at')->paginate(50);

        return view('manage.order.index', [
            'orders' => $result,
            'request' => $request
        ]);
    }

    public function download(Request $request)
    {
        $response = new StreamedResponse(function () use ($request) {
            $manage = Auth::guard('manage')->user();
            $orders = DB::table('orders')->where('manages_id', $manage->id);

            // キーワード検索
            if ($request->has('ketwords') && $request->ketwords != '') {
                $orders->where('service', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('name', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('furigana', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('email', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('tel', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('zipcode', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('pref', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('address1', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('address2', 'LIKE', "%{$request->ketwords}%")
                    ->orWhere('memo', 'LIKE', "%{$request->ketwords}%");
            }

            // 期間指定
            if ($request->has('period')) {
                switch ($request->period) {
                case 'today':
                    $orders->whereDate('created_at', date('Y-m-d'));
                    break;
                case 'lastday':
                    $orders->whereDate('created_at', date('Y-m-d', strtotime('-1 days')));
                    break;
                case 'thismonth':
                    $orders->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'));
                    break;
                case 'lastmonth':
                    $orders->whereYear('created_at', date('Y', strtotime('-1 months')))->whereMonth('created_at', date('m', strtotime('-1 months')));
                    break;
                default:
                break;
            }
            }

            $results = $orders->orderByDesc('created_at')->get();

            $stream = fopen('php://output', 'w');
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            fputcsv($stream, [
                'ID',
                'サービス',
                '注文日時',
                '受け取り日時',
                'お名前',
                'フリガナ',
                'メールアドレス',
                '電話番号',
                'お届け先',
                '送料',
                '応援金',
                '合計金額',
            ]);

            foreach ($results as $result) {
                fputcsv($stream, [
                    $result->id, // 番号
                    $result->service, // サービス
                    $result->created_at, // 注文日時
                    $result->delivery_time, // 受け取り日時
                    $result->name, // お名前
                    $result->furigana, // フリガナ
                    $result->email, // メールアドレス
                    (String)$result->tel, // 電話番号
                    $result->zipcode != null ? '〒'.$result->zipcode.' '.$result->pref.$result->address1.$result->address2 : '', // お届け先
                    $result->shipping, // 送料
                    $result->okimochi, // 応援金
                    $result->total_amount, // 合計金額
                ]);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders_list.csv"');
        return $response;
    }
}
