@php
  use App\Option;
  use \App\Enums\ReservationStatus;
@endphp
<html>
    <body>
      @if($data['processing'] === '登録')
        <p>以下の医療機関にて新規受付登録がありました。</p>
      @elseif($data['processing'] === '変更')
        <p>以下の医療機関にて受付変更がありました。</p>
      @elseif($data['processing'] === '受付ステータス変更')
        <p>以下の医療機関にて受付ステータス変更がありました。</p>
      @endif
      <p>医療機関名：{{ $data['hospital_name'] }}</p>
      <p>受診者名：{{ $data['reservation']->customer()->get()->first()->name }}</p>
      <p>予約日：{{ $data['reservation']->created_at }}</p>
      <p>受診日：{{ $data['reservation']->reservation_date }}</p>
      <p>検査コース名：{{ implode(", ", $data['reservation']->course()->get()->pluck('name')->toArray()) }}</p>
      <p>オプション：{{ implode(", ", Option::whereIn('id', $data['reservation']->reservation_options()->get()->pluck('option_id'))->get()->pluck('name')->toArray()) }}</p>
      <p>調整金額：{{number_format($data['reservation']->adjustment_price) }}</p>
      <p>金額：{{ number_format($total) }}</p>
      <p>健保：{{ $data['reservation']->is_health_insurance ? '◯' : '-' }}</p>
      @if($data['reservation']->reservation_status->is(ReservationStatus::PENDING))
        <p>受付ステータス：仮受付</p>
      @elseif($data['reservation']->reservation_status->is(ReservationStatus::RECEPTION_COMPLETED))
        <p>受付ステータス：受付確定</p>
      @elseif($data['reservation']->reservation_status->is(ReservationStatus::COMPLETED))
        <p>受付ステータス：受診完了</p>
      @elseif(!$data['reservation']->reservation_status->is(ReservationStatus::CANCELLED))
        <p>受付ステータス：キャンセル</p>
      @else
        <p>受付ステータス：なし</p>
      @endif
      <p>処理：{{ $data['processing'] }}</p>
      <p>登録・更新者：{{ $data['staff_name'] }}</p>
      @if($data['processing'] === '登録')
        <p>登録・更新日時：{{ $data['reservation']->created_at }}</p>
      @elseif($data['processing'] === '変更' || $data['processing'] === '受付ステータス変更')
        <p>登録・更新日時：{{ $data['reservation']->updated_at }}</p>
      @else
        <p>登録・更新日時：{{ $data['reservation']->updated_at }}</p>
      @endif
      <br>
      <a href="{{url('login')}}">{{url('login')}}</a>
      <br>
      <p>本メールにお心当たりがない場合は、お手数ですが削除してください。</p>
      <p>本メールは自動配信です。ご返信いただいてもご質問等にはご回答できませんのでご了承ください。</p>
    </body>
</html> 