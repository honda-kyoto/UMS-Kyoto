<?php
/**********************************************************
* File         : delete_mlist.php
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
		list($mlist_acc) = explode(",", $line);

		$params = array(
				'listName' => $mlist_acc
		);

		$res = $client->mailingDelete( $params );

		if ( $res->resultCode == 100 )
		{
			$csv .= "���[�����O���X�g�폜�͐������܂���" . "\r\n";
		}
		else if ( $res->resultCode == 200 )
		{
			$csv .= $params . "\r\n";
			$csv .= "���[�����O���X�g�폜�͎��s���܂���(�N���C�A���g���G���[)" . "\r\n";

		}
		else
		{
			$csv .= "���[�����O���X�g�폜�͎��s���܂���(�T�[�o�[���G���[)" . "\r\n";

		}


		$csv .= $mlist_acc . ',' . "\r\n";
	}
}

if ($csv != "")
{
	file_put_contents($dir."/result_".$file, $csv);
}

exit;


?>