<html>
<body>


{{ $billing->hospital->contract_information->contractor_name }} 御中<br/><br/>
<br/>
いつも大変お世話になっております。<br/>
株式会社EPARK人間ドックです。<br/>
平素は格別のお引き立てを賜り、誠にありがとうございます。<br/><br/>

この度は、{{ substr($attributes['selectedMonth'], 0, 4) }}年 {{ substr($attributes['selectedMonth'], -2) }}月の請求内容のご確認をお願いしたくご連絡差し上げました。<br/><br/>

恐れ入りますが、下記２点のご確認をお願いします。<br/><br/>
１.「各プラン・サービス別内訳明細」の内容<br/><br/>
２.「予約受付別明細」の内容<br/>
　※キャンセル、日程/料金変更　など<br/><br/>

上記２点をご確認後、EPARK人間ドック管理システムの請求管理画面より<br/>
［請求確認済み］ボタンの押下をお願いします。<br/>
※相違がある場合は該当箇所を変更のうえ、ボタンの押下をお願いします。<br/><br/>

管理システムURL：https://docknets-manage.jp/<br/><br/>

弊社までお電話またはメール・FAXにてご連絡頂くことでも対応可能です。<br/><br/>

確認・変更のご返答期限：<br/>
「返答期限日：{{ \Carbon\Carbon::now()->endOfMonth()->format("Y年m月d日") }}」までにお願いします。<br/><br/>


何かご不明な点などございましたらお気軽にお申し付けください。<br/>
お忙しいところ恐縮ではございますが、よろしくお願い申し上げます。<br/><br/><br/>


株式会社EPARK人間ドック<br/>
〒105-0012<br/>
東京都港区芝大門1-2-13　MSC芝大門ビル6F<br/>
TEL:0120-201-637　　 FAX:03-4560-7693<br/>
Mail: dock_gyoumu@eparkdock.com<br/>
電話受付時間：平日 10:00 17:00


</body>
</html>
