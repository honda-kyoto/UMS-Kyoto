<?php
/**********************************************************
* File         : ido_data_import.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/users_detail_mgr.class.php");
require_once("sql/users_detail_sql.inc.php");


// 重複ログ出力関数
function writeDoubleLog($where, $condition, $err_msg, $aryRet)
{
	global $oMgr;
	global $fields_value;

	// 退職処理をしてしまわないため、未存在チェックフラグを一旦０に更新
	$sql = "update idodata set notexist_flg = '0' " . $where;

	$ret = $oMgr->oDb->query($sql);

	if (!$ret)
	{
		echo "初期処理に失敗しました。";
		exit;
	}

	echo "■".$condition."で複数データに一致：" . $err_msg;
	echo "\n";
	$line = 0;
	foreach ($aryRet AS $aryExt)
	{
		$line++;
		echo "---" . $line . "件目---\n";
		echo "利用者ID：" . $aryExt['user_id'];
		echo "\n";
		foreach ($fields_value AS $key => $title)
		{
			echo $title . "：" . $aryExt[$key];
			echo "\n";
		}
	}
	echo "---以上" . $line . "件---\n\n";
}


$dir = "import/";
$ido_file = 'idodata.csv';

$fields_value = array(
'cteiinkb' => '定員区分',
'cteiinnm' => '定員区分名称',
'cshainno' => '職員番号',
'dhtreingb_dte' => '発令年月日(西暦)',
'nnmn_ido_cde' => '任免異動種目コード',
'nnmn_ido_nme' => '任免異動種目',
'cnamekna' => 'カナ氏名',
'cnameknj' => '漢字氏名',
'kyu_kn_nme' => '旧姓使用カナ氏名',
'kyu_kj_nme' => '旧姓使用漢字氏名',
'seibetu_kbn' => '性別区分',
'seibetu_nme' => '性別',
'dbirth_dte' => '生年月日(西暦)',
'dsaiyo_dte' => '国家公務員採用日(西暦)',
'dninyo_dte' => '任用年月日(西暦)',
'kkn_cde' => '機関コード',
'kkn_nme' => '機関名称',
'szk_cde' => '所属コード',
'szk_nme' => '所属名称',
'bkyk_cde' => '部局コード',
'bkyk_nme' => '部局名称',
'kkrkoza_cde' => '掛・講座コード',
'kkrkoza_nme' => '掛・講座名称',
'knmei_cde' => '官名コード',
'knmei_nme' => '官名名称',
'syksy_cde' => '職種コード',
'syksy_nme' => '職種名称',
'hjksyk_skin_cde' => '非常勤職員職員コード',
'hjksyk_skin_nme' => '非常勤職員職員',
'hjksyk_misy_cde' => '非常勤職員名称コード',
'hjksyk_misy_nme' => '非常勤職員名称',
'dnnki_mr_dte' => '任期満了年月日(西暦)',
'djosin_prt_dte' => '上申書印刷日（西暦）',
'djirei_prt_dte' => '辞令印刷日（西暦）',
'getuji_flg' => '月次更新フラグ',
);

$fields = array_keys($fields_value);

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
					$post_code_nm = "syksy_cde";
					$job_code_nm = "knmei_cde";
				}
				else
				{
					$joukin_kbn = JOUKIN_KBN_PARTTIME;
					$post_code_nm = "hjksyk_misy_cde";
					$job_code_nm = "hjksyk_skin_cde";
				}
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

			// 掛講座が「000000」の場合、空にする
			if ($fields[$key] == "kkrkoza_cde")
			{
				if ($val == "000000")
				{
					$val = "";
				}
			}

			$vals[$fields[$key]] = $val;
		}

		$sqlCshainno = $oMgr->sqlItemChar($vals['cshainno']);
		$sqlCnameknj = $oMgr->sqlItemChar($vals['cnameknj']);
		$sqlDbirthDte = $oMgr->sqlItemChar($vals['dbirth_dte']);
		$sqlSeibetuKbn = $oMgr->sqlItemChar($vals['seibetu_kbn']);


		// 共通WHERE句
		$where1 = <<< SQL
 WHERE
  retire_fin_flg = '0' and
  cnameknj = $sqlCnameknj and
  dbirth_dte = $sqlDbirthDte and
  seibetu_kbn = $sqlSeibetuKbn;
SQL;

		$where2 = <<<SQL
WHERE
  retire_fin_flg = '0' and
  cshainno = $sqlCshainno;
SQL;

		$where3 = <<< SQL
 WHERE
  retire_fin_flg = '0' and
  cnameknj = $sqlCnameknj and
  dbirth_dte = $sqlDbirthDte and
  seibetu_kbn = $sqlSeibetuKbn and
  cshainno = $sqlCshainno;
SQL;


		//
		// 既存か新規かチェック
		//
		if ($vals['cnameknj'] != "" || $vals['cshainno'] != "")
		{
			$err_msg = "[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";

			$where = $where1;	// 条件は氏名、生年月日、性別をセット

			$sql = "select * from idodata " . $where;

			$aryRet = $oMgr->oDb->getAll($sql);

			// 複数ある場合
			if (is_array($aryRet) && count($aryRet) >= 2)
			{
				// 職員番号が一致するものがないかチェック
				$hit_data_exists = false;
				foreach ($aryRet AS $no => $aryExt)
				{
					if ($aryExt['cshainno'] == $vals['cshainno'])
					{
						$aryResult = $aryExt;
						$where = $where3;	// 条件を変更
						$hit_data_exists = true;
					}
				}

				// 一致するものがない場合はログに出力
				if (!$hit_data_exists)
				{
					writeDoubleLog($where, "氏名・生年月日・性別", $err_msg, $aryRet);

					continue;
				}

			}
			else
			{
				$aryResult = @$aryRet[0];
			}

			// 氏名、生年月日、性別で一致するデータが存在しなかった場合
			if (!isset($aryResult['user_id']))
			{
				$where = $where2;	// 条件は職員番号をセット

				$sql = "select * from idodata " . $where;

				$aryRet = $oMgr->oDb->getAll($sql);

				// 複数ある場合
				if (is_array($aryRet) && count($aryRet) >= 2)
				{
						writeDoubleLog($where, "職員番号", $err_msg, $aryRet);

						continue;
				}
			}

			$user_id = "";

			if (isset($aryResult['user_id']))
			{
				$user_id = $aryResult['user_id'];

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
				$sql .= ", notexist_flg = '0' ";

				$sql .= $where;

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
to_char(now(),'yyyy/MM/dd hh24:mm:ss') as start_date
FROM
idodata
$where
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
				$sql = "update idodata set user_id = " . $request['user_id'] . $where;


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
			// 利用者マスタと日もづいていない場合は無視
			if ($user_id == "")
			{
				continue;
			}

			$err_msg = "[利用者ID：" . $user_id . "]";

			$oMgr->oDb->begin();

			// 前日を期間終了日にする
			$sql = "update user_mst set end_date = (now() - INTERVAL '1 day')::date, update_time = now(), update_id = NULL where user_id = " . $user_id;

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
