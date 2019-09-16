<html>
    <body>
      <p>受付メール設定の情報が登録・更新されました。</p>
      <p>登録・更新者：{{ $data['staff_name'] }}</p>
      <p>登録・更新日時{{ $data['hospital_email_setting']->update_at }}</p>
      <br>
      <p>詳細の確認は管理画面より行なってください。</p>
      <a href="{{url('login')}}">{{url('login')}}</a>
    </body>
</html> 