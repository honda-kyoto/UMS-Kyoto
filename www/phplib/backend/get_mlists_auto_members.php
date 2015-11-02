<?php
/**********************************************************
* File         : mlists_auto_members.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

//配信先メールアドレス（actives）、送信可能メンバー（members）

$listName =  $argv[1];

$oMgr = new common_mgr();

$client = null;
$oMgr->createMlistClient(&$client);

if (is_null($client))
{
	exit;
}

$page = 1;
$aryCurMembers = array();
while (1)
{
	$params = array();
	$params['listName'] = $listName;
	$params['page'] = $page;
	$params['file'] = 'members';
	$res = $client->mailingMemSearch($params);
	$resultNum = $res->resultNum;
	if (is_object($res->item) && isset($res->item->member))
	{
		$aryCurMembers[] = $res->item->member;
	}
	else if (is_array($res->item))
	{
		foreach ($res->item AS $item)
		{
			$aryCurMembers[] = $item->member;
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
	$params['listName'] = $listName;
	$params['page'] = $page;
	$params['file'] = 'actives';
	$res = $client->mailingMemSearch($params);
	$resultNum = $res->resultNum;
	if (is_object($res->item) && isset($res->item->member))
	{
		$aryCurActives[] = $res->item->member;
	}
	else if (is_array($res->item))
	{
		foreach ($res->item AS $item)
		{
			$aryCurActives[] = $item->member;
		}
	}

	// 50件ずつ返ってくるのでページ数×50が結果件数を上回ったら終わり
	if ($page * 50 >= $resultNum)
	{
		break;
	}
	$page++;
}

$aryAll = array();

$sender_cnt = 0;
if (is_array($aryCurMembers))
{
	foreach ($aryCurMembers AS $item)
	{
		$aryAll[] = $item;
		$sender_cnt++;
	}
}

foreach ($aryCurActives AS $item)
{
	if (is_array($item, $aryAll))
	{
		continue;
	}
	$aryAll[] = $item;
}

$csv = "";
foreach ($aryAll AS $item)
{
	$sender_flg = '0';
	$recipient_flg = '0';
	if ($sender_cnt > 0)
	{
		if (in_array($item, $aryCurMembers))
		{
			$sender_flg = '1';
		}
	}
	else
	{
		$sender_flg = '1';
	}
	if (in_array($item, $aryCurActives))
	{
		$recipient_flg = '1';
	}

	$csv .= $item . "," . $sender_flg . "," . $recipient_flg . "\n";
}

if ($csv != "")
{
	$dir = getcwd();
	file_put_contents($dir . "/" . $listName . ".csv", $csv);
}

exit;


?>
