<html>
    <body>
<pre>
@if($customer_flg)
{{$姓}}　{{$名}}さま

このたびはEPARK人間ドックをご利用いただきありがとうございました。
@if($process_kbn == '0')
以下の内容で予約申し込みを仮受付しましたことをお知らせいたします。
@elseif($process_kbn != '0' && $status == 1)
ご予約内容（仮予約）を変更しましたことをお知らせいたします。
@elseif($process_kbn != '0' && $status != 1)
以下の内容で予約内容を変更申し込みを受付しましたことをお知らせいたします。
@endif
現時点ではまだ確定はしておりません。
このあと、医療施設からご連絡ございますので、それまでお待ちください。
@else
{{$医療施設名}}さま

@if($process_kbn == '0')
こちらはEPARK人間ドックです。予約を仮受付しましたことをお知らせいたします。
@else($process_kbn != '0')
こちらはEPARK人間ドックです。下記のご予約内容の変更についてお知らせいたします。
@endif
受診希望者さまに電話、またはメールにて、予約の確定をお願い致します。
確定または変更・キャンセルに関しまして、管理画面に入力頂くか、
弊社にご連絡をお願い申し上げます。（電話・FAX・メール：unei@eparkdock.com）
※健保の場合は健保に変更致しますのでその旨ご記入お願い致します。
■ ■ ■こちらから予約のステータス変更が出来ます。■ ■ ■
 {{$管理画面URL}}
@endif

仮受付日：{{$受付日}}
@if($process_kbn == '0')
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ご予約内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@else
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■変更後のご予約内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@endif
医療施設：{{$医療施設名}}
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
ご加入の保険により金額が異なります。
金額につきましては医療施設よりご連絡いたします。

受診希望日
第一希望：{{$第一希望日}}
第二希望：{{$第二希望日}}
第三希望：{{$第三希望日}}
※実際の受診日は、直接医療施設とご相談の際に調整をお願いいたします。

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
@foreach($questions as $question)
{{$question['question_title']}}： {{$question['answer']}}
@endforeach
電話がつながりやすい時間帯：{{$電話が繋がりやすい時間帯}}
所属する健康保険組合名：{{$所属する健康保険組合名}}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■備考
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{{$受付・予約メモ}}

        ■■■■予約完了までの手続き■■■■
予約はまだ確定していません。
以下の手順で予約確定の手続きを行います。
申込日から２～３日以内に、医療施設から予約内容確認の
お電話または、メールをいたします。
医療施設の休診日によっては、お電話が遅れる場合がございますのでご了承ください。
ご連絡がありましたら、予約内容に間違いがないかご確認の上、
医療施設の指示に従ってお手続きを行ってください。
受診予約がお客さまと医療施設の間で同意がなされた上で、
予約確定となります。

■■■■予約の確認・変更・キャンセルについて■■■■
マイページの予約履歴から、ご予約内容の確認、検査コースの変更・キャンセルが出来ます。
https://www.docknet.jp/mypage/accept.html
※変更・キャンセルは、{{$キャンセル変更受付期限日}}まで受付けております。


※本メールはEPARK人間ドック（ http://www.docknet.jp/ ）にて
　健診のご予約をされたお客様へお送りしています。
※本メールにお心あたりが無い場合は、誠に恐れ入りますが、
　破棄して頂きますようお願いいします。
※本メールは配信専用です。検査に関するお問い合わせは、
　医療施設へ直接ご連絡ください。

---------------------------------------------------------
人間ドックの総合ポータルサイト　EPARK人間ドック
http://www.docknet.jp/
運営：株式会社EPARK人間ドック
http://eparkdock.com/
---------------------------------------------------------
</pre>
    </body>
</html>