<html>
    <body>
<pre>
@if($customer_flg)
{{$姓}}　{{$名}}さま

このたびはEPARK人間ドックをご利用いただきありがとうございました。
ご予約が確定しましたことをお知らせいたします。

@else
{{$医療施設名}}さま


こちらはEPARK人間ドックです。予約を確定しましたことをお知らせいたします。
{{$姓}}　{{$名}}さまの予約が確定しましたことをお知らせいたします。

■ ■ ■こちらから予約のステータス変更が出来ます。■ ■ ■
 {{$管理画面URL}}
@endif

受付日：{{$受付日}}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ご予約内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@if($customer_flg)
医療施設：{{$医療施設名}}
@else
医療施設：{{$医療施設名}} さま
@endif
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
@if($customer_flg)
ご加入の保険により金額が異なります。
金額につきましては医療施設よりご連絡いたします。
@endif

受診日：{{$確定日}}
受付時間：{{$受付時間}}

@if(!$customer_flg)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■お客様情報
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
お名前： {{$姓}}　{{$名}}（{{$姓読み仮名}}　{{$名読み仮名}}）さま
性別：{{$性別}}
生年月日：{{$年}}年{{$月}}月{{$日}}日
住所：〒{{$郵便番号}} 　{{$都道府県}}{{$市区群}}{{$町村番地}}{{$建物名}}
TEL： {{$電話番号}}
メールアドレス：{{$メールアドレス}}
施設の選び方：{{$施設の選び方}}
キャンペーンコード：{{$キャンペーンコード}}

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
■■■■予約の確認・変更・キャンセルについて■■■■
マイページの予約履歴から、ご予約内容の確認、検査コースの変更・キャンセルが出来ます。
https://www.docknet.jp/mypage/accept
※変更・キャンセルは、{{$キャンセル変更受付期限日}}まで受付けております。


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