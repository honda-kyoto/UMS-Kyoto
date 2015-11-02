<?php
/**********************************************************
* File         : get_all_members.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

$oMgr = new common_mgr();

$client = null;
$oMgr->createMlistClient(&$client);

if (is_null($client))
{
echo "client is null";
	exit;
}

$page = 1;

$strCsv = "";

while (1)
{
	$params = array();
	$params['page'] = $page;
	$params['file'] = 'members';
	$res = $client->mailingMemSearch($params);
	$resultNum = $res->resultNum;
	if (is_object($res->item) && isset($res->item->member))
	{
		$strCsv .= $res->item->listName;
		$strCsv .= ",members,";
		$strCsv .= $res->item->member;
		$strCsv .= "\n";
	}
	else if (is_array($res->item))
	{
		foreach ($res->item AS $item)
		{
			$strCsv .= $item->listName;
			$strCsv .= ",members,";
			$strCsv .= $item->member;
			$strCsv .= "\n";
		}
	}

	// 50件ずつ返ってくるのでページ数×50が結果件数を上回ったら終わり
	if ($page * 50 >= $resultNum)
	{
		break;
	}
	$page++;
}
$page = 1;
$aryCurActives = array();
while (1)
{
	$params = array();
	$params['page'] = $page;
	$params['file'] = 'actives';
	$res = $client->mailingMemSearch($params);
	$resultNum = $res->resultNum;
	if (is_object($res->item) && isset($res->item->member))
	{
		$strCsv .= $res->item->listName;
		$strCsv .= ",actives,";
		$strCsv .= $res->item->member;
		$strCsv .= "\n";
	}
	else if (is_array($res->item))
	{
		foreach ($res->item AS $item)
		{
			$strCsv .= $item->listName;
			$strCsv .= ",actives,";
			$strCsv .= $item->member;
			$strCsv .= "\n";
		}
	}

	// 50件ずつ返ってくるのでページ数×50が結果件数を上回ったら終わり
	if ($page * 50 >= $resultNum)
	{
		break;
	}
	$page++;
}


file_put_contents($dir . "/mlist_members.csv", $strCsv);

exit;


?>

