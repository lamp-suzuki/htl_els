@extends('layouts.shop.app')

@section('content')
<section class="mv">
  <div class="container">
    <h2>注文内容の確認</h2>
  </div>
</section>
<div class="mv__step mb-md-5">
  <div class="container">
    <ol class="mv__step-count">
      <li class="visited"><em>情報入力</em></li>
      <li class="visited"><em>お支払い</em></li>
      <li class="current"><em>確認</em></li>
      <li class=""><em>完了</em></li>
    </ol>
  </div>
</div>

<form class="pc-two" action="{{ route('shop.thanks') }}" method="post">
  <div>
    @csrf
    <div class="py-4">
      <div class="container">
        {{-- エラーメッセージ --}}
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        <p>ご登録内容をご確認の上「注文を確定する」ボタンを押して下さい。</p>
        <div class="mb-4">
          <h3 class="form-ttl">受け取りについて</h3>
          <div class="form-group">
            @php
            if (session('receipt.service') == 'takeout') {
                $service = 'お持ち帰り';
            } elseif (session('receipt.service') == 'delivery') {
                $service = 'デリバリー';
            } else {
                $service = 'お取り寄せ';
            }
            @endphp
            <label class="small d-block" for="">お受け取り方法</label>
            <p class="mb-0">{{ $service }}</p>
          </div>
          <div class="form-group">
            <label class="small d-block" for="">お受け取り希望日時</label>
            <p class="mb-0">{{ $receipt['date'] }} {{ $receipt['time'] }}</p>
            <input type="hidden" name="delivery_time" value="{{ $receipt['date'] }} {{ $receipt['time'] }}">
          </div>
          @if (session('receipt.service') == 'takeout')
          <div class="form-group">
            <label class="small d-block" for="">ご注文店舗</label>
            <p class="mb-0">{{ $shop->name }}</p>
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
          </div>
          @endif
        </div>
        <div class="mb-4">
          <h3 class="form-ttl">お客様情報</h3>
          <div class="form-group">
            <label class="small d-block" for="">氏名</label>
            <p class="mb-0">{{ $form_order['name1'] }} {{ $form_order['name2'] }}</p>
            <input type="hidden" name="name" value="{{ $form_order['name1'] }} {{ $form_order['name2'] }}">
          </div>
          <div class="form-group">
            <label class="small d-block" for="">フリガナ</label>
            <p class="mb-0">{{ $form_order['furi1'] }} {{ $form_order['furi2'] }}</p>
            <input type="hidden" name="furigana" value="{{ $form_order['furi1'] }} {{ $form_order['furi2'] }}">
          </div>
          <div class="form-group">
            <label class="small d-block" for="">お部屋番号</label>
            <p class="mb-0">{{ $form_order['room_id'] }}</p>
            <input type="hidden" name="room_id" value="{{ $form_order['room_id'] }}">
          </div>
          <div class="form-group">
            <label class="small d-block" for="">電話番号</label>
            <p class="mb-0">{{ $form_order['tel'] }}</p>
            <input type="hidden" name="tel" value="{{ $form_order['tel'] }}">
          </div>
          <div class="form-group">
            <label class="small d-block" for="">メールアドレス</label>
            <p class="mb-0">{{ $form_order['email'] }}</p>
            <input type="hidden" name="email" value="{{ $form_order['email'] }}">
          </div>
        </div>
        <div class="">
          <h3 class="form-ttl">お支払い方法</h3>
          @if ($payment['pay'] == 1)
          <p class="mb-0">店舗でお支払い</p>
          @else
          <p class="mb-0">クレジットカード払い</p>
          @endif
        </div>
      </div>
    </div>
    <div class="py-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">その他のご要望</span>
      </h3>
      <div class="container">
        <div class="form-group mb-0">
          @if (session('form_cart.other_content') == null || session('form_cart.other_content') == '')
          <p class="mb-0">なし</p>
          @else
          <p class="mb-0">{!! nl2br(e(session('form_cart.other_content'))) !!}</p>
          @endif
          <input type="hidden" name="other_content" value="{{ session('form_cart.other_content') }}">
        </div>
      </div>
    </div>
    <div class="py-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">領収書</span>
      </h3>
      <div class="container">
        <div class="form-group mb-0">
          <p class="mb-0">@if (session('form_payment.set-receipt') == 1)
            <span>あり：{{ session('form_payment.receipt_name') }}</span>
            @else
            <span>なし</span>
            @endif</p>
            <input type="hidden" name="set_receipt" value="{{ session('form_payment.set-receipt') == 1 ? 1 : 0 }}">
            <input type="hidden" name="receipt_name" value="{{ session('form_payment.receipt_name') }}">
        </div>
      </div>
    </div>
    <div class="py-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">注文内容</span>
      </h3>
      <div class="container">
        <div class="cart__list">
          <ol>
            @foreach ($carts as $index => $cart)
            <li>
              <div class="info w-75">
                <p class="name">{{ $cart['name'] }}</p>
                @if (count($cart['options']) > 0)
                <span class="options">
                  @foreach ($cart['options'] as $opt)
                  <small class="text-muted mr-1">{{ $opt }}</small>
                  @endforeach
                </span>
                @endif
                <p class="price mb-0">{{ number_format($cart['price']) }}</p>
              </div>
              <div class="delete">
                <span class="font-weight-bold small">数量：{{ $cart['quantity'] }}</span>
              </div>
            </li>
            <input type="hidden" name="cart_{{ $index }}_id" value="{{ $cart['id'] }}">
            <input type="hidden" name="cart_{{ $index }}_quantity" value="{{ $cart['quantity'] }}">
            <input type="hidden" name="cart_{{ $index }}_options" value="{{ $cart['options_id'] }}">
            @endforeach
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="cart__amount pb-md-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">合計金額</span>
      </h3>
      <div class="container">
        <table class="w-100 table table-borderless mb-0">
          <tbody>
            <tr>
              <th>商品小計</th>
              <td>¥ {{ number_format(session('cart.amount')) }}</td>
            </tr>
            @if (session('cart.shipping') !== 0)
            <tr>
              <th>送料</th>
              <td>¥ {{ number_format(session('cart.shipping')) }}</td>
            </tr>
            @endif
            @if (session('form_payment.use_points') !== 0 && session('form_payment.use_points') !== null)
            <tr>
              <th>ポイント利用</th>
              <td>-¥ {{ number_format(session('form_payment.use_points')) }}</td>
            </tr>
            @endif
            <tr>
              <th>応援金</th>
              <td>¥ {{ number_format(session('cart.okimochi')) }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th>合計</th>
              @if (session('form_payment.use_points') !== 0 && session('form_payment.use_points') !== null)
              <td>¥ {{ number_format(session('cart.amount') + session('cart.shipping')) - session('form_payment.use_points') }}</td>
              @else
              <td>¥ {{ number_format(session('cart.amount') + session('cart.shipping')) }}</td>
              @endif
            </tr>
            @if ($point_flag)
            <tr class="small">
              <th>獲得ポイント</th>
              <td>{{ number_format(floor(session('cart.amount')*0.01)) }}pt</td>
            </tr>
            @endif
          </tfoot>
        </table>
      </div>
    </div>
    <div class="py-4 bg-light">
      <div class="container">
        <div class="d-flex justify-content-center form-btns">
          <a class="btn btn-lg bg-white btn-back mr-2" href="{{ route('shop.payment') }}">戻る</a>
          <button class="btn btn-lg btn-primary" type="submit">注文を確定する</button>
        </div>
      </div>
    </div>

    @isset($payment['payjp-token'])
    <input type="hidden" name="pay_tok" value="{{ $payment['payjp-token'] }}">
    @endisset
    <input type="hidden" name="payment_method" value="{{ $payment['pay'] }}">
    <input type="hidden" name="okimochi" value="{{ session('cart.okimochi') }}">
    <input type="hidden" name="total_amount" value="{{ (session('cart.amount') + session('cart.shipping')) }}">
  </div>
</form>
@endsection