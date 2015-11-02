<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

$oMgr = new common_mgr();

$sql = "
SELECT
    UHT.staffcode,
    UHT.wardcode,
    UHT.professioncode,
    UHT.password,
    UHT.kananame,
    UHT.kanjiname,
    UHT.deptcode,
    UHT.gradecode,
    UHT.appcode,
    USM.eijimei || '.' || USM.eijisei AS eijiname,
    TO_CHAR(USM.birthday, 'YYYYMMDD') AS birthday,
    USM.sex,
    USM.pbno,
    USM.naisen,
    TO_CHAR(UHT.validstartdate, 'YYYYMMDD') AS validstartdate,
    TO_CHAR(UHT.validenddate, 'YYYYMMDD') AS validenddate,
    TO_CHAR(UHT.send_date, 'YYYYMMDD') AS send_date,
    UPU.staffcode as update_staffcode,
    HWM.wardname,
    HDM.deptname,
    HGM.gradename,
    USM.note
FROM
    user_his_tbl AS UHT
    inner join user_mst as USM on
        UHT.user_id  = USM.user_id
    left outer join user_his_tbl as UPU on
        UHT.update_id = UPU.user_id and
        UPU.list_no = 0 and UPU.del_flg = '0'
    LEFT OUTER JOIN his_ward_mst AS HWM ON
        UHT.wardcode = HWM.wardcode
    LEFT OUTER JOIN his_profession_mst AS HPM ON
        UHT.professioncode = HPM.professioncode
    LEFT OUTER JOIN his_dept_mst AS HDM ON
        UHT.deptcode = HDM.deptcode
    left outer join his_grade_mst HGM ON
        UHT.gradecode = HGM.gradecode
WHERE
    UHT.del_flg = '0'
ORDER BY
    UHT.user_id,
    UHT.list_no
";

$aryRet = $oMgr->oDb->getAll($sql);

$strUser = "";
if (is_array($aryRet))
{
	foreach ($aryRet AS $data)
	{
		// 固定
		$strUser .= "1601";

		// 職員コード
		$strUser .= str_pad($data['staffcode'], 10);

		// 新旧フラグ固定
		$strUser .= "0";

		// 予備
		$strUser .= str_pad("", 15);

		// 所属部署コード
		$strUser .= str_pad($data['wardcode'], 5);

		// 職種コード
		$strUser .= str_pad($data['professioncode'], 3);

		// パスワード
		$password = $data['password'];
		$password = $oMgr->passwordDecrypt($password);
		if (strlen($password) > 5)
		{
			$password = substr($password, 0, 5);
		}
		$strUser .= str_pad($password, 5);

		// 職員カナ名称
		$kananame = str_replace("　", " ", $data['kananame']);
		$kananame = string::zen2han($kananame);
		$strUser .= string::mb_str_pad($kananame, 20);

		// 職員漢字名称
		$strUser .= string::mb_str_pad($data['kanjiname'], 20, "　");

		// 発行番号
		$strUser .= str_pad("", 4);

		// 給与職員番号
		$strUser .= str_pad("", 4);

		// 所属科コード
		$strUser .= str_pad($data['deptcode'], 2);

		// 役職コード
		$strUser .= str_pad($data['gradecode'], 2);

		// 棒給表ｺｰﾄﾞ
		$strUser .= str_pad("", 2);

		// 所属科コード
		$strUser .= str_pad($data['deptcode'], 2);

		// 予約項目コード
		$strUser .= str_pad($data['appcode'], 5);

		// 予備
		$strUser .= str_pad("", 19);

		// ローマ字氏名
		$eijiname = $data['eijiname'];
		if (strlen($eijiname) > 25)
		{
			list ($sei, $mei) = explode(".", $eijiname);
			$sei = substr($sei, 0, 1);
			$eijiname = $sei . "." . $mei;
		}
		$strUser .= str_pad($eijiname, 25);

		// 住所
		$strUser .= string::mb_str_pad("", 60, "　");

		// 備考
		$note = string::han2zen($date['note']);
		$note = string::nr2null($note);
		if (string::strlen($note) > 60)
		{
			$note = mb_substr($note, 0, 60, 'UTF-8');
		}
		$strUser .= string::mb_str_pad($note, 60, "　");

		// 電話番号
		$strUser .= str_pad("", 12);

		// 生年月日
		$strUser .= str_pad($data['birthday'], 8);

		// 性別
		$sex = "";
		if ($data['sex'] == "0")
		{
			$sex = "M";
		}
		else if ($data['sex'] == "1")
		{
			$sex = "F";
		}
		$strUser .= str_pad($sex, 1);

		// 終了区分
		$strUser .= "0";

		// PHS番号
		$pbno = substr($data['pbno'], 1, 3);
		$strUser .= str_pad($pbno, 3);

		// 内線
		$strUser .= str_pad($data['naisen'], 4);

		// 有効開始日
		$strUser .= str_pad($data['validstartdate'], 8);

		// 有効終了日
		$strUser .= str_pad($data['validenddate'], 8);

		// 予備
		$strUser .= str_pad("", 10);

		// 更新日
		$strUser .= str_pad($data['send_date'], 8);

		// 更新端末
		$strUser .= str_pad("", 4);

		// 更新者
		$strUser .= str_pad($data['update_staffcode'], 10);

		// 部署名
		$strUser .= string::mb_str_pad($data['wardname'], 40, "　");

		// 所属名
		$strUser .= string::mb_str_pad($data['deptname'], 20, "　");

		// 役職名
		$strUser .= string::mb_str_pad($data['gradename'], 20, "　");

		// 棒給表名
		$strUser .= string::mb_str_pad("", 10, "　");

		// 予備
		$strUser .= str_pad("", 5);

		// 扉許可情報
		$strUser .= "111111111111111111111111111111111111111111111111111111111111";

		$strUser .= "\n";

	}
}

if ($strUser != "")
{
	$strUser = mb_convert_encoding($strUser, "sjis-win", "UTF-8");
	file_put_contents($dir."/toshokan_users.txt", $strUser);
}

exit;


?>
