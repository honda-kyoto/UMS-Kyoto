<?php

	require_once('./class/googleapi_new_class.php');
	require_once('./conf/config.php');

	$googlenewapi = new Googleapi_new($google_new_admin);
//var_dump($googlenewapi);
	$result = $googlenewapi->get_user_info("hhonda");
	echo $result['primaryEmail'];

//	$user_info['accountname'] = "shibutatest3";
//	$user_info['givenname'] = "kazuyoshi";
//	$user_info['familyname'] = "shibuta";
//	$result = $googlenewapi->create_user_account($user_info);
//	
//	echo $result['primaryEmail'];



?>