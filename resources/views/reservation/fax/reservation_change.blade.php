<html>
    <body>
<pre>
EPARK人間ドック 予約・変更のお知らせ

{{$医療施設名}}　御中

こちらはEPARK人間ドックです。下記の予約内容の変更についてお知らせいたします。

受診希望者さまに電話、またはメールにて、予約の確定をお願い致します。
確定または変更・キャンセルに関しまして、管理画面に入力頂くか、
弊社にご連絡をお願い申し上げます。（電話・FAX・メール：unei@eparkdock.com）
※健保の場合は健保に変更致しますのでその旨ご記入お願い致します。

　　　　　　　　　　　　　　　株式会社EPARK人間ドック
返信先電話番号　　　　　　　　　　　0120-201-637
返信先FAX番号　　　　　　　　　　　03-4560-7693
返信先アドレス　　　　　　　　　　　unei@eparkdock.com
ステータス　　　　　　　　□　確定　　　　□　キャンセル　　　　□　健保
受診予定確定日時
備考

受付日時　　　　　　　　　{{$受付日}}
受付形態　　　　　　　　　{{ $operator }}
検査コース　　　　　　　　{{$検査コース名}} {{ number_format($コース料金) }}円（税込）
@foreach($options as $option)
@if($loop->first)
オプション　　　　　　　　{{ $option['name'] }} {{ number_format($option['price']) }}円（税込）
@else
　　　　　　　　　　　　　{{$option['name']}} {{ number_format($option['price']) }}円（税込）
@endif
@endforeach
小計　　　　　　　　　　　{{number_format($コース料金＋オプション総額)}}円（税込）
調整　　　　　　　　　　　{{number_format($調整金額)}}円（税込）
合計　　　　　　　　　　　{{number_format($コース料金＋オプション総額＋調整金額)}}円（税込）

受診希望日　　　　　　　　第一希望：{{$第一希望日}}
　　　　　　　　　　　　　第二希望：{{$第二希望日}}
　　　　　　　　　　　　　第三希望：{{$第三希望日}}

お名前　　　　　　　　　　{{$姓}}　{{$名}}（{{$姓読み仮名}}　{{$名読み仮名}}）さま
性別　　　　　　　　　　　{{$性別}}
生年月日　　　　　　　　　{{$年}}年{{$月}}月{{$日}}日
住所　　　　　　　　　　　〒{{$郵便番号}} 　{{$都道府県}}{{$市区群}}{{$町村番地}}{{$建物名}}
TEL　　　　　　　　　　　 {{$電話番号}}
メールアドレス　　　　　　{{$メールアドレス}}

医療機関からの質問・回答：
@foreach($questions as $question)
Q. {{$question['question_title']}}
A. {{$question['answer']}}

@endforeach

電話がつながりやすい時間帯：{{$電話が繋がりやすい時間帯}}
備考
{{$備考}}

受付・予約メモ
{{$受付・予約メモ}}

{{ (Session::get('hospital_id', null)) }}{{ (Session::get('staffs', null)) }}
</pre>
    </body>
</html>