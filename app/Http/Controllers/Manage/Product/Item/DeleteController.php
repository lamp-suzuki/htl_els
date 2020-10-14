<?php

namespace App\Http\Controllers\Manage\Product\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    public function index(Request $request)
    {
        try {
            DB::table('products')->where('id', $request['menu_id'])->delete();
            session()->flash('message', '商品が削除されました。');
        } catch (\Throwable $th) {
            // echo $th;
            session()->flash('error', 'エラーが発生しました。');
        }
        return redirect()->route('manage.product.index');
    }
}
