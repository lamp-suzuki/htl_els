@extends('layouts.shop.app')

@section('page_title', 'カート')

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
        {{-- エラーメッセージ --}}
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
        @if (session()->has('cart.vali'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          選択サービス対象外の商品がカート内にあります。<br><small>「{{ session('cart.vali_product') }}」</small>
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
          @if(is_array($products) && count($products) > 0)
          @foreach ($products as $index => $product)
          <li>
            @if (isset($product->thumbnail_1))
            <div class="thumbnail">
              <img src="{{ url($product->thumbnail_1) }}" alt="{{ $product->name }}" />
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
                @for ($i = 1; $i <= 50; $i++)
                <option value="{{ $i }}"@if($i==session('cart.products.'.$index.'.quantity')) selected @endif>{{ $i }}</option>
                @endfor
              </select>
            </div>
            <div class="delete">
              <button class="btn btn-sm btn-primary btn-cartdelete" type="button" data-id="{{ $index }}">削除</button>
            </div>
          </li>
          @endforeach
          @else
          <li>カートの中身は空です。</li>
          @endif
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
          <p>
            @if(session('receipt.service')==='takeout')
            お持ち帰り(テイクアウト)
            @elseif(session('receipt.service')==='delivery')
            デリバリー(配達)
            @elseif(session('receipt.service')==='ec')
            通販(全国配送)
            @endif
          </p>
        </div>
        <div class="form-group">
          <label for="changeDeliveryDate" class="small d-block">お受け取り日時</label>
          <p>{{ date('Y年n月j日', strtotime(session('receipt.date'))) }} {{ session('receipt.time') }}</p>
        </div>
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
            <span class="d-inline-block align-middle">応援金を送る</span>
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
            <tr class="js-cart-amount" data-amount="{{ session('cart.amount') + session('cart.shipping') }}">
              <th>応援金</th>
              <td>¥ <span class="js-okimochi-amount">0</span></td>
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
              <td>¥ <span class="js-cart-total">{{ number_format(session('cart.amount') + session('cart.shipping')) }}</span></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <div class="py-4 bg-light">
      <div class="container">
        <div class="d-flex justify-content-center form-btns">
          <a class="btn btn-lg bg-white btn-back mr-2" href="{{ route('shop.home') }}">戻る</a>
          <button class="btn btn-lg btn-primary" type="submit"
          @if ((session()->has('cart.vali')) || ($delivery_shipping_min !== null && $delivery_shipping_min > session('cart.amount')) || (session('cart.amount') + session('cart.shipping')) <= 0)
          disabled
          @endif
          >注文へ進む</button>
        </div>
      </div>
    </div>
  </div>
</form>

<form id="cartdelete" action="{{ route('shop.cart.delete') }}" method="POST">
  @csrf
  <input type="hidden" name="product_id" value="">
</form>
@endsection