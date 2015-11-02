<?php
/**********************************************************
* File         : retry_wireless_ad_relation.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/app_regist_common_mgr.class.php");
require_once("sql/app_regist_common_sql.inc.php");

$oMgr = new app_regist_common_mgr();

$sql = "
SELECT
    log_cd,
    user_id,
    wireless_id
FROM
    wireless_ad_errlog
WHERE
    complete_flg='0'
";

$aryId = $oMgr->oDb->getAssoc($sql);

if (!defined("LDAP_HOST_1"))
{
	exit;
}

//接続開始
$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
if (!$ldap_conn)
{
	$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
}

if (!$ldap_conn)
{
	Debug_Trace("接続失敗", 987);
	exit;
}

// バインド
$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

if (!$ldap_bind)
{
	Debug_Trace("バインド失敗", 987);
	exit;
}

if (is_array($aryId))
{
	foreach ($aryId AS $log_cd => $aryData)
	{
		// 結果にかかわらず完了フラグ１を立てる
		$sql = "UPDATE wireless_ad_errlog SET complete_flg = '1' WHERE log_cd = '" . $log_cd . "'";

		$oMgr->oDb->query($sql);

		$user_id = $aryData['user_id'];
		$wireless_id = $aryData['wireless_id'];

		// ユーザのパスワード
		$aryUser = $oMgr->getUserData($user_id);
		$passwd = $aryUser['login_passwd'];

		// 指定のワイアレスの設定を取得
		$sql = "
SELECT
    APL.vlan_id
FROM
    app_head_tbl AS APH,
    app_list_Tbl AS APL
WHERE
    APH.app_id = APL.app_id AND
    APH.wireless_id = '" . $oMgr->sqlItemChar($wireless_id) . "' AND
    APH.app_user_id = " . $user_id . " AND
    APL.busy_flg = '1'
";

		$vlan_id = $oMgr->oDb->getOne($sql);

		if ($vlan_id == "")
		{
			// とれない場合削除された
			continue;
		}

		$vlan_name = $this->getVlanName($vlan_id);
		$joinVl = str_replace("###VLAN_NAME###", $vlan_name, VLAN_DN);

		$aryGroupDn = array();
		$aryGroupDn[] = $joinVl;

		$ret = $this->addUserToAd(&$ldap_conn, $wireless_id, $passwd, WLESS_ID_DN, $aryGroupDn);

		if (!$ret)
		{
			// 未処理に戻す
			$sql = "UPDATE wireless_ad_errlog SET complete_flg = '0' WHERE log_cd = '" . $log_cd . "'";

			$oMgr->oDb->query($sql);
		}
	}
}

exit;


?>
