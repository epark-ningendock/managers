以下コースのWEB受付、掲載が2ヶ月以内に終了予定となっております。

【WEB受付終了予定コース一覧】
@foreach($context['$receptionCloseCourses'] as $data)
    医療機関名：{{ $data['hospital_name'] }}　検査コース：{{ $data['course_name'] }}　WEB受付終了日：{{ (new \Carbon\Carbon($data['reception_end_date']))->format('Y/m/d') }}　
@endforeach

【WEB掲載終了予定コース一覧】
@foreach($context['publishCloseCourses'] as $data)
    医療機関名：{{ $data['hospital_name'] }}　検査コース：{{ $data['course_name'] }}　WEB掲載終了日：{{ (new \Carbon\Carbon($data['publish_end_date']))->format('Y/m/d') }}　
@endforeach