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
$page = 1;
while (1)
{
	$params = array();
	$params['page'] = $page;
	$res = $client->userSearchLdap($params);
	$resultNum = $res->resultNum;

	if (is_array($res->item))
	{
		foreach ($res->item AS $mailItem)
		{
			echo $mailItem->uid;
			echo ",";
			echo $mailItem->mail;
			echo ",";
			if (!isset($mailItem->mailAlias))
			{
				echo "\n";
				continue;
			}
			if (is_array($mailItem->mailAlias))
			{
				foreach ($mailItem->mailAlias AS $alias)
				{
					echo $alias;
					echo ",";
				}
			}
			else 
			{
				echo $mailItem->mailAlias;
				echo ",";
			}
			echo "\n";
		}
	}

//var_dump($res);
	// 50件ずつ返ってくるのでページ数×50が結果件数を上回ったら終わり
	if ($page * 50 >= $resultNum)
	{
		break;
	}
	$page++;
}


?>


