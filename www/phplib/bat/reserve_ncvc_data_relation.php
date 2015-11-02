<?php
/**********************************************************
* File         : reserve_base_data_relation.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/users_detail_mgr.class.php");


$oMgr = new users_detail_mgr();

$oMgr->sessionStart();

$sql = "
SELECT
    user_id,
    login_id,
    login_passwd,
    eijisei,
    eijimei,
    mail_disused_flg,
    mail_acc,
    TO_CHAR(start_date, 'YYYY/MM/DD') AS start_date,
    TO_CHAR(end_date, 'YYYY/MM/DD') AS end_date,
    garoon_disused_flg,
    mlist_disused_flg,
    update_id
FROM
    user_ncvc_reserve
WHERE
    complete_flg = '0' AND
    reflect_date <= now()::date
";


$aryRsv = $oMgr->oDb->getAll($sql);

if (is_array($aryRsv))
{
	foreach ($aryRsv AS $aryData)
	{
		$aryData['password'] = $oMgr->passwordDecrypt($aryData['password']);

		$aryTmp = $oMgr->getRoleData($aryData['user_id'], true);
		$aryData['user_type_id'] = $aryTmp['user_type_id'];
		$aryData['user_role_id'] = $aryTmp['user_role_id'];

		$_SESSION['LOGIN_USER_ID'] = $aryData['update_id'];

		// 更新
		$ret = $oMgr->updateUserNcvcData($aryData);

		if (!$ret)
		{
			Debug_Trace("NCVC予約データ反映に失敗しました。", 721);
			Debug_Trace($aryData);
			exit;
		}

		$oMgr->oDb->begin();

		// 完了フラグ１を立てる
		$sql = "UPDATE user_ncvc_reserve SET complete_flg = '1' WHERE user_id = " . $aryData['user_id'];

		$oMgr->oDb->query($sql);

		$oMgr->oDb->end();

	}

}

exit;


?>
