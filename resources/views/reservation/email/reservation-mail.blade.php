@php
  use App\Option;
@endphp
<html>
    <body>
      <p>以下の医療機関にて新規受付登録がありました。</p>
      <p>医療機関名：{{ $data['hospital_name'] }}</p>
      <p>受診者名：{{ $data['reservation']->customer()->get()->first()->name }}</p>
      <p>予約日：{{ $data['reservation']->created_at }}</p>
      <p>受診日：{{ $data['reservation']->reservation_date }}</p>
      <p>検査コース名：{{ implode(", ", $data['reservation']->course()->get()->pluck('name')->toArray()) }}</p>
      <p>オプション：{{ implode(", ", Option::whereIn('id', $data['reservation']->reservation_options()->get()->pluck('option_id'))->get()->pluck('name')->toArray()) }}</p>
      <p>調整金額：{{ $data['reservation']->adjustment_price }}</p>
      <p>金額：{{ $total }}</p>
      <p>健保：{{ $data['reservation']->is_health_insurance ? '◯' : '-' }}</p>
      <p>受付ステータス：受付確定</p>
      <br>
      <a href="{{url('login')}}">{{url('login')}}</a>
      <br>
      <p>本メールにお心当たりがない場合は、お手数ですが削除してください。</p>
      <p>本メールは自動配信です。ご返信いただいてもご質問等にはご回答できませんのでご了承ください。</p>
    </body>
</html> 