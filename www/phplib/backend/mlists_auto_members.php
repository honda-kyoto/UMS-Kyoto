<?php
/**********************************************************
* File         : mlists_auto_members.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/mlists_members_mgr.class.php");

//配信先メールアドレス（actives）、送信可能メンバー（members）

$aryMembers = array();
$aryActives = array();

$aryMembers[0] = "";
$aryActives[0] = "";

$oMgr = new mlists_members_mgr();

$mlist_id =  $argv[1];

$trace = "[" . date("Y/m/d H:i:s") . "]mlists_auto_members.php ID:" . $mlist_id;
Debug_Trace($trace, 0);

// 送信制限を取得
$sender_kbn = $oMgr->getSenderKbn($mlist_id);

if ($sender_kbn == SENDER_KBN_LIMIT)
{
	// 制限アリの場合設定方法を取得
	$sender_set_type = $oMgr->getAutoSetType($mlist_id);

	// 別途指定の場合送信者リストを取得
	if ($sender_set_type == SENDER_SET_TYPE_LIMIT)
	{
		$aryTmp = $oMgr->getList($mlist_id);

		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $data)
			{
				$aryMembers[] = $data['mail_addr'];
			}
		}
	}
}

// 検索実行
$aryUsers = $oMgr->searchMlistAutoMembers($mlist_id);

if (is_array($aryUsers))
{
	foreach ($aryUsers AS $data)
	{
		// メールアドレスは統合ID@ncvc.go.jp
		$addr = $data['login_id'] . USER_MAIL_DOMAIN;
		$aryActives[] = $addr;
		if ($sender_kbn == SENDER_KBN_LIMIT && $sender_set_type == SENDER_SET_TYPE_MEMBER)
		{
			// 送信者制限がある場合でメンバーが送信可能の場合送信者にもメンバーアドレスを追加
			$aryMembers[] = $addr;
		}
	}
}

// 現在の登録状況を取得
$listName = $oMgr->getMlistAcc($mlist_id);

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
		$aryCurMembers[] = $res->item;
	}
	else if (is_array($res->item))
	{
		foreach ($res->item AS $item)
		{
			$aryCurMembers[] = $item;
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
		$aryCurActives[] = $res->item;
	}
	else if (is_array($res->item))
	{
		foreach ($res->item AS $item)
		{
			$aryCurActives[] = $item;
		}
	}

	// 50件ずつ返ってくるのでページ数×50が結果件数を上回ったら終わり
	if ($page * 50 >= $resultNum)
	{
		break;
	}
	$page++;
}

// 加工
$aryDelMembers = array();
if (is_array($aryCurMembers))
{
	foreach ($aryCurMembers AS $aryCur)
	{
		$member = $aryCur->member;
		$key = array_search($member, $aryMembers);

		if ($key > 0)
		{
			// 登録済み
			unset($aryMembers[$key]);
		}
		else
		{
			$aryDelMembers[] = $member;
		}
	}
}

$aryDelActives = array();
if (is_array($aryCurActives))
{
	foreach ($aryCurActives AS $aryCur)
	{
		$member = $aryCur->member;
		$key = array_search($member, $aryActives);

		if ($key > 0)
		{
			// 登録済み
			unset($aryActives[$key]);
		}
		else
		{
			$aryDelActives[] = $member;
		}
	}
}


//
// 同期
//

$has_error = false;

// 登録対象が残っていれば登録処理
if (is_array($aryMembers))
{
	foreach ($aryMembers AS $member)
	{
		if ($member == "")
		{
			continue;
		}
		$params = array(
				'listName' => $listName,
				'member'    => $member,
				'file'     => 'members',
		);
		$res = $client->mailingMemAdd( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メンバー追加は成功しました[members:".$member."]", 181);
		}
		else if ( $res->resultCode == 200 )
		{
			$has_error = true;
			Debug_Trace("メンバー追加は失敗しました(クライアント側エラー)[members:".$member."]", 181);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
		else
		{
			$has_error = true;
			Debug_Trace("メンバー追加は失敗しました(サーバー側エラー)[members:".$member."]", 181);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
	}
}
if (is_array($aryActives))
{
	foreach ($aryActives AS $member)
	{
		if ($member == "")
		{
			continue;
		}
		$params = array(
				'listName' => $listName,
				'member'    => $member,
				'file'     => 'actives',
		);
		$res = $client->mailingMemAdd( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メンバー追加は成功しました[actives:".$member."]", 181);
		}
		else if ( $res->resultCode == 200 )
		{
			$has_error = true;
			Debug_Trace("メンバー追加は失敗しました(クライアント側エラー)[actives:".$member."]", 181);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
		else
		{
			$has_error = true;
			Debug_Trace("メンバー追加は失敗しました(サーバー側エラー)[actives:".$member."]", 181);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
	}
}

// 削除対象があれば削除する
if (is_array($aryDelMembers))
{
	foreach ($aryDelMembers AS $member)
	{
		$params = array(
				'listName' => $listName,
				'member'    => $member,
				'file'     => 'members',
		);
		$res = $client->mailingMemDelete( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メンバー削除は成功しました[members:".$member."]", 381);
		}
		else if ( $res->resultCode == 200 )
		{
			$has_error = true;
			Debug_Trace("メンバー削除は失敗しました(クライアント側エラー)[members:".$member."]", 381);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
		else
		{
			$has_error = true;
			Debug_Trace("メンバー削除は失敗しました(サーバー側エラー)[members:".$member."]", 381);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
	}
}
if (is_array($aryDelActives))
{
	foreach ($aryDelActives AS $member)
	{
		$params = array(
				'listName' => $listName,
				'member'    => $member,
				'file'     => 'actives',
		);
		$res = $client->mailingMemDelete( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メンバー削除は成功しました[actives:".$member."]", 381);
		}
		else if ( $res->resultCode == 200 )
		{
			$has_error = true;
			Debug_Trace("メンバー削除は失敗しました(クライアント側エラー)[actives:".$member."]", 381);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
		else
		{
			$has_error = true;
			Debug_Trace("メンバー削除は失敗しました(サーバー側エラー)[actives:".$member."]", 381);
			Debug_Trace($res, 181);
			Debug_Trace($params, 181);
		}
	}
}


// エラーがあればログに記録
if ($has_error)
{
	// 同じIDのものがないかチェック
	$sql = "SELECT log_cd FROM mlist_members_errlog WHERE mlist_id = " . $mlist_id . " AND complete_flg = '0'";

	$ext_cd = $oMgr->oDb->getOne($sql);

	if ($ext_cd == "")
	{
		// なければ記録する

		// ログコード作成　ランダム数字2文字＋マイクロタイムの数字部分のみ
		$rand = rand(10, 99);
		$time = microtime(true);

		list($mae, $ushiro) = explode(".", $time);

		$log_cd = $rand . $mae . str_pad($ushiro, 4, "0");

		$sql = "INSERT INTO mlist_members_errlog (log_cd, log_time, mlist_id) VALUES ('" . $log_cd ."', now(), " . $mlist_id . ")";

		$ret = $oMgr->oDb->query($sql);

		if (!$ret)
		{
			Debug_Trace("エラーログの記録に失敗しました[ID：".$mlist_id."]", 382);
		}
	}
}

exit;


?>
