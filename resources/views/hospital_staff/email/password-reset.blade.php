<html>
    <body>
      <p>こんにちは {{ $data['hospital_staff']['name'] }}さん！パスワードがわからなくなってしまったようですね。申し訳ございません。</p>
      <br>
      <p>でも、心配しないでください！次のリンクからパスワードをリセットすることができます。</p>
      <br>
      <a href="{{url('hospital-staff/show-reset-password/'.$data['reset_token'].'/'.$data['hospital_staff']['email'])}}">{{url('hospital-staff/show-reset-password/'.$data['reset_token'].'/'.$data['hospital_staff']['email'])}}</a>
      <br>
      <br>
      <p>このリンクトークンは3時間で切れるので、ご注意をお願いします。</p>
      <br>
      <p>これからもEparkをご利用ください！</p>
    </body>
</html>