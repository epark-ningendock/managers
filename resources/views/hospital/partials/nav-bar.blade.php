<div class="nav nav-pills">
  <a class="btn @if ($selected_pages['name'] == 'contact-information') active @else bg-gray @endif" href="#contact-information" data-toggle="tab">契約情報</a>
  <a class="btn @if ($selected_pages['name'] == 'basic-information') active @else bg-gray @endif" href="#basic-information" data-toggle="tab">基本情報</a>
  <a class="btn @if ($selected_pages['name'] == 'image-information') active @else bg-gray @endif" href="#image-information" data-toggle="tab">画像情報</a>
  <a class="btn @if ($selected_pages['name'] == 'detail-information') active @else bg-gray @endif" href="#detail-information" data-toggle="tab">こだわり情報</a>
</div>
