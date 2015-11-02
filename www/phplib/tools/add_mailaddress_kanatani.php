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
			'uid'           => "kanatani.masumi.ri",
			'userPassword'  => "Masumi0530",
			'mail'          => "kanatani.masumi@ncvc.go.jp",
			'ncvcName'      => "金谷　真澄",
			'mailAlias'     => "kanatani.masumi.ri@ncvc.go.jp",
			'mailForward'    => NULL,
			'mailStatus'    => "noForward"
	);
	$res = $client->userAddLdap( $params );

	if ( $res->resultCode == 100 )
	{
		echo "メールアカウント"."は成功しました";
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
