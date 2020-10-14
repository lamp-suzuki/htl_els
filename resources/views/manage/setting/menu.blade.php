<div class="page-tab">
  <a href="{{ route('manage.setting.basic') }}" @if (\Route::currentRouteName()==='manage.setting.basic' ) class="active" @endif>
    <i data-feather="monitor"></i>
    <span>全体設定</span>
  </a>
  <a href="{{ route('manage.setting.service.index') }}" @if (\Route::currentRouteName()==='manage.setting.service.index' ) class="active" @endif>
    <i data-feather="settings"></i>
    <span>サービス設定</span>
  </a>
</div>