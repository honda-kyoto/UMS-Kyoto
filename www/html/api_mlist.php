<?php

//	$wsdl = 'https://61.208.193.10/soap/wsdl/user_service.wsdl';
	$wsdl = 'https://mail01.ncvc.go.jp/soap/wsdl/ml_service.wsdl';

	$options = array(
		'login'    => 'ncvc',
		'password' => 'd67gfVdG',
	);
	$client = new SoapClient($wsdl, $options);

	$params = array();
	//$res = $client->userSearchLdap($params);
	$res = $client->mailingSearch($params);
	//$res = $client->mailingMemSearch($params);

	print "<pre>";
	print_r( $res );
	print "</pre>";


?>

