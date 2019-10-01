

<ul class="nav nav-pills" style="margin-bottom: 30px;">
    <li class="@if( request()->routeIs('contract.show') ) active @endif"><a href="{{ route('contract.show', ['id' => $hospital->id]) }}">契約情報</a></li>
    <li class="@if( request()->routeIs('hospital.edit') ) active @endif"><a href="{{ route('hospital.edit', ['id' => $hospital->id]) }}">基本情報</a></li>
    <li class="@if( request()->routeIs('hospital.image.create') ) active @endif"><a href="{{ route('hospital.image.create', ['id' => $hospital->id]) }}">画像情報</a></li>
    <li class="@if( request()->routeIs('hospital.attention.create') ) active @endif"><a href="{{ route('hospital.attention.create', ['id' => $hospital->id]) }}">こだわり情報</a></li>
</ul>
