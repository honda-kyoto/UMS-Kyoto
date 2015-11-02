<?php
/**********************************************************
* File         : passwd_encrypt.php
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

$sql = "SELECT user_id, login_passwd FROM user_mst WHERE login_passwd != ''";

$aryPw = $oMgr->oDb->getAssoc2Ary($sql);

if (is_array($aryPw))
{
	foreach ($aryPw AS $user_id => $passwd)
	{
		$passwd = $oMgr->passwordEncrypt($passwd);

		$sql = "UPDATE user_mst SET login_passwd = '" . string::replaceSql($passwd) . "' WHERE user_id = " . $user_id;

		$ret = $oMgr->oDb->query($sql);

		if (!$ret)
		{
			echo $sql;
			$oMgr->oDb->rollback();
			exit;
		}
	}
}

$sql = "SELECT user_id, list_no, password FROM user_his_tbl WHERE password != ''";

$aryHis = $oMgr->oDb->getAll($sql);

if (is_array($aryHis))
{
	foreach ($aryHis AS $data)
	{
		$user_id = $data['user_id'];
		$list_no = $data['list_no'];
		$password = $data['password'];

		$password = $oMgr->passwordEncrypt($password);

		$sql = "UPDATE user_his_tbl SET password = '" . string::replaceSql($password) . "' WHERE user_id = " . $user_id . " AND list_no = " . $list_no;

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
