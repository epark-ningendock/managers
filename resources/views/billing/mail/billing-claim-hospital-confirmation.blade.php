<html>
<body>


	以下の契約者の請求確認が完了しました。<br/>

	確認画面より確認お願いいたします。<br/>										
																															
	契約者名： {{ $billing->hospital->contract_information->contractor_name }} <br/>																												
	医療機関名：{{ $billing->hospital->name }}<br/>																				
	請求確認対応者：{{  auth()->user()->name }}<br/>																													
	請求確認対応日時：{{  $billing->updated_at }}	<br/>																													

</body>
</html>
