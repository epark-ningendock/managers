<html>
    <body>
      <p>{{ $data['staff']['name'] }}さん ご登録ありがとうございます。</p>
      <br>
      <p>あなたのパスワードは</p>
      <br>
      <p>{{ $data['password'] }} です</p>
      <br>
      <p>次のリンクからログインすることができます。</p>
      <br>
      {{-- ログイン処理が完成後、ログイン画面に変更 --}}
      <a href="#">login</a>
      <br>
      <p>これからもEparkをご利用ください。</p>
    </body>
</html>