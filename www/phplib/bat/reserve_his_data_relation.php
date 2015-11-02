<?php
/**********************************************************
* File         : reserve_his_data_relation.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/users_detail_mgr.class.php");


$oMgr = new users_detail_mgr();

$sql = "
SELECT
    user_id,
    list_no,
    staffcode,
    wardcode,
    professioncode,
    gradecode,
    kananame,
    kanjiname,
    password,
    TO_CHAR(validstartdate, 'YYYY/MM/DD') AS validstartdate,
    TO_CHAR(validenddate, 'YYYY/MM/DD') AS validenddate,
    deptcode,
    appcode,
    deptgroupcode,
    history_note,
    his_history_kbn,
    make_id,
    update_id
FROM
    user_his_reserve
WHERE
    del_flg = '0' AND
    send_date <= now()::date
";

$aryRsv = $oMgr->oDb->getAll($sql);

if (is_array($aryRsv))
{
	foreach ($aryRsv AS $aryData)
	{
		$aryData['edit_mode'] = "";
		$aryData['immediate_flg'] = "1";

		if ($aryData['password'] != "")
		{
			$aryData['password'] = $oMgr->passwordDecrypt($aryData['password']);
		}

		// 更新
		$ret = $oMgr->updateUserHisData($aryData);

		if (!$ret)
		{
			Debug_Trace("HIS連携予約データ反映に失敗しました。", 521);
			Debug_Trace($aryData);
			exit;
		}

		$oMgr->oDb->begin();
		// 削除フラグ１を立てる
		$sql = "UPDATE user_his_reserve SET del_flg = '1' WHERE user_id = " . $aryData['user_id'] . " AND list_no = " . $aryData['list_no'];

		$oMgr->oDb->query($sql);

		$oMgr->oDb->end();

	}

}

exit;


?>
