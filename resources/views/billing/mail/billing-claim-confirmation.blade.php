<html>
<body>


{{ $billing->hospital->contract_information->contractor_name }} 御中<br/>
<br/>
いつも大変お世話になっております。<br/>
株式会社EPARK人間ドックでございます。<br/>

平素は弊社に格別のお引き立てを賜り、誠にありがとうございます。<br/>

さてこの度は、{{ substr($attributes['selectedMonth'], 0, 4) }}年 {{ substr($attributes['selectedMonth'], -2) }}月 の請求金額が確定いたしましたのでご連絡させていただきました。<br/>
別途送付させていただきます請求書の内容をご確認いただき、期日までにお支払いいただきま<br/>
すようお願い申し上げます。<br/>
{{--（請求書はEPARK人間ドック管理システムの請求管理画面からもダウンロードいただけます）<br/>--}}
<br/>
何かご不明な点がございましたら、お気軽にお申し付けください。<br/>
何卒よろしくお願い申し上げます。<br/>
<br/>
株式会社EPARK人間ドック<br/>
〒105-0012<br/>
東京都港区芝大門1-2-13　MSC芝大門ビル6F<br/>
TEL:0120-201-637　　 FAX:03-4560-7693<br/>
Mail: dock_gyoumu@eparkdock.com<br/>


</body>
</html>
