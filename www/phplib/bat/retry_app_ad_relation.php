<?php
/**********************************************************
* File         : retry_app_ad_relation.php
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
    app_id
FROM
    app_ad_errlog
WHERE
    complete_flg='0'
";

$aryId = $oMgr->oDb->getAssoc2Ary($sql);

if (is_array($aryId))
{
	foreach ($aryId AS $log_cd => $app_id)
	{
		// 結果にかかわらず完了フラグ１を立てる
		$sql = "UPDATE app_ad_errlog SET complete_flg = '1' WHERE log_cd = '" . $log_cd . "'";

		$oMgr->oDb->query($sql);

		$aryEnt = $oMgr->getAppHead($app_id);
		$aryEntList = $oMgr->getAppList($app_id);

		// 連携
		$ret = $oMgr->relationAd($aryEnt['wire_kbn'], array(), $aryEnt, array(), $aryEntList, true);

		if (!$ret)
		{
			// 未処理に戻す
			$sql = "UPDATE app_ad_errlog SET complete_flg = '0' WHERE log_cd = '" . $log_cd . "'";

			$oMgr->oDb->query($sql);
		}
	}
}

exit;


?>
