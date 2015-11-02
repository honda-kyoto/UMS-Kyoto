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
    staff_id,
    staff_id_flg,
    kanjisei,
    kanjimei,
    kanasei,
    kanamei,
    eijisei,
    eijimei,
    kanjisei_real,
    kanjimei_real,
    kanasei_real,
    kanamei_real,
    kyusei,
    sex,
    date_part('year', birthday) AS birth_year,
    date_part('month', birthday) AS birth_mon,
    date_part('day', birthday) AS birth_day,
    belong_chg_id,
    post_id,
    job_id,
    naisen,
    pbno,
    joukin_kbn,
    note,
    make_id,
    update_id,
    TO_CHAR(retire_date, 'YYYY/MM/DD') AS retire_date
FROM
    user_base_reserve
WHERE
    complete_flg = '0' AND
    reflect_date <= now()::date
";

$aryRsv = $oMgr->oDb->getAll($sql);

if (is_array($aryRsv))
{
	foreach ($aryRsv AS $aryData)
	{
		$aryTmp = $oMgr->getSubBelongData($aryData['user_id'], true);
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $data)
			{
				$aryData['sub_belong_chg_id'][$no] = $data['sub_belong_chg_id'];
			}
		}

		$aryTmp = $oMgr->getSubJobData($aryData['user_id'], true);
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $data)
			{
				$aryData['sub_job_id'][$no] = $data['sub_job_id'];
			}
		}

		$aryTmp = $oMgr->getSubPostData($aryData['user_id'], true);
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $data)
			{
				$aryData['sub_post_id'][$no] = $data['sub_post_id'];
			}
		}

		$aryTmp = $oMgr->getSubStaffIdData($aryData['user_id'], true);
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $data)
			{
				$aryData['sub_staff_id'][$no] = $data['sub_staff_id'];
			}
		}

		$_SESSION['LOGIN_USER_ID'] = $aryData['update_id'];

		// 更新
		$ret = $oMgr->updateUserBaseData($aryData);

		if (!$ret)
		{
			Debug_Trace("基本情報予約データ反映に失敗しました。", 621);
			Debug_Trace($aryData);
			exit;
		}

		$oMgr->oDb->begin();

		// 完了フラグ１を立てる
		$sql = "UPDATE user_base_reserve SET complete_flg = '1' WHERE user_id = " . $aryData['user_id'];

		$oMgr->oDb->query($sql);

		$oMgr->oDb->end();

	}

}

exit;


?>
