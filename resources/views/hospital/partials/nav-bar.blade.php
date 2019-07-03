<ul class="nav nav-pills" style="margin-bottom: 30px;">
    <li class="@if( request()->routeIs('hospital.contractInfo') ) active @endif"><a href="{{ route('hospital.contractInfo') }}">契約情報</a></li>
    <li class="@if( request()->routeIs('hospital.create') ) active @endif"><a href="{{ route('hospital.create') }}">基本情報</a></li>
    <li class="@if( request()->routeIs('hospital.image.information') ) active @endif"><a href="{{ route('hospital.image.information') }}">画像情報</a></li>
    <li><a href="#">こだわり情報</a></li>
</ul>
