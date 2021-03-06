<html>
    <body>
<pre>
@if($customer_flg)
{{$姓}}　{{$名}}さま

このたびはEPARK人間ドックをご利用いただきありがとうございました。
下記のご予約をキャンセルしましたことをお知らせいたします。
@endif

@if(!$customer_flg)
{{$医療施設名}}さま

こちらはEPARK人間ドックです。
下記のご予約をキャンセルしましたことをお知らせいたします。
ご対応下さいますようお願いいたします。
@endif
受付日：{{$受付日}}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■キャンセルしたご予約内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
医療施設：{{$医療施設名}} さま
住所：{{$医療施設住所}}
電話番号：{{$医療施設電話番号}}

検査コース：{{$検査コース名}} {{$コース料金}}円（税込）
@foreach($options as $option)
@if($loop->first)
オプション：{{ $option['name'] }} {{ $option['price'] }}円（税込）
@else
　　　　　　 　　{{$option['name']}} {{$option['price']}}円（税込）
@endif
@endforeach

小計：{{number_format($コース料金＋オプション総額)}}円（税込）
調整：{{number_format($調整金額)}}円（税込）
合計：{{number_format($コース料金＋オプション総額＋調整金額)}}円（税込）
@if($customer_flg && $isCategory == 1)ご加入の保険により金額が異なります。@endif

受診日：{{$確定日}}
@if($受付時間 != '')受付時間：{{$受付時間}}@endif



@if(!$customer_flg)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■お客様情報
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
お名前： {{$姓}}　{{$名}}（{{$姓読み仮名}}　{{$名読み仮名}}）さま
性別：{{$性別}}
生年月日：{{$年}}年{{$月}}月{{$日}}日
住所：〒{{$郵便番号}}　{{$都道府県}}{{$市区群}}{{$町村番地}}{{$建物名}}
TEL： {{$電話番号}}
メールアドレス：{{$メールアドレス}}
施設の選び方：{{$施設の選び方}}

医療機関からの質問・回答：
@foreach($questions as $question)
Q. {{$question['question_title']}}
A. {{$question['answer']}}

@endforeach

電話がつながりやすい時間帯：{{$電話が繋がりやすい時間帯}}
所属する健康保険組合名：{{$所属する健康保険組合名}}
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■備考
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{{$備考}}
@if(!$customer_flg)

受付・予約メモ
{{$受付・予約メモ}}
@endif

@if($customer_flg)
※本メールはEPARK人間ドック（ https://www.docknet.jp/ ）にて
　健診のご予約をされたお客様へお送りしています。
※本メールにお心あたりが無い場合は、誠に恐れ入りますが、
　破棄して頂きますようお願いいします。
※本メールは配信専用です。検査に関するお問い合わせは、
　医療施設へ直接ご連絡ください。
@endif

---------------------------------------------------------
人間ドックの総合ポータルサイト　EPARK人間ドック
https://www.docknet.jp/
運営：株式会社EPARK人間ドック
https://eparkdock.com/
---------------------------------------------------------
{{ (Session::get('hospital_id', null)) }}{{ (Session::get('staffs', null)) }}
</pre>
    </body>
</html>