@extends('layouts.shop.app')

@section('content')
<div class="history-back d-md-none">
  <div class="container">
    <a href="{{ route('shop.home') }}">
      <i data-feather="chevron-left"></i>
      <span>メニューに戻る</span>
    </a>
  </div>
</div>

<section class="mv">
  <div class="container">
    <h2>カート内容</h2>
  </div>
</section>
<!-- .mv -->

<form class="pc-two" action="{{ route('shop.order') }}" method="POST" name="nextform" id="cartform">
  <div>
    @csrf
    <div class="cart__list pb-4 pt-md-4">
      <div class="container">
        @if (session()->has('cart.vali'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          選択サービス対象外の商品がカート内にあります。
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        @if ($delivery_shipping_min !== null && $delivery_shipping_min > session('cart.amount'))
        <div class="alert alert-danger fade show" role="alert">
          ご注文は、商品代金が￥{{ number_format($delivery_shipping_min) }}(税込)以上ご注文の場合に限ります。
        </div>
        @endif
        <ol>
          @foreach ($products as $index => $product)
          <li>
            @if (isset($product->thumbnail_1))
            <div class="thumbnail">
              <img src="{{ url('/') }}/{{ $product->thumbnail_1 }}" alt="" />
            </div>
            @endif
            <div class="info">
              <p class="name">{{ $product->name }}</p>
              @if (isset($options[$index]))
              <span class="options">
                @foreach ($options[$index]['name'] as $opt)
                <small class="text-muted mr-1">{{ $opt }}</small>
                @endforeach
              </span>
              @endif
              @php
              if (isset($options[$index])) {
                $opt_price = $options[$index]['price'];
              } else {
                $opt_price = 0;
              }
              @endphp
              <p class="price">{{ number_format(($product->price + $opt_price)*session('cart.products.'.$index.'.quantity')) }}</p>
              <select class="form-control form-control-sm w-auto js-cart-quantity" name="counts" data-quantity="{{ session('cart.products.'.$index.'.quantity') }}" data-index="{{ $index }}" data-price="{{ $product->price + $opt_price }}">
                @for ($i = 1; $i <= 10; $i++)
                <option value="{{ $i }}"@if($i==session('cart.products.'.$index.'.quantity')) selected @endif>{{ $i }}</option>
                @endfor
              </select>
            </div>
            <div class="delete">
              <button class="btn btn-sm btn-primary btn-cartdelete" type="button" data-id="{{ $index }}">削除</button>
            </div>
          </li>
          @endforeach
        </ol>
      </div>
    </div>
    <!-- .cart__list -->
    <div class="cart__delidate pb-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">お受け取りについて</span>
      </h3>
      <div class="container">
        <div class="form-group">
          <label for="changeReceive" class="small d-block">お受け取り方法</label>
          <select id="changeReceive" class="form-control js-vali" name="changeReceive">
            <option value="takeout" @if(session('receipt.service')==='takeout') selected @endif>お持ち帰り(テイクアウト)</option>
            <option value="delivery" @if(session('receipt.service')==='delivery') selected @endif>デリバリー(配達)</option>
          </select>
        </div>
        <div class="form-group">
          <label for="changeDeliveryDate" class="small d-block">お受け取り日時</label>
          <select id="deliveryDate" class="form-control js-vali" name="delivery_date">
            @for ($i = 0; $i <= 6; $i++)
            <option value="{{ date('Y-m-d', strtotime('+'.$i.' day')) }}" @if(session('receipt.date')===date('Y-m-d', strtotime('+'.$i.' day'))) selected @endif>{{ date('Y年n月j日', strtotime('+'.$i.' day')) }}@if($i == 0)（本日）@elseif($i == 1)（明日）@endif</option>
            @endfor
          </select>
        </div>
        <div class="form-group">
          <select id="changedeliveryTime" class="form-control js-vali" name="delivery_time">
            <option value="{{ session('receipt.time') }}">{{ session('receipt.time') }}</option>
          </select>
        </div>
        @if(session('receipt.service')==='takeout')
        <div class="form-group">
          <label for="changeDeliveryDate" class="small d-block">お受け取り店舗</label>
          <select id="changeDeliveryShop" class="form-control js-vali" name="delivery_shop">
            <option>店舗を選択</option>
            @foreach ($shops as $shop)
            <option value="{{ $shop->id }}:{{ $shop->name }}"@if(session('receipt.shop_id') !== null && session('receipt.shop_id') == $shop->id) selected @endif>{{ $shop->name }}</option>
            @endforeach
          </select>
        </div>
        @endif
      </div>
    </div>
    <div class="cart__option">
      <h3 class="ttl-horizon mb-0">
        <span class="d-block container">オプション</span>
      </h3>
      <div id="collapse-wrap" class="collapse-wrap">
        <!-- collapse -->
        <div id="head-okimochi" data-toggle="collapse" data-target="#content-okimochi" aria-expanded="false"
          aria-controls="content-okimochi">
          <span class="d-block container">
            <i data-feather="heart" class="text-primary d-inline-block align-middle mr-2"></i>
            <span class="d-inline-block align-middle">「TakeCafe」に応援金を送る</span>
            <i data-feather="plus-circle" class="position-absolute"></i>
          </span>
        </div>
        <div id="content-okimochi" class="collapse container text-center show" aria-labelledby="head-okimochi"
          data-parent="#collapse-wrap">
          <div class="btn-group okimochi-btns" role="group">
            <button type="button" class="btn btn-outline-secondary active" data-price="0">￥0</button>
            <button type="button" class="btn btn-outline-secondary" data-price="100">￥100</button>
            <button type="button" class="btn btn-outline-secondary" data-price="200">￥200</button>
            <button type="button" class="btn btn-outline-secondary" data-price="500">￥500</button>
            <button type="button" class="btn btn-outline-secondary" data-price="1000">￥1,000</button>
            <input type="hidden" name="okimochi" id="okimochi" value="0">
          </div>
          <p class="text-center mb-0 mt-2 small">応援金は全額お店に送られます。</p>
        </div>
        <!-- collapse -->
      </div>
    </div>
    <!-- .cart__option -->
    <div class="pb-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">その他のご要望</span>
      </h3>
      <div class="container">
        <textarea name="other_content" class="form-control" rows="6"
          placeholder="ご要望やお店に伝えたいことがございましたらご入力ください。">@if(session('form_cart.other_content') !== null){!! e(session('form_cart.other_content')) !!}@endif</textarea>
      </div>
    </div>
  </div>
  <div class="seconds">
    <div class="cart__amount pb-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">合計金額</span>
      </h3>
      <div class="container">
        <table class="w-100 table table-borderless mb-0">
          <tbody>
            <tr>
              <th>小計</th>
              <td>¥ {{ number_format(session('cart.amount')) }}</td>
            </tr>
            @if (session('cart.shipping') !== 0)
            <tr>
              <th>送料</th>
              <td>¥ {{ number_format(session('cart.shipping')) }}</td>
            </tr>
            @endif
          </tbody>
          <tfoot>
            <tr>
              <th>合計</th>
              <td>¥ {{ number_format(session('cart.amount') + session('cart.shipping')) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <div class="py-4 bg-light">
      <div class="container">
        <div class="d-flex justify-content-center form-btns">
          <a class="btn btn-lg bg-white btn-back mr-2" href="{{ route('shop.home') }}">戻る</a>
          <button class="btn btn-lg btn-primary" type="button" data-toggle="modal" data-target="#signup"@if ((session()->has('cart.vali')) || ($delivery_shipping_min !== null && $delivery_shipping_min > session('cart.amount')))disabled @endif>注文へ進む</button>
          {{-- <button class="btn btn-lg btn-primary" type="submit">注文へ進む</button> --}}
        </div>
      </div>
    </div>
  </div>
</form>

<form id="cartdelete" action="{{ route('shop.cart.delete') }}" method="POST">
  @csrf
  <input type="hidden" name="product_id" value="">
</form>

<!-- modal -->
<div class="modal catalog-modal fsmodal fade" id="signup" tabindex="-1" aria-hidden="true" style="max-width:100%">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body rounded-bottom">
        <h3 class="text-center">会員ログイン</h3>
        <div class="text-center">
          <p class="small">会員登録をしない方はこちらからお進みください。<br>次のページで<b>新規会員登録</b>も可能です。</p>
          <button class="btn btn-block btn-primary py-3" id="submitbtn">ログインせず注文</button>
        </div>
        <hr class="my-4" />
        <div class="form-group">
          <label for="" class="small">メールアドレス</label>
          <input type="email" name="login_email" id="login_email" class="form-control" />
        </div>
        <div class="form-group">
          <label for="" class="small">パスワード</label>
          <input type="password" name="login_password" id="login_password" class="form-control" />
        </div>
        <div class="mt-4 text-center">
          <button class="btn btn-dark px-5" type="submit" id="width-login">ログインする</button>
          <p class="small text-center mt-3 mb-0">
            {{-- <a class="text-muted" href="">パスワードをお忘れの方はこちら</a> --}}
          </p>
        </div>
      </div>
      <div class="modal-footer">
        <p class="modal-close" data-dismiss="modal" aria-label="Close">カートに戻る</p>
      </div>
    </div>
  </div>
</div>
<!-- .modal -->

<script language="javascript" type="text/javascript">
  const submitbtn = document.getElementById('submitbtn');
  // 実行
  submitbtn.addEventListener('click', (e) => { // 公開
    e.preventDefault();
    document.nextform.submit();
  });
</script>
@endsection