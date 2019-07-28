<html>
    <body>
      <p>EPARK人間ドック管理システムのパスワード初期化を行います。</p>
      <br>
      <p>以下のURLをクリックしパスワード初期化手続きを行ってください。</p>
      <a href="{{url('hospital-staff/show-reset-password/'.$data['reset_token'].'/'.$data['staff']['email'])}}">{{url('hospital-staff/show-reset-password/'.$data['reset_token'].'/'.$data['staff']['email'])}}</a>
      <br>
      <br>
      <p>※本メールに記載のURLの有効期限は60分間です。</p>
      <p>60分以上経過した場合は、再度お手続きいただきますようお願いいたします。</p>
      <br>
      <p>本メールにお心当たりがない場合は、お手数ですが削除してください。</p>
      <p>本メールは自動配信です。ご返信いただいてもご質問等にはご回答できませんのでご了承ください。</p>
    </body>
</html>
