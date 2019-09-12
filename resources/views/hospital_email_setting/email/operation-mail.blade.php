<html>
    <body>
      @if ($data['processing'] === "登録")
        <p>登録者：{{ $data['staff_name'] }}</p>
        <p>登録日時：{{ $data['hospital_email_setting']->created_at }}</p>
      @elseif ($data['processing'] === "更新")
        <p>更新者：{{ $data['staff_name'] }}</p>
        <p>更新日時：{{ $data['hospital_email_setting']->updated_at }}</p>
      @endif
      <br>
      <a href="{{url('login')}}">{{url('login')}}</a>
      <br>
      <p>本メールにお心当たりがない場合は、お手数ですが削除してください。</p>
      <p>本メールは自動配信です。ご返信いただいてもご質問等にはご回答できませんのでご了承ください。</p>
    </body>
</html> 