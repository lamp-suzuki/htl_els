@extends('layouts.manage.app')

@section('content')
<h2 class="page-ttl">注文詳細</h2>
<div class="mb-4">
  <h3 class="page-ttl-sub">基本情報</h3>
  <div class="order__detail w-100">
    <div class="order__detail-head">
      <span>{{ $order->created_at }}</span>
      <span>{{ $order->service }}</span>
    </div>
    <div class="order__detail-body">
      <p class="name">
        <span class="furi">{{ $order->furigana }}</span>
        <span>{{ $order->name }}</span>
      </p>
      <table class="table table-sm table-borderless w-auto">
        <tbody>
          <tr>
            <th>注文番号</th>
            <td>{{ $order->id }}</td>
          </tr>
          <tr>
            <th>注文店舗</th>
            <td>{{ $shop->name }}</td>
          </tr>
          <tr>
            <th>受け取り時間</th>
            <td>{{ $order->delivery_time }}</td>
          </tr>
          <tr>
            <th>メールアドレス</th>
            <td>
              <a href="mailto:{{ $order->email }}">{{ $order->email }}</a>
            </td>
          </tr>
          <tr>
            <th>電話番号</th>
            <td>
              <a href="tel:{{ $order->tel }}">{{ $order->tel }}</a>
            </td>
          </tr>
          @if ($order->zipcode !== null)
          <tr>
            <th>住所</th>
            <td>〒{{ $order->zipcode }}
              <br>{{ $order->pref }} {{ $order->address1 }}
              <br>{{ $order->address2 }}</td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
  <!-- .order__detail -->
</div>
<div class="mb-4">
  <h3 class="page-ttl-sub">注文内容</h3>
  <div class="order-menus">
    <div class="order-menus-list">
      @foreach ($products as $product)
      <div class="order-menus-item">
        @if ($product['thumbnail'] != null)
        <div class="thumbnail">
          <img src="{{ url('/') }}/{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" width="150" />
        </div>
        @endif
        <div class="info">
          <p class="name">
            <span class="d-block">{{ $product['name'] }}</span>
            @foreach ($product['options'] as $opt)
            <small class="text-muted mr-1">{{ $opt['name'] }}</small>
            @endforeach
          </p>
          <p class="price">{{ number_format($product['amount']) }}</p>
        </div>
        <div class="count ml-auto">x {{ $product['quantity'] }}</div>
      </div>
      <!-- .order-menus-item -->
      @endforeach
    </div>
    <!-- .order-menus-list -->
    <div class="order-menus-price pb-0">
      <span class="ttl font-weight-normal">送料</span>
      <span class="price">{{ number_format($order->shipping) }}</span>
    </div>
    <div class="order-menus-price pb-0">
      <span class="ttl font-weight-normal">応援金</span>
      <span class="price">{{ number_format($order->okimochi) }}</span>
    </div>
    <div class="order-menus-price">
      <span class="ttl">合計</span>
      <span class="price">{{ number_format($order->total_amount) }}</span>
    </div>
    <!-- .order-menus-price -->
  </div>
  <!-- .order-menus -->
</div>
@endsection