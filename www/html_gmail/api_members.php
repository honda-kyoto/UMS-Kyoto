<?php

//	$wsdl = 'https://61.208.193.10/soap/wsdl/user_service.wsdl';
	$wsdl = 'https://61.208.193.10/soap/wsdl/ml_service.wsdl';

	$options = array(
		'login'    => 'ncvc',
		'password' => 'd67gfVdG',
                         'proxy_host' => 'pyxis.jun.ncvc.go.jp',
                         'proxy_port' => 8080,
	);
	$client = new SoapClient($wsdl, $options);

	$params = array();
	//$res = $client->userSearchLdap($params);
	//$res = $client->mailingSearch($params);
	$res = $client->mailingMemSearch($params);

	print "<pre>";
	print_r( $res );
	print "</pre>";


?>

