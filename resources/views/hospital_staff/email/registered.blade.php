<html>
    <body>
      <p>{{ $data['hospital_staff']['name'] }}さん ご登録ありがとうございます。</p>
      <br>
      <p>あなたのパスワードは</p>
      <p>{{ $data['password'] }} です</p>
      <br>
      <p>次のリンクからログインすることができます。</p>
      <a href="{{url('login')}}">{{url('login')}}</a>
      <br>
      <p>本メールにお心当たりがない場合は、お手数ですが削除してください。</p>
      <p>本メールは自動配信です。ご返信いただいてもご質問等にはご回答できませんのでご了承ください。</p>
    </body>
</html>