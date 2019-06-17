<html>
<body>
    <ul>
        <li><b>送信先メールアドレス </b> - {{ $data['destination_mail_address'] }}</li>
        <li><b>差出任命 </b> - {{ $data['appointed_submissions'] }}</li>
        <li><b>差出人メールアドレス </b> - {{ $data['hospital_email'] }}</li>
        <li><b>テンプレート </b> - {{ $data['template'] }}</li>
        <li><b>件名 </b> - {{ $data['subject'] }}</li>
        <li><b>本文 </b> - {{ $data['message'] }}</li>
    </ul>
</body>
</html>