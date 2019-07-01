<ul class="nav nav-pills @if( request()->route()->url == route('hospital.contractInfo')) active @endif" style="margin-bottom: 30px;">
    <li class="active"><a href="{{ route('hospital.contractInfo') }}">契約情報</a></li>
    <li><a href="#">基本情報</a></li>
    <li><a href="#">画像情報</a></li>
    <li><a href="#">こだわり情報</a></li>
</ul>