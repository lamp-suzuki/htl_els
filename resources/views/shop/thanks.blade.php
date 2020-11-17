@extends('layouts.shop.app')

@section('content')
<div class="thanks py-5 mb-5">
  <div class="container">
    <h2>
      <i data-feather="check-square" width="36" height="36"></i>
      <span>ご注文を受け付けました！</span>
    </h2>
    <div class="thanks-menu">
      @php
      $no = 1;
      @endphp
      @foreach ($thumbnails as $thum)
      @php
      if ($no >= 3) {
          break;
      }
      @endphp
      @if ($thum !== null)
      <div>
        <img src="{{ url('/') }}/{{ $thum }}" />
      </div>
      @php
      ++$no;
      @endphp
      @endif
      @endforeach
    </div>
    <p class="text-center">
      ご注文ありがとうございます！
      <br />
      ご入力いただいたメールアドレス宛に
      <br />
      ご注文内容の詳細をお送りいたしました。
    </p>
    <hr />
    <div class="thanks-date form-group">
      <label for="" class="small d-block">お受け取り希望日時</label>
      <p class="mb-0 font-weight-bold text-primary">{{ $date_time }}</p>
    </div>
    <div class="cart__amount">
      <table class="w-100 table table-borderless">
        <tfoot>
          <tr>
            <th>ご注文金額</th>
            <td>¥ {{ number_format($total_amount) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection