<?php

namespace App\Http\Controllers\Manage\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BasicController extends Controller
{
    public function index()
    {
        $manage = Auth::guard('manage')->user();
        $genres = DB::table('genres')->get();
        return view('manage.setting.basic', [
            'manage' => $manage,
            'genres' => $genres,
        ]);
    }

    // 更新
    public function update(Request $request)
    {
        $manage = Auth::guard('manage')->user();
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ])->validate();
        if ($request->has('noti_tel') && $request->noti_tel != null && $request->noti_tel != '') {
            Validator::make($request->all(), [
                'noti_tel' => 'numeric|digits_between:8,11',
            ])->validate();
        }
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('public/uploads/logo');
            $logo = str_replace('public', 'storage', $logo);
        } else {
            $logo = $manage->logo;
        }
        try {
            DB::table('manages')->where('id', $manage->id)->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'tel' => $request['tel'],
                'fax' => $request['fax'],
                'description' => $request['description'],
                'logo' => $logo,
                'genres_id' => $request['genres_id'],
                'takeout_flag' => 0,
                'delivery_flag' => 1,
                'ec_flag' => 0,
                'default_stock' => (int)$request['default_stock'],
                'updated_at' => now(),
                'noti_tel' => isset($request['noti_tel']) && $request['noti_tel'] != '' && $request['noti_tel'] != null ? $request['noti_tel'] : null,
                'noti_start_time' => isset($request['noti_start_time']) && $request['noti_start_time'] != '' && $request['noti_start_time'] != null ? date('H:i:s', strtotime($request['noti_start_time'])) : null,
                'noti_end_time' => isset($request['noti_end_time']) && $request['noti_end_time'] != '' && $request['noti_end_time'] != null ? date('H:i:s', strtotime($request['noti_end_time'])) : null,
            ]);
            session()->flash('message', '更新されました。');
        } catch (\Throwable $th) {
            session()->flash('error', 'エラーが発生しました。');
        }
        return redirect()->route('manage.setting.basic');
    }
}
