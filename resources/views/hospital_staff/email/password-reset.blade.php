<html>
    <body>
      <p>こんにちは {{ $data['staff']['name'] }}さん。</p>
      <br>
      <p>次のリンクからパスワードをリセットすることができます。</p>
      <br>
      <a href="{{url('hospital-staff/show-reset-password/'.$data['reset_token'].'/'.$data['staff']['email'])}}">{{url('hospital-staff/show-reset-password/'.$data['reset_token'].'/'.$data['staff']['email'])}}</a>
      <br>
      <br>
      <p>このリンクトークンは3時間で切れるので、ご注意をお願いします。</p>
      <br>
      <p>これからもEparkをご利用ください。</p>
    </body>
</html>