@php
  use \App\Enums\Authority;
  use \App\Enums\Permission;
@endphp

@extends('adminlte::master')

@section('adminlte_css')
  <link rel="stylesheet"
        href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('css')
  @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
  'boxed' => 'layout-boxed',
  'fixed' => 'fixed',
  'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
      @if(config('adminlte.layout') == 'top-nav')
      <nav class="navbar navbar-static-top">
        <div class="container">
          <div class="navbar-header">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
              {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
            </a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
              <i class="fa fa-bars"></i>
            </button>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
            <ul class="nav navbar-nav">
              @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
            </ul>
          </div>
          <!-- /.navbar-collapse -->
      @else
      <!-- Logo -->
      <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
        </a>
      @endif
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">

          <ul class="nav navbar-nav">
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                >
                  <i class="fa fa-fw fa-power-off"></i> ログアウト
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                  @endif
                  {{ csrf_field() }}
                </form>
            </li>
          </ul>
        </div>
        @if(config('adminlte.layout') == 'top-nav')
        </div>
        @endif
      </nav>
    </header>

    @if(config('adminlte.layout') != 'top-nav')
    <aside class="main-sidebar">
      <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
          {{-- EPARKスタッフの機能 --}}
          @if(Auth::user()->getTable() == "staffs")
            <li class="treeview @if (!request()->session()->get('hospital_id')) active @endif">
              <a href="#">
                <i class="fa fa-list-ul"></i> <span>EPARKスタッフ機能</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                @if (Auth::user()->authority->value !== Authority::CONTRACT_STAFF && Auth::user()->staff_auth->is_hospital !== Permission::NONE)
                  <li class="{{ Request::segment(1) === 'hospital' && request()->path() !== 'hospital/contract' ? 'active' : null }}"><a href="/hospital"><i class="fa fa-hospital-o"></i>&nbsp;&nbsp;&nbsp;&nbsp;医療機関管理</a></li>
                @endif
                @if (Auth::user()->authority->value !== Authority::CONTRACT_STAFF && Auth::user()->staff_auth->is_staff !== Permission::NONE)
                  <li class="{{ Request::segment(1) === 'staff' && request()->path() !== 'staff/edit-password-personal' ? 'active' : null }}"><a href="/staff"><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;&nbsp;スタッフ管理</a></li>
                @endif
                @if (Auth::user()->authority->value !== Authority::CONTRACT_STAFF && Auth::user()->staff_auth->is_cource_classification !== Permission::NONE)
                  <li class="{{ Request::segment(1) === 'classification' ? 'active' : null }}"><a href="/classification"><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;&nbsp;検査コース分類管理</a></li>
                @endif
                @if (Auth::user()->authority->value !== Authority::CONTRACT_STAFF && Auth::user()->staff_auth->is_pre_account !== Permission::NONE)
                  <li class="{{ Request::segment(1) === '#' ? 'active' : null }}"><a href="/#"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;&nbsp;&nbsp;事前決済管理</a></li>
                @endif
                @if (Auth::user()->authority->value === Authority::CONTRACT_STAFF)
                  <li class="{{ request()->path() === 'hospital/contract' ? 'active' : null }}"><a href="/hospital/contract"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;&nbsp;&nbsp;契約管理</a></li>
                @endif
                @if(Auth::user()->getTable() == "staffs")
                  <li class="{{ request()->path() === 'staff/edit-password-personal' ? 'active' : null }}"><a href="/staff/edit-password-personal"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;パスワードの変更</a></li>
                @endif
                @if (Auth::user()->authority->value !== Authority::CONTRACT_STAFF && Auth::user()->staff_auth->is_invoice !== Permission::NONE)
                  <li class="{ ( request()->is(['billing/*', 'billing'])  ) ? 'active' : '' }}"><a href="/billing"><i class="fa fa-dollar"></i>&nbsp;&nbsp;&nbsp;&nbsp;請求管理</a></li>
                @endif
              </ul>
          @endif
          {{-- 医療機関スタッフの機能 --}}
          @if ( request()->session()->get('hospital_id'))
            <li class="treeview @if (request()->session()->get('hospital_id')) active @endif">
                <a href="#">
                  <i class="fa fa-list-ul"></i> <span>医療機関スタッフ機能</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li class="{{ request()->path()  !== 'hospital-staff/edit-password' && Request::segment(1) === 'hospital-staff' ? 'active' : null }}"><a href="/hospital-staff"><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;&nbsp;医療機関スタッフ管理</a></li>
                  <li class="{{ Request::segment(1) === 'customer' ? 'active' : null }}"><a href="/customer"><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;&nbsp;顧客管理</a></li>
                  <li class="{{ Request::segment(1) === '#' ? 'active' : null }}"><a href="#"><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;&nbsp;請求管理</a></li>
                  <li class="{{ Request::segment(1) === 'course' ? 'active' : null }}"><a href="/course"><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;&nbsp;検査コース管理</a></li>
                  <li class="{{ Request::segment(1) === 'option' ? 'active' : null }}"><a href="/option"><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;&nbsp;検査コースオプション管理</a></li>
                  <li class="{{ Request::segment(1) === 'reservation' ? 'active' : null }}"><a href="/reservation"><i class="fa fa-list-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;受付一覧</a></li>
                  <li class="{{ Request::segment(1) === 'calendar' ? 'active' : null }}"><a href="/calendar"><i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp;カレンダー管理</a></li>
                  <li class="{{ Request::segment(1) === 'email-template' ? 'active' : null }}"><a href="/email-template"><i class="fa fa-gears"></i>&nbsp;&nbsp;&nbsp;&nbsp;メールテンプレート管理</a></li>
                  {{-- EPARKスタッフのみ表示させる --}}
                  @if(Auth::user()->getTable() == "staffs")
                    <li class="{{ Request::segment(1) === 'hospital-email-setting' ? 'active' : null }}"><a href="/hospital-email-setting"><i class="fa fa-gears"></i>&nbsp;&nbsp;&nbsp;&nbsp;受付メール設定</a></li>
                  @endif
                  @if(Auth::user()->getTable() == "hospital_staffs" && request()->session()->get('hospital_id'))
                    <li class="{{ request()->path()  === 'hospital-staff/edit-password' ? 'active' : null }}"><a href="{{ route('hospital-staff.edit.password') }}"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;パスワードの変更</a></li>
                  @endif
                </ul>
                {{-- デフォルトのサイドバー --}}
                {{-- @each('adminlte::partials.menu-item', $adminlte->menu(), 'item') --}}
            </li>
          @endif
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>
    @endif

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @if(config('adminlte.layout') == 'top-nav')
      <div class="container">
      @endif

      <!-- Content Header (Page header) -->
      <section class="content-header">
        @yield('content_header')
      </section>

      <!-- Main content -->
      <section class="content">

        @yield('content')

      </section>
      <!-- /.content -->
      @if(config('adminlte.layout') == 'top-nav')
      </div>
      <!-- /.container -->
      @endif
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- ./wrapper -->
@stop

@section('adminlte_js')
  <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
  @stack('js')
  @yield('js')
@stop
