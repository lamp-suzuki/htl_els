@extends('layouts.shop.app')

@section('content')
<section class="mv">
  <div class="container">
    <h2>お客様情報入力</h2>
  </div>
</section>
<div class="mv__step mb-md-5">
  <div class="container">
    <ol class="mv__step-count">
      <li class="current"><em>情報入力</em></li>
      <li class=""><em>お支払い</em></li>
      <li class=""><em>確認</em></li>
      <li class=""><em>完了</em></li>
    </ol>
  </div>
</div>

<form class="pc-two" action="{{ route('shop.payment') }}" method="POST">
  <div>
    @csrf
    <div class="py-4">
      <div class="container">
        @if ($errors->any())
        <div class="alert alert-danger"><small>入力に誤りまたは未入力があります。</small></div>
        @endif
        <h3 class="form-ttl">お客様情報</h3>
        <div class="form-group">
          <label class="small d-block form-must" for="">氏名</label>
          <div class="row">
            <div class="col">
              @if (isset($users->name))
              <input type="text" class="form-control" id="name1" name="name1" placeholder="姓" value="{{ explode(' ', $users->name)[0] }}" required />
              @else
              <input type="text" class="form-control" id="name1" name="name1" placeholder="姓" value="{{ session('form_order.name1') }}" required />
              @endif
            </div>
            <div class="col">
              @if (isset($users->name))
              <input type="text" class="form-control" id="name2" name="name2" placeholder="名" value="{{ explode(' ', $users->name)[1] }}" required />
              @else
              <input type="text" class="form-control" id="name2" name="name2" placeholder="名" value="{{ session('form_order.name2') }}" required />
              @endif
            </div>
          </div>
        </div>
        <!-- .form-group -->
        <div class="form-group">
          <label class="small d-block form-must" for="">氏名（フリガナ）</label>
          <div class="row">
            <div class="col">
              @if (isset($users->furigana))
              <input type="text" class="form-control" id="furi1" name="furi1" placeholder="セイ" value="{{ explode(' ', $users->furigana)[0] }}" required />
              @else
              <input type="text" class="form-control" id="furi1" name="furi1" placeholder="セイ" value="{{ session('form_order.furi1') }}" required />
              @endif
            </div>
            <div class="col">
              @if (isset($users->furigana))
              <input type="text" class="form-control" id="furi2" name="furi2" placeholder="メイ" value="{{ explode(' ', $users->furigana)[1] }}" required />
              @else
              <input type="text" class="form-control" id="furi2" name="furi2" placeholder="メイ" value="{{ session('form_order.furi2') }}" required />
              @endif
            </div>
          </div>
        </div>
        <!-- .form-group -->
        <div class="form-group">
          <label class="small d-block form-must" for="room_id">お部屋番号</label>
          <input type="text" name="room_id" id="room_id" class="form-control" required>
        </div>
        <!-- .form-group -->
        <div class="form-group">
          <label class="small d-block form-must" for="">電話番号</label>
          @if (isset($users->tel))
          <input type="tel" class="form-control" name="tel" value="{{ $users->tel }}" placeholder="000-0000-0000" required />
          @else
          <input type="tel" class="form-control" name="tel" value="{{ session('form_order.tel') }}" placeholder="000-0000-0000" required />
          @endif
        </div>
        <!-- .form-group -->
        <div class="form-group">
          <label class="small d-block form-must" for="">メールアドレス</label>
          @if (isset($users->email))
          <input type="email" class="form-control" name="email" value="{{ $users->email }}" placeholder="" required />
          @else
          <input type="email" class="form-control" name="email" value="{{ session('form_order.email') }}" placeholder="" required />
          @endif
        </div>
        <!-- .form-group -->
        <div class="form-group">
          <label class="small d-block form-must" for="">メールアドレス（確認）</label>
          <input type="email" class="form-control" name="email_confirmation" value="" placeholder="" required />
        </div>
        <!-- .form-group -->
      </div>
    </div>
  </div>
  <div>
    @if (!isset($users))
    {{-- <div class="pt-4 pt-md-0 pb-4">
      <h3 class="ttl-horizon">
        <span class="d-block container">会員について</span>
      </h3>
      <div class="container">
        @if (!Auth::check())
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="memberCheck" name="member_check" value="1" />
          <label class="form-check-label" for="memberCheck">この内容で会員登録する</label>
        </div>
        @endif
        <div class="takeeats-bbox">
          <p class="m-0">
            <i data-feather="check-square" class="text-primary mr-1"></i>
            <span class="small">次回からの注文が簡単に！</span>
          </p>
          <p class="m-0">
            <i data-feather="check-square" class="text-primary mr-1"></i>
            <span class="small">ポイントを貯めてお得に注文！</span>
          </p>
        </div>
      </div>
    </div> --}}
    @endif
    <div class="py-4 bg-light">
      <div class="container">
        <div class="d-flex justify-content-center form-btns">
          <a class="btn btn-lg bg-white btn-back mr-2" href="{{ route('shop.cart') }}">戻る</a>
          <button class="btn btn-lg btn-primary" type="submit">お支払い情報入力へ</button>
        </div>
      </div>
    </div>
  </div>
</form>

<script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="//jpostal-1006.appspot.com/jquery.jpostal.js"></script>
<script>
$('#zipcode').jpostal({
  postcode : [
    '#zipcode' // 郵便番号のid名
  ],
  address : {
    '#pref' : '%3', // %3 = 都道府県
    '#address1' : '%4%5', // %4 = 市区町村, %5 = 町名
  }
});
</script>
@endsection