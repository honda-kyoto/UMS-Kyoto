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
		list($mlist_acc, $sender_kbn, $mail_addr, $sender_flg, $recipient_flg) = explode(",", $line);

		// �ǉ�����
		if ($sender_kbn == SENDER_KBN_FREE)
		{
			$params = array(
					'listName' => $mlist_acc,
					'member'    => $mail_addr,
					'file'      => 'actives',
			);
			$res = $client->mailingMemAdd( $params );

			if ( $res->resultCode == 100 )
			{
				$csv .= "�����o�[�ǉ��͐������܂���" . "\r\n";
			}
			else if ( $res->resultCode == 200 )
			{
				$csv .= "�����o�[�ǉ��͎��s���܂���(�N���C�A���g���G���[)" . "\r\n";
			}
			else
			{
				$csv .= "�����o�[�ǉ��͎��s���܂���(�T�[�o�[���G���[)" . "\r\n";
			}
		}
		else
		{
			if ($sender_flg == "1")
			{
				$params = array(
						'listName' => $mlist_acc,
						'member'    => $mail_addr,
						'file'     => 'members',
				);
				$res = $client->mailingMemAdd( $params );

				if ( $res->resultCode == 100 )
				{
					$csv .= "�����o�[�ǉ��͐������܂���" . "\r\n";
				}
				else if ( $res->resultCode == 200 )
				{
					$csv .= "�����o�[�ǉ��͎��s���܂���(�N���C�A���g���G���[)" . "\r\n";
				}
				else
				{
					$csv .= "�����o�[�ǉ��͎��s���܂���(�T�[�o�[���G���[)" . "\r\n";
				}

			}

			if ($recipient_flg == "1")
			{
				$params = array(
						'listName' => $mlist_acc,
						'member'    => $mail_addr,
						'file'     => 'actives',
				);
				$res = $client->mailingMemAdd( $params );


				if ( $res->resultCode == 100 )
				{
					$csv .= "�����o�[�ǉ��͐������܂���" . "\r\n";
				}
				else if ( $res->resultCode == 200 )
				{
					$csv .= "�����o�[�ǉ��͎��s���܂���(�N���C�A���g���G���[)" . "\r\n";
				}
				else
				{
					$csv .= "�����o�[�ǉ��͎��s���܂���(�T�[�o�[���G���[)" . "\r\n";
				}

			}
		}

		$csv .= $mlist_acc . ',' . $sender_kbn . ',' . $mail_addr . ',' . $sender_flg . ',' . $recipient_flg . "\r\n";
	}
}

if ($csv != "")
{
	file_put_contents($dir."/result_".$file, $csv);
}

exit;


?>