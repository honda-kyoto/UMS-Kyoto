<?php
/**********************************************************
* File         : retry_auto_members.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");


$oMgr = new common_mgr();

$sql = "
SELECT
    log_cd,
    mlist_id
FROM
    mlist_members_errlog
WHERE
    complete_flg='0'
";

$aryId = $oMgr->oDb->getAssoc2Ary($sql);

if (is_array($aryId))
{
	foreach ($aryId AS $log_cd => $mlist_id)
	{
		$oMgr->relationAutoMembers($mlist_id);

		// 結果にかかわらず完了フラグ１を立てる
		$sql = "UPDATE mlist_members_errlog SET complete_flg = '1' WHERE log_cd = '" . $log_cd . "'";

		$oMgr->oDb->query($sql);
	}
}

exit;


?>
