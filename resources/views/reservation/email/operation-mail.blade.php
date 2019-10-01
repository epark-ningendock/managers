<html>
    <body>
      <p>処理：{{ $data['processing'] }}</p>
      @if ($data['processing'] === "登録")
        <p>登録・更新者：{{ $data['staff_name'] }}</p>
        <p>登録・更新日時：{{ $data['reservation']->created_at }}</p>
      @elseif ($data['processing'] === "変更")
        <p>登録・更新者：{{ $data['staff_name'] }}</p>
        <p>登録・更新日時：{{ $data['reservation']->updated_at }}</p>
      @endif
      <br>
      <p>詳細の確認は管理画面より行なってください。</p>
      <a href="{{url('login')}}">{{url('login')}}</a>
    </body>
</html> 