<?php

//	$wsdl = 'https://61.208.193.10/soap/wsdl/user_service.wsdl';
	$wsdl = 'https://mail01.ncvc.go.jp/soap/wsdl/user_service.wsdl';
//	$wsdl = 'https://61.208.193.10/soap/wsdl/ml_service.wsdl';

	$options = array(
		'login'    => 'ncvc',
		'password' => 'd67gfVdG',
	);
	$client = new SoapClient($wsdl, $options);

	$params = array();
	//$res = $client->userSearchLdap($params);
	//$res = $client->mailingSearch($params);
	//$res = $client->mailingMemSearch($params);
	
	$uid = $argv[1];
	$page = 1;

	$params = array();
	$params['uid'] = $uid;
	$params['page'] = $page;
	$res = $client->userSearchLdap($params);
	$resultNum = $res->resultNum;
	echo 'count:'.$resultNum;

	if($resultNum != 0){
		echo "\n";

		echo $res->item->uid;
		echo "\n";
		echo $res->item->ncvcName;
		echo "\n";
		echo $res->item->mail;
		echo "\n";
		if(array_key_exists('mailAlias',$res->item)){
			echo $res->item->mailAlias;
		}
		echo "\n";
		if(array_key_exists('mailForward',$res->item)){
			echo $res->item->mailForward;
		}
		echo "\n";
		echo $res->item->mailStatus;
		echo "\n";
var_dump($res);
	}



?>
