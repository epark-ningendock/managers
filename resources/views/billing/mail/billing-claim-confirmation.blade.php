<html>
<body>


{{ $billing->hospital->contract_information->contractor_name }} 御中<br/><br/>
<br/>
いつも大変お世話になっております。<br/>
株式会社EPARK人間ドックです。<br/>
平素は弊社に格別のお引き立てを賜り、誠にありがとうございます。<br/><br/>

この度は、{{ substr($attributes['selectedMonth'], 0, 4) }}年 {{ substr($attributes['selectedMonth'], -2) }}月の請求内容のご確認をお願いしたくご連絡差し上げました。<br/><br/>

つきましては、メール添付またはFAX次ページの<br/>
・契約プラン<br/>
・予約受付内容<br/>
のご確認をお願いいたします。<br/><br/>

コースやオプション検査の変更など、予約受付内容に変更がございましたら<br/>
下記URLのEPARK人間ドック管理システムへログインいただき、受付管理より情報を変更いただくか、<br/>
弊社までお電話またはメール・FAXにてご連絡いただければ幸いです。<br/><br/>

管理システムURL：https://docknets-manage.jp/<br/><br/>

お忙しいところ恐縮ですが、予約受付内容の変更やご確認は、<br/>
返答期限日「{{ \Carbon\Carbon::now()->addDays(7)->format("Y年m月d日") }}」までに<br/>
請求管理画面より［請求確認済み］ボタンを押していただくか<br/>
弊社までお電話またはメール・FAXにてご連絡をお願いいたします。<br/><br/>

何かご不明な点などございましたらお気軽にお申し付けください。<br/><br/><br/>


株式会社EPARK人間ドック<br/>
〒105-0012<br/>
東京都港区芝大門1-2-13　MSC芝大門ビル6F<br/>
TEL:0120-201-637　　 FAX:03-4560-7693<br/>
Mail: dock_gyoumu@eparkdock.com<br/>


</body>
</html>
