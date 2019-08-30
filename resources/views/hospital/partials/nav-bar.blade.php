

@if ( request()->route()->getName() == 'hospital.edit' )
    <ul class="nav nav-pills" style="margin-bottom: 30px;">
        <li class="@if( request()->get('tab') == 'contractor-information' ) active @endif"><a href="{{ route('hospital.edit', ['id' => $hospital->id]) }}?tab=contractor-information">契約情報</a></li>
        <li class="@if( request()->get('tab') == 'hospital-information' ) active @endif"><a href="{{ route('hospital.edit', ['id' => $hospital->id]) }}?tab=hospital-information">基本情報</a></li>
        <li class="@if( request()->get('tab') == 'image-information' ) active @endif"><a href="{{ route('hospital.edit', ['id' => $hospital->id]) }}?tab=image-information">画像情報</a></li>
        <li class="@if( request()->get('tab') == 'decent-information' ) active @endif"><a href="{{ route('hospital.edit', ['id' => $hospital->id]) }}?tab=decent-information">こだわり情報</a></li>
    </ul>
@else
    <ul class="nav nav-pills" style="margin-bottom: 30px;">
        <li class="@if( request()->routeIs('contract.information.create') ) active @endif"><a href="{{ route('contract.information.create') }}">契約情報</a></li>
        <li class="@if( request()->routeIs('hospital.create') ) active @endif"><a href="{{ route('hospital.create') }}">基本情報</a></li>
        <li class="@if( request()->routeIs('hospital.image.information') ) active @endif"><a href="{{ route('hospital.image.information') }}">画像情報</a></li>
        <li class="@if( request()->routeIs('hospital.attention.create') ) active @endif"><a href="{{ route('hospital.attention.create') }}">こだわり情報</a></li>
    </ul>
@endif
