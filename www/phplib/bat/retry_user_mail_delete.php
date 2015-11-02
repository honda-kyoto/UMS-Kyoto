<?php
/**********************************************************
* File         : retry_user_mail_delete.php
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
    login_id
FROM
    user_mail_errlog
WHERE
    kbn = '9' AND
    complete_flg='0'
";

$aryId = $oMgr->oDb->getAssoc2Ary($sql);

if (is_array($aryId))
{
	foreach ($aryId AS $log_cd => $login_id)
	{
		// 結果にかかわらず完了フラグ１を立てる
		$sql = "UPDATE mlist_members_errlog SET complete_flg = '1' WHERE log_cd = '" . $log_cd . "'";

		$oMgr->oDb->query($sql);

		$oMgr->delUserMailAddr($login_id);
	}
}

exit;


?>
