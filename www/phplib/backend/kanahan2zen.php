<?php
/**********************************************************
* File         : kanahan2zen.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
exit;

set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$oMgr = new common_mgr();

$oMgr->oDb->begin();

$sql = "SELECT user_id, kanasei, kanamei FROM user_mst";

$aryPw = $oMgr->oDb->getAssoc($sql);

if (is_array($aryPw))
{
	foreach ($aryPw AS $user_id => $data)
	{
		$kanasei = string::han2zen($data['kanasei']);
		$kanamei = string::han2zen($data['kanamei']);

		$sql = "UPDATE user_mst SET kanasei = '" . string::replaceSql($kanasei) . "', kanamei = '" . string::replaceSql($kanamei) . "' WHERE user_id = " . $user_id;

		$ret = $oMgr->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			$oMgr->oDb->rollback();
			exit;
		}
	}
}

$sql = "SELECT user_id, list_no, kananame FROM user_his_tbl WHERE kananame != ''";

$aryHis = $oMgr->oDb->getAll($sql);

if (is_array($aryHis))
{
	foreach ($aryHis AS $data)
	{
		$user_id = $data['user_id'];
		$list_no = $data['list_no'];
		$kananame = $data['kananame'];

		$kananame = string::han2zen($kananame);
		$kananame = str_replace(" ", "　", $kananame);

		$sql = "UPDATE user_his_tbl SET kananame = '" . string::replaceSql($kananame) . "' WHERE user_id = " . $user_id . " AND list_no = " . $list_no;

		$ret = $oMgr->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			$oMgr->oDb->rollback();
			exit;
		}
	}
}

$oMgr->oDb->end();

exit;


?>
