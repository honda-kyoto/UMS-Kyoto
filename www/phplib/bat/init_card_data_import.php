<?php
/**********************************************************
* File         : daily_card_data_import.php
* Authors      : mie tsutsui
* Date         : 2014.08.23
* Last Update  : 2014.08.23
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$import_file = $argv[1];

// フィールド定義
$key_number_key = 5;
$user_name_key = 7;
$belong_info_1_key = 18;
$belong_info_2_key = 23;
$belong_info_3_key = 28;
$belong_info_4_key = 33;
$card_type_key = 38;
$uid_key = 39;
$start_date_key = 40;
$end_date_key = 41;
$suspend_flg_key = 42;


// 個人を特定するフィールド
//$user_key = "uid";
$user_key = "key_number";


//
// 比較用関数
//
function isChanged($aryOld, $aryNew)
{
	if ($aryOld['key_number'] != $aryNew['key_number'])
	{
		return true;
	}
	if ($aryOld['belong_info_1'] != $aryNew['belong_info_1'])
	{
		return true;
	}
	if ($aryOld['belong_info_2'] != $aryNew['belong_info_2'])
	{
		return true;
	}
	if ($aryOld['belong_info_3'] != $aryNew['belong_info_3'])
	{
		return true;
	}
	if ($aryOld['belong_info_4'] != $aryNew['belong_info_4'])
	{
		return true;
	}
	if ($aryOld['card_type'] != $aryNew['card_type'])
	{
		return true;
	}
	if ($aryOld['uid'] != $aryNew['uid'])
	{
		return true;
	}
	if ($aryOld['start_date'] != $aryNew['start_date'])
	{
		return true;
	}
	if ($aryOld['end_date'] != $aryNew['end_date'])
	{
		return true;
	}
	if ($aryOld['suspend_flg'] != $aryNew['suspend_flg'])
	{
		return true;
	}

	return false;
}

// 基本操作オブジェクト
$oMgr = new common_mgr();


//
// ファイルをチェック
//
if ($data = file_get_contents(CARD_KOJIN_DIR."/save/SVKOJIN.csv"))
{
	$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

	$aryData = explode("\n", $data);

	$cnt = 0;
	foreach ($aryData AS $body)
	{
		$cnt++;
		if ($cnt == 1 || $cnt == 2)
		{
			// 1-2行目はヘッダ情報
			continue;
		}

		if (trim($body) == "")
		{
			continue;
		}

		$aryBody = explode(",", $body);

		// 取り込み対象のデータのみ取得
		foreach ($aryBody AS $key => $val)
		{
			switch ($key)
			{
				case $key_number_key:
				case $user_name_key:
				case $belong_info_1_key:
				case $belong_info_2_key:
				case $belong_info_3_key:
				case $belong_info_4_key:
				case $card_type_key:
				case $start_date_key:
				case $end_date_key:
				case $suspend_flg_key:
					$val = trim($val);
					$val = trim($val,"\x22");
					break;
				default:
					break;
			}
		}
		
		$key_number_trim = trim($aryBody[$key_number_key],"\x22");
		$user_name_trim = trim($aryBody[$user_name_key],"\x22");
		$user_name_trim = str_replace('　','',$user_name_trim);
		$belong_info_1_trim = trim($aryBody[$belong_info_1_key],"\x22");
		$belong_info_2_trim = trim($aryBody[$belong_info_2_key],"\x22");
		$belong_info_3_trim = trim($aryBody[$belong_info_3_key],"\x22");
		$belong_info_4_trim = trim($aryBody[$belong_info_4_key],"\x22");
		$card_type_trim     = trim($aryBody[$card_type_key],"\x22");
		$uid_trim           = trim($aryBody[$uid_key],"\x22");
		$start_date_trim = trim($aryBody[$start_date_key],"\x22");
		$end_date_trim = trim($aryBody[$end_date_key],"\x22");
		$suspend_flg_trim = trim($aryBody[$suspend_flg_key],"\x22");
		
		$aryImp = array();
		$aryImp['key_number'] = $key_number_trim;
		$aryImp['user_name'] = $user_name_trim;
		$aryImp['belong_info_1'] = $belong_info_1_trim;
		$aryImp['belong_info_2'] = $belong_info_2_trim;
		$aryImp['belong_info_3'] = $belong_info_3_trim;
		$aryImp['belong_info_4'] = $belong_info_4_trim;
		// ※ハンズフリータグ：70、カード：50
		if ($card_type_trim == "70")
		{
			$card_type = "2";
		}
		else if ($card_type_trim == "50")
		{
			$card_type = "1";
		}
		else
		{
			echo $cnt . "行目：カード種別が無効のため処理できませんでした。\n";
			continue;
		}

		$aryImp['card_type'] = $card_type;
		$aryImp['uid'] = $uid_trim;
		$aryImp['start_date'] = $start_date_trim;
		$aryImp['end_date'] = $end_date_trim;
		$aryImp['suspend_flg'] = $suspend_flg_trim;

		if ($aryImp[$user_key] == "")
		{
			echo $cnt . "行目：キーとなる情報が未入力のため処理できませんでした。\n";
			continue;
		}

		$key_value = $oMgr->sqlItemChar($aryImp['user_name']);
//		$key_value = str_replace('　','',$key_value);
//		$key_value = str_replace(' ','',$key_value);
		
		// 現在の登録状況をチェック
		$sql = <<< SQL
SELECT
  user_id,
  kanjisei,
  kanjimei
FROM
  user_mst
WHERE
  kanjisei || kanjimei = $key_value
SQL;

		$aryResult = $oMgr->oDb->getAll($sql);

		if (count($aryResult) > 1)
		{
			//echo $cnt . "行目：キー項目[" . $user_key . ":" . $aryImp[$user_key] . "]は複数のレコードにヒットしたため処理できませんでした。\n";
			$body = mb_convert_encoding($body, "sjis-win", "UTF-8");
			file_put_contents("./"."hit_2over.csv", $body, FILE_APPEND);
			continue;
		}
		else if (count($aryResult) == 0)
		{
			//echo $cnt . "行目：キー項目[" . "user_name" . ":" . $aryImp['user_name'] . "]はヒットしないため処理できませんでした。\n";
			$body = mb_convert_encoding($body, "sjis-win", "UTF-8");
			file_put_contents("./"."hit_not.csv", $body, FILE_APPEND);
			continue;
		}
		else {
			//echo $cnt . "行目：キー項目[" . "user_name" . ":" . $aryImp['user_name'] . "]はOK。\n";
			//$body = mb_convert_encoding($body, "sjis-win", "UTF-8");
			//file_put_contents("./"."hit_ok.csv", $body, FILE_APPEND);
		}
		$aryRet = $aryResult[0];
//print_r($aryRet);
//print_r($aryImp);

			$user_id = $oMgr->sqlItemChar($aryRet['user_id']);
			$key_number = $oMgr->sqlItemChar($aryImp['key_number']);
			$belong_info_1 = $oMgr->sqlItemChar($aryImp['belong_info_1']);
			$belong_info_2 = $oMgr->sqlItemChar($aryImp['belong_info_2']);
			$belong_info_3 = $oMgr->sqlItemChar($aryImp['belong_info_3']);
			$belong_info_4 = $oMgr->sqlItemChar($aryImp['belong_info_4']);
			$card_type = $oMgr->sqlItemChar($aryImp['card_type']);
			$uid = $oMgr->sqlItemChar($aryImp['uid']);
			$start_date = $oMgr->sqlItemChar($aryImp['start_date']);
			$end_date = $oMgr->sqlItemChar($aryImp['end_date']);
			$suspend_flg = $oMgr->sqlItemFlg($aryImp['suspend_flg']);


			// DBを更新
			$sql = <<< SQL
INSERT INTO kyoto_user_card_tbl (
user_id,
list_no,
card_type,
permission_1,
permission_2,
permission_3,
permission_4,
permission_5,
permission_6,
permission_7,
permission_8,
permission_9,
permission_10,
permission_11,
permission_12,
permission_13,
permission_14,
permission_15,
permission_16,
permission_17,
permission_18,
permission_19,
permission_20,
permission_21,
permission_22,
permission_23,
permission_24,
permission_25,
belong_info_1,
belong_info_2,
belong_info_3,
belong_info_4,
key_number,
uid,
reissue_flg,
start_date,
end_date,
suspend_flg,
del_flg,
make_id,
update_id
)
VALUES
(
$user_id,
0,
$card_type,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
$belong_info_1,
$belong_info_2,
$belong_info_3,
$belong_info_4,
$key_number,
$uid,
0,
$start_date,
$end_date,
$suspend_flg,
0,
1,
1
)
SQL;

//print_r("sql".$sql."\n");
			$ret = $oMgr->oDb->query($sql);

			if (!$ret)
			{
				echo $cnt . "行目：DBの更新に失敗しました。"."userid:".$user_id.":\n";
				continue;
			}

			$body = mb_convert_encoding($body, "sjis-win", "UTF-8");
			file_put_contents("./"."hit_ok.csv", $body, FILE_APPEND);
			//echo $cnt . "行目：DBの更新が正常に終了しました。\n";
	}
	echo $cnt -3  . "行の処理が終了しました。\n";
}


exit;


?>
