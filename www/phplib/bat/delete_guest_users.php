<?php
/**********************************************************
* File         : mlists_auto_members.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/guests_regist_common_mgr.class.php");

$oMgr = new guests_regist_common_mgr();

echo "[start:" . date("Y/m/d H:i:s") . "]\n";

// 該当データの終了フラグを9にする
$sql = "update guest_head_tbl set end_flg = '9' where del_flg = '0' and end_flg = '0' and make_time < current_timestamp + '-1 day'";

$ret = $oMgr->oDb->query($sql);

if (!$ret)
{
	echo "SQLエラー：フラグ更新失敗\n";
	exit;
}

// 該当データを取ってくる
$sql = "select guest_id, wireless_id from guest_head_tbl where end_flg = '9'";

$aryData = $oMgr->oDb->getAssoc2Ary($sql);

if (is_array($aryData))
{
	foreach ($aryData AS $guest_id => $wireless_id)
	{
		// アカウントを削除
		$ret = $oMgr->deleteAd($wireless_id);

		if (!$ret)
		{
			$end_flg = '0';
		}
		else
		{
			$end_flg = '1';
		}

		// 結果を更新
		$sql = "update guest_head_tbl set end_flg = '" . $end_flg . "' where guest_id = " . $guest_id;

		$ret = $oMgr->oDb->query($sql);


		if (!$ret)
		{
			echo "SQLエラー：結果更新失敗[guest_id:" . $guest_id . "]\n";
		}
	}
}

echo "[end:" . date("Y/m/d H:i:s") . "]\n";
exit;


?>
