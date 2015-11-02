<?php
/**********************************************************
* File         : retry_user_mail_edit.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");
require_once("sql/common_sql.inc.php");

$oMgr = new common_mgr();

$sql = "
SELECT
    log_cd,
    user_id,
    login_id
FROM
    user_mail_errlog
WHERE
    kbn = '1' AND
    complete_flg='0'
";

$aryId = $oMgr->oDb->getAssoc($sql);

if (is_array($aryId))
{
	foreach ($aryId AS $log_cd => $aryData)
	{
		// 結果にかかわらず完了フラグ１を立てる
		$sql = "UPDATE mlist_members_errlog SET complete_flg = '1' WHERE log_cd = '" . $log_cd . "'";

		$oMgr->oDb->query($sql);

		$user_id = $aryData['user_id'];
		$login_id = $aryData['login_id'];

		// 統合IDが変わっていないか確認
		$aryUser = $oMgr->getUserData($user_id);

		// 変わっていたら処理しない
		if ($login_id != $aryUser['login_id'])
		{
			continue;
		}

		$oMgr->relationUserMailAddr("", $user_id);
	}
}

exit;


?>
