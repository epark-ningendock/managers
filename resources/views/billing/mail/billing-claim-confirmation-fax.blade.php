<html lang="en">

<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>請求確認</title>
    <style>
        .size_title {
            font-size: 7ex;
            font-weight: bold;
        }
        .size_title2 {
            font-size: 2ex;
            font-weight: bold;
        }
        .line {
            text-decoration: underline solid;
        }
        .span_right {
            display:block;
            text-align:right;/*右寄せ*/
        }
    </style>

</head>
<body>

<table>
    <tr>
        <td width="50%" rowspan="2">
            <p class="size_title">FAX</p>
        </td>
        <td width="20%">
            　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　
        </td>
        <td width="30%"　align="right">
            　　<span style="border-bottom: solid 2px;">Date {{$attributes['today_date']}}/</span>
        </td>
    </tr>
    <tr>
        <td>　</td>
        <td>　</td>
    </tr>
</table>
<br>
<p class="size_title2">【EPARK人間ドック】請求内容ご確認のお願い</p><br>
<span style="border-bottom: solid 2px;">送付先：{{ $billing->hospital->contract_information->contractor_name }} 御担当者様 </span>
<span class="span_right">株式会社EPARK人間ドック</span>
<span class="span_right">〒105-0012　　 　　　　</span>
<span class="span_right">東京都港区芝大門1-2-13　</span>
<span class="span_right">MSC芝大門ビル6F　 　　</span>
<span class="span_right line">電話番号：0120-201-637</span>
<span class="span_right line">FAX番号：03-4560-7693</span>
<br>
<br>

　　いつも大変お世話になっております。<br/>
　　株式会社EPARK人間ドックです。<br/>
　　平素は格別のお引き立てを賜り、誠にありがとうございます。<br/><br/>

　　この度は、{{ substr($attributes['selectedMonth'], 0, 4) }}年 {{ substr($attributes['selectedMonth'], -2) }}月 の請求内容のご確認を<br>
　　お願いしたくご連差し上げました。<br/>
<br>
　　恐れ入りますが、添付の明細書か請求管理画面の「明細」ボタンより、下記２点のご確認をお願いします。<br/>
　　１.「各プラン・サービス別内訳明細」の内容<br/><br/>
　　２.「予約受付別明細」の内容<br/>
　　※キャンセル、日程/料金変更　など<br/><br/><br/>

　　上記２点をご確認後、EPARK人間ドック管理システムの請求管理画面より<br/>
　　［請求確認］ボタンの押下をお願いします。<br/>
　　※相違がある場合は該当箇所を変更のうえ、ボタンの押下をお願いします。<br/><br/>

　　管理システムURL：https://docknets-manage.jp/<br/><br/>

　　弊社までお電話またはメール・FAXにてご連絡頂くことでも対応可能です。<br/><br/><br/>

　　確認・変更のご返答期限：<br/>
　　「返答期限日{{ \Carbon\Carbon::now()->endOfMonth()->format("Y年m月d日") }}」までにお願いします。<br/><br/><br/>

　　何かご不明な点がございましたらお気軽にお申し付けください。<br/>
　　お忙しいところ恐縮ではございますが、よろしくお願い申し上げます。<br/><br/><br/>


</body>
