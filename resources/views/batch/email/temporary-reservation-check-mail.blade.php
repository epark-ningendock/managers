以下企業の予約が仮受付のまま3日間経過しております。

@foreach($context['reservation_dates'] as $data)
    医療機関名：{{ $data['hospital_name'] }}　予約ID：{{ $data['id'] }}　受診者名：{{ $data['customer_name'] }}　予約日：{{ $data['created_at'] }}　受診予定日：{{ $data['reservation_date'] }}
@endforeach