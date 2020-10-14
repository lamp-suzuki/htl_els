@extends('layouts.shop.app')

@section('content')
<section class="mv bg-light">
  <div class="container">
    <h2>店舗情報</h2>
  </div>
</section>
<section class="bg-white py-4 rounded mb-5">
  <div class="container single">
    <table>
      <tbody>
        <tr>
          <th>店名</th>
          <td>{{ $manages->name }}({{ $shops->name }})</td>
        </tr>
        <tr>
          <th>住所</th>
          <td>〒{{ $shops->zipcode }}<br>{{ $shops->address1 }} {{ $shops->address2 }}
          @if ($shops->googlemap_url != null)
          <br>
          <a class="btn btn-outline-secondary text-body py-1" href="{{ $shops->googlemap_url }}" target="_blank">
            <i class="text-primary" data-feather="map"></i>
            <span>GoogleMapでみる</span>
          </a>
          @endif
          </td>
        </tr>
        @if ($shops->access != null)
        <tr>
          <th>アクセス</th>
          <td>{{ $shops->access }}</td>
        </tr>
        @endif
        @if ($shops->parking != null)
        <tr>
          <th>駐車場</th>
          <td>{{ $shops->parking }}</td>
        </tr>
        @endif
        <tr>
          <th>電話番号</th>
          <td>{{ $shops->tel }}</td>
        </tr>
        <tr>
          <th>営業時間</th>
          <td>
            <ul class="mb-0 pl-3">
              <li>日曜日
                @if ($shops->takeout_sun != null)
                {{ explode(',', $shops->takeout_sun)[0] }}〜{{ explode(',', $shops->takeout_sun)[1] }}、{{ explode(',', $shops->takeout_sun)[2] }}〜{{ explode(',', $shops->takeout_sun)[3] }}
                @else
                定休日
                @endif
              </li>
              <li>月曜日
                @if ($shops->takeout_mon != null)
                {{ explode(',', $shops->takeout_mon)[0] }}〜{{ explode(',', $shops->takeout_mon)[1] }}、{{ explode(',', $shops->takeout_mon)[2] }}〜{{ explode(',', $shops->takeout_mon)[3] }}
                @else
                定休日
                @endif
              </li>
              <li>火曜日
                @if ($shops->takeout_tue != null)
                {{ explode(',', $shops->takeout_tue)[0] }}〜{{ explode(',', $shops->takeout_tue)[1] }}、{{ explode(',', $shops->takeout_tue)[2] }}〜{{ explode(',', $shops->takeout_tue)[3] }}
                @else
                定休日
                @endif
              </li>
              <li>水曜日
                @if ($shops->takeout_wed != null)
                {{ explode(',', $shops->takeout_wed)[0] }}〜{{ explode(',', $shops->takeout_wed)[1] }}、{{ explode(',', $shops->takeout_wed)[2] }}〜{{ explode(',', $shops->takeout_wed)[3] }}
                @else
                定休日
                @endif
              </li>
              <li>木曜日
                @if ($shops->takeout_thu != null)
                {{ explode(',', $shops->takeout_thu)[0] }}〜{{ explode(',', $shops->takeout_thu)[1] }}、{{ explode(',', $shops->takeout_thu)[2] }}〜{{ explode(',', $shops->takeout_thu)[3] }}
                @else
                定休日
                @endif
              </li>
              <li>金曜日
                @if ($shops->takeout_fri != null)
                {{ explode(',', $shops->takeout_fri)[0] }}〜{{ explode(',', $shops->takeout_fri)[1] }}、{{ explode(',', $shops->takeout_fri)[2] }}〜{{ explode(',', $shops->takeout_fri)[3] }}
                @else
                定休日
                @endif
              </li>
              <li>土曜日
                @if ($shops->takeout_sat != null)
                {{ explode(',', $shops->takeout_sat)[0] }}〜{{ explode(',', $shops->takeout_sat)[1] }}、{{ explode(',', $shops->takeout_sat)[2] }}〜{{ explode(',', $shops->takeout_sat)[3] }}
                @else
                定休日
                @endif
              </li>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>
@endsection