<html>
    <body>
      <p>カレンダー：{{ $data['calendar']->id }}　{{ $data['calendar']->name }}</p>
      <p>処理：{{ $data['processing'] }}</p>
      <p>登録・更新者：{{ $data['staff_name'] }}</p>
      @if ($data['processing'] === "登録")
        <p>登録・更新日時：{{ $data['calendar']->created_at }}</p>
      @elseif ($data['processing'] === "変更")
        <p>登録・更新日時：{{ $data['calendar']->updated_at }}</p>
      @elseif ($data['processing'] === "削除")
        <p>登録・更新日時：{{ $data['calendar']->deleted_at }}</p>
      @endif
      <br>
      <a href="{{url('login')}}">{{url('login')}}</a>
      <br>
      <p>本メールにお心当たりがない場合は、お手数ですが削除してください。</p>
      <p>本メールは自動配信です。ご返信いただいてもご質問等にはご回答できませんのでご了承ください。</p>
    </body>
</html> 