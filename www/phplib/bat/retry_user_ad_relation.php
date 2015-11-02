<?php
/**********************************************************
* File         : retry_user_ad_relation.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/user_regist_common_mgr.class.php");
require_once("sql/user_regist_common_sql.inc.php");

$oMgr = new user_regist_common_mgr();

$sql = "
SELECT
    log_cd,
    user_id
FROM
    user_ad_errlog
WHERE
    complete_flg='0'
";

$aryId = $oMgr->oDb->getAssoc2Ary($sql);

if (is_array($aryId))
{
	foreach ($aryId AS $log_cd => $user_id)
	{
		// 結果にかかわらず完了フラグ１を立てる
		$sql = "UPDATE user_ad_errlog SET complete_flg = '1' WHERE log_cd = '" . $log_cd . "'";

		$oMgr->oDb->query($sql);

		$aryUser = $oMgr->getUserData($user_id);
		$login_id = $aryUser['login_id'];

		// 連携
		$oMgr->relationAd($user_id, $login_id);
	}
}

exit;


?>
