<html>
    <body>
      <p>こんにちは {{ $name = "" }}さん。</p>
      <br>
      <p>パスワードの更新が完了いたしました。</p>
      <br>
      <a href="{{url('login')}}">{{url('login')}}</a>
      <br>
      <p>本メールにお心当たりがない場合は、お手数ですが削除してください。</p>
      <p>本メールは自動配信です。ご返信いただいてもご質問等にはご回答できませんのでご了承ください。</p>
    </body>
</html>