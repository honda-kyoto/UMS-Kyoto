<?php
/**********************************************************
* File         : retry_user_ad_relation.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/users_detail_mgr.class.php");
require_once("sql/users_detail_sql.inc.php");

$dir = "import/";
$ido_file = 'idodata.csv';

$fields = array(
'cteiinkb',
'cteiinnm',
'cshainno',
'dhtreingb_dte',
'nnmn_ido_cde',
'nnmn_ido_nme',
'cnamekna',
'cnameknj',
'kyu_kn_nme',
'kyu_kj_nme',
'seibetu_kbn',
'seibetu_nme',
'dbirth_dte',
'dsaiyo_dte',
'dninyo_dte',
'kkn_cde',
'kkn_nme',
'szk_cde',
'szk_nme',
'bkyk_cde',
'bkyk_nme',
'kkrkoza_cde',
'kkrkoza_nme',
'knmei_cde',
'knmei_nme',
'syksy_cde',
'syksy_nme',
'hjksyk_skin_cde',
'hjksyk_skin_nme',
'hjksyk_misy_cde',
'hjksyk_misy_nme',
'dnnki_mr_dte',
'djosin_prt_dte',
'djirei_prt_dte',
'getuji_flg',
);


$has_error = false;
$oMgr = new users_detail_mgr();

//
// ファイルをチェック
//
if ($data = file_get_contents($dir.$ido_file, FILE_USE_INCLUDE_PATH))
{
	// 未存在チェックフラグを一旦１に更新（退職処理済みは除く）
	$sql = "update idodata set notexist_flg = '1' where retire_fin_flg = '0'";

	$ret = $oMgr->oDb->query($sql);

	if (!$ret)
	{
		echo "初期処理に失敗しました。";
		exit;
	}

	$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

	$aryData = explode("\n", $data);

	$cnt = 0;
	foreach ($aryData AS $body)
	{
		$cnt++;
		if ($cnt == 1)
		{
			// 1行目はタイトル
			continue;
		}

		$aryBody = explode(",", $body);

		$cshainno = "";
		$user_exists = false;
		$vals = array();
		foreach ($aryBody AS $key => $val)
		{
			$val = trim($val);
			$val = trim($val,"\x22");

			// 常勤区分を取得
			if ($fields[$key] == "cteiinkb")
			{
				if ($val == "1")
				{
					$joukin_kbn = JOUKIN_KBN_FULLTIME;
					//$post_code_nm = "knmei_cde";
					//$job_code_nm = "syksy_cde";
					$post_code_nm = "syksy_cde";
					$job_code_nm = "knmei_cde";
				}
				else
				{
					$joukin_kbn = JOUKIN_KBN_PARTTIME;
					//$post_code_nm = "hjksyk_skin_cde";
					//$job_code_nm = "hjksyk_misy_cde";
					$post_code_nm = "hjksyk_misy_cde";
					$job_code_nm = "hjksyk_skin_cde";
				}
			}

			// 社員番号を取得
			if ($fields[$key] == "cshainno")
			{
				$cshainno = $val;
			}

			// カナ名は全角に
			if ($fields[$key] == "cnamekna" || $fields[$key] == "kyu_kn_nme")
			{
				$val = string::han2zen($val);
				$val = str_replace(" ", "　", $val);
			}

			// 非常勤の場合役職コードに文字列を付与（特殊処理）
			if ($fields[$key] == "hjksyk_skin_cde")
			{
				if ($joukin_kbn == JOUKIN_KBN_PARTTIME)
				{
					$val = "HJK_" . $val;
				}
			}

			$vals[$fields[$key]] = $val;
		}

		//
		// 既存か新規かチェック
		//
		if ($cshainno != "")
		{
			$err_msg = "[社員番号：" . $cshainno . "]";

			$sql = "select * from idodata where cshainno = " . $oMgr->sqlItemChar($cshainno);

			$aryRet = $oMgr->oDb->getRow($sql);
			$user_id = $aryRet['user_id'];

			if ($aryRet['cshainno'] != "")
			{
				// 変更があるかチェック
				$has_change_data = false;
				foreach ($fields AS $key => $col)
				{
					if ($aryRet[$col] != $vals[$col])
					{
						// あり
						$has_change_data = true;
						break;
					}
				}

				$sql = "update idodata set update_time = now()";
				if ($has_change_data)
				{
					// 変更がある場合はデータ更新＋処理状況＝更新あり（１）に変更
					foreach ($fields AS $key => $col)
					{
						$sql .= "," . $col . " = '" . $vals[$col] . "'";
					}
				}
				$sql .= ", notexist_flg = '0'";

				$sql .= " where cshainno = " . $oMgr->sqlItemChar($cshainno);

				$ret = $oMgr->oDb->query($sql);

				if (!$ret)
				{
					echo $err_msg."中継テーブル更新に失敗しました。\n";
					$has_error = true;
					continue;
				}
			}
			else
			{
				$has_change_data = true;

				$sql = "insert into idodata (";
				$sql .= "user_id,";
				$sql .= implode(",", $fields);
				$sql .= ") values (";
				$sql .= "NULL";
				foreach ($fields AS $key => $col)
				{
					$sql .= ",'" . $vals[$col] . "'";
				}
				$sql .= ")";

				$ret = $oMgr->oDb->query($sql);

				if (!$ret)
				{
					echo $err_msg."中継テーブル登録に失敗しました。\n";

					print_r($vals);
					$has_error = true;
					continue;
				}
			}

			// 更新、追加でなければスキップ
			if (!$has_change_data)
			{
				continue;
			}

			$sqlCshainno = $oMgr->sqlItemChar($cshainno);

			// 連携項目を編集して取得
			$sql = <<< SQL
SELECT
user_id,
cshainno as staff_id,
cshainno as login_id,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(kyu_kj_nme, 0, position('　' in kyu_kj_nme)))
else
(select substr(cnameknj, 0, position('　' in cnameknj)))
end as kanjisei,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(kyu_kj_nme, position('　' in kyu_kj_nme)+1))
else
(select substr(cnameknj, position('　' in cnameknj)+1))
end as kanjimei,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(kyu_kn_nme, 0, position('　' in kyu_kn_nme)))
else
(select substr(cnamekna, 0, position('　' in cnamekna)))
end as kanasei,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(kyu_kn_nme, position('　' in kyu_kn_nme)+1))
else
(select substr(cnamekna, position('　' in cnamekna)+1))
end as kanamei,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(cnameknj, 0, position('　' in cnameknj)))
else '' end as kanjisei_real,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(cnameknj, position('　' in cnameknj)+1))
else '' end as kanjimei_real,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(cnamekna, 0, position('　' in cnamekna)))
else '' end as kanasei_real,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(cnamekna, position('　' in cnamekna)+1))
else '' end as kanamei_real,
case when seibetu_kbn = '1' then '0' when seibetu_kbn = '2' then '1' end as sex,
dbirth_dte as birthday,
case
when kkrkoza_cde != '0' and kkrkoza_cde != '' then
(select belong_chg_id from belong_chg_mst where belong_chg_code = kkrkoza_cde)
when szk_cde != '0' and szk_cde != '' then
(select belong_chg_id from belong_chg_mst where belong_chg_code = szk_cde)
when bkyk_cde != '0' and bkyk_cde != '' then
(select belong_chg_id from belong_chg_mst where belong_chg_code = bkyk_cde)
else
(select belong_chg_id from belong_chg_mst where belong_chg_code = 'XXXXXX')
end
as belong_chg_id,
(select post_id from post_mst where post_code = $post_code_nm) as post_id,
(select job_id from job_mst where job_code = $job_code_nm) as job_id,
'$joukin_kbn' as joukin_kbn,
'1' as user_type_id,
'2014/06/30 00:00:00' as start_date
FROM
idodata
WHERE
cshainno = $sqlCshainno
SQL;

			$request = $oMgr->oDb->getRow($sql);
			list ($y, $m, $d) = explode("/", $request['birthday']);
			$request['birth_year'] = $y;
			$request['birth_mon'] = $m;
			$request['birth_day'] = $d;


			if ($user_id != "")
			{
				// 既存
				$ret = $oMgr->updateUserBaseData($request);

				if (!$ret)
				{
					echo $err_msg."利用者基本情報更新に失敗しました。";
					$has_error = true;
					continue;
				}


				$ret = $oMgr->updateUserNcvcData($request);

				if (!$ret)
				{
					echo $err_msg."利用者NCVC情報更新に失敗しました。";
					$has_error = true;
					continue;
				}

			}
			else
			{
				// 新規
				$ret = $oMgr->insertUserData(&$request);

				if (!$ret)
				{
					echo $err_msg."利用者情報新規登録に失敗しました。";
					$has_error = true;
					continue;
				}

				// 中間テーブルのユーザIDを更新
				$sql = "update idodata set user_id = " . $request['user_id'] . " where cshainno = '" . $cshainno . "'";


				$ret = $oMgr->oDb->query($sql);

			}

		}
	}

	// ファイルになかった職員を退職扱いにする
	$sql = "select user_id from idodata where notexist_flg = '1' and retire_fin_flg = '0'";

	$aryRet = $oMgr->oDb->getCol($sql);

	if (is_array($aryRet))
	{
		foreach ($aryRet AS $user_id)
		{
			$err_msg = "[利用者ID：" . $user_id . "]";

			$oMgr->oDb->begin();

			$sql = "update user_mst set end_date = now()::date, update_time = now(), update_id = NULL where user_id = " . $user_id;

			$ret = $oMgr->oDb->query($sql);

			if (!$ret)
			{
				$oMgr->oDb->rollback();
				echo $err_msg."利用者退職処理に失敗しました。";
				$has_error = true;
				continue;
			}

			$sql = "update idodata set retire_fin_flg = '1', update_time = now() where user_id = " . $user_id;


			if (!$ret)
			{
				$oMgr->oDb->rollback();
				echo $err_msg."利用者退職処理に失敗しました。";
				$has_error = true;
				continue;
			}

			$oMgr->oDb->end();
		}
	}

}



if (!$has_error)
{
	echo "処理が正常に終了しました。";
}
exit;


?>
