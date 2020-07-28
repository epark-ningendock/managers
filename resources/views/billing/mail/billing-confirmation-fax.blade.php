<html lang="en">

<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>請求金額確定のお知らせ</title>
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
        <td width="25%">
            　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　
        </td>
        <td width="25%"　align="right">
            　　<span style="border-bottom: solid 2px;">Date　{{$attributes['today_date']}}</span>
        </td>
    </tr>
    <tr>
        <td>　</td>
        <td>　</td>
    </tr>
</table>
<br>
<p class="size_title2">【EPARK人間ドック】請求金額確定のお知らせ</p><br/>
<span style="border-bottom: solid 2px;">送付先：{{ $billing->hospital->contract_information->contractor_name }} 御担当者様 </span><span class="span_right">株式会社EPARK人間ドック</span>
<span class="span_right">〒105-0012　　 　　　　</span>
<span class="span_right">東京都港区芝大門1-2-13　</span>
<span class="span_right">MSC芝大門ビル6F　 　　</span>
<span class="span_right line">電話番号：0120-201-637</span>
<span class="span_right line">FAX番号：03-4560-7693</span>
<br>
<br>

　　いつも大変お世話になっております。<br/>
　　株式会社EPARK人間ドックです。<br/>
　　平素は弊社に格別のお引き立てを賜り、誠にありがとうございます。<br/><br/>

　　この度は、{{ substr($attributes['selectedMonth'], 0, 4) }}年 {{ substr($attributes['selectedMonth'], -2) }}月 の請求内容が確定いたしましたのでご報告いたします。<br/>
<br/>

　　【振込の場合】<br/>
　　ご請求書は、ご利用月の翌月中旬にお手元に届く予定です。ご対応をお願いいたします。<br/><br/>

　　【引落の場合】<br/>
　　ご契約の金融機関より、ご請求月の翌月20日または27日に引き落とさせていただきます。<br/><br/>

　　何かご不明な点などございましたらお気軽にお申し付けください。<br/>


</body>
