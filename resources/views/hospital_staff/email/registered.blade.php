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
      <p>これからもEparkをご利用ください。</p>
    </body>
</html>