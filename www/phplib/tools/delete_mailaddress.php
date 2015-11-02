<?php

//	$wsdl = 'https://61.208.193.10/soap/wsdl/user_service.wsdl';
	$wsdl = 'https://mail01.ncvc.go.jp/soap/wsdl/user_service.wsdl';
//	$wsdl = 'https://61.208.193.10/soap/wsdl/ml_service.wsdl';

	$options = array(
		'login'    => 'ncvc',
		'password' => 'd67gfVdG',
	);
	$client = new SoapClient($wsdl, $options);


	$params = array(
			'uid'           => "kokujun.ichiro"
	);
	$res = $client->userDeleteLdap( $params );

	if ( $res->resultCode == 100 )
	{
		echo "メールアカウント"."は削除しました";
	}
	else if ( $res->resultCode == 200 )
	{
		echo "メールアカウント"."は失敗しました(クライアント側エラー)[".$res->message."]";
		echo "\n";
		echo $aryData;
		echo "\n";
		echo $params;
	}
	else
	{
		echo "メールアカウント"."は失敗しました(サーバー側エラー)[".$res->message."]";
	}
?>
