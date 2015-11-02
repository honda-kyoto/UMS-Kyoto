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

$dir = getcwd();

$oMgr = new common_mgr();

$file = $argv[1];

$buff = file_get_contents($dir."/".$file);

$aryLines = explode("\n", $buff);

$csv = "";
if (is_array($aryLines))
{
	foreach ($aryLines AS $line)
	{
		$line = trim($line);
		list($mlist_acc, $sender_kbn) = explode(",", $line);

		// 追加処理
		if ($sender_kbn == "")
		{
			$sender_kbn = "0";
		}

		$params = array(
				'listName' => $mlist_acc,
				'value'    => $sender_kbn,
		);

		$res = $client->mailingAdd( $params );

		if ( $res->resultCode == 100 )
		{
			$csv .= "メーリングリスト登録は成功しました" . "\r\n";
		}
		else if ( $res->resultCode == 200 )
		{
			$csv .= $params . "\r\n";
			$csv .= "メーリングリスト登録は失敗しました(クライアント側エラー)" . "\r\n";

		}
		else
		{
			$csv .= "メーリングリスト登録は失敗しました(サーバー側エラー)" . "\r\n";

		}


		$csv .= $mlist_acc . ',' . $sender_kbn . "\r\n";
	}
}

if ($csv != "")
{
	file_put_contents($dir."/result_".$file, $csv);
}

exit;


?>
