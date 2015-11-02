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

//ここから削除
//	// 退職処理をしてしまわないため、未存在チェックフラグを一旦０に更新
//	$sql = "update idodata set notexist_flg = '0' " . $where;
//
//	$ret = $oMgr->oDb->query($sql);
//
//	if (!$ret)
//	{
//		echo "初期処理に失敗しました。";
//		exit;
//	}

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

//ここから削除
//	// 未存在チェックフラグを一旦１に更新（退職処理済みは除く）
//	$sql = "update idodata set notexist_flg = '1' where retire_fin_flg = '0'";
//
//	$ret = $oMgr->oDb->query($sql);
//
//	if (!$ret)
//	{
//		echo "初期処理に失敗しました。";
//		exit;
//	}

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
  staff_id = $sqlCshainno;
SQL;

		$where2 = <<<SQL
WHERE
  kanjisei || '　' || kanjimei = $sqlCnameknj;
SQL;

		$where3 = <<< SQL
 WHERE
  kanjisei || '　' || kanjimei = $sqlCnameknj and
  birthday = $sqlDbirthDte and
  sex = $sqlSeibetuKbn and
  staff_id = $sqlCshainno;
SQL;


		//
		// 既存か新規かチェック
		//
		if ($vals['cnameknj'] != "" || $vals['cshainno'] != "")
		{

			$err_msg = "[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";

			$where = $where1;	// 条件は職員番号をセット

			// 連携項目を編集して取得
			$sql = <<< SQL
select 
case 
when CHG.belong_chg_code is not null and CHG.belong_chg_code != '' then 
CHG.belong_chg_code 
when SEC.belong_sec_code is not null and SEC.belong_sec_code != '' then 
SEC.belong_sec_code 
when DEP.belong_dep_code is not null and DEP.belong_dep_code != '' then 
DEP.belong_dep_code 
else 
'' 
end 
as belong_code, 
to_char(U.birthday,'yyyy/MM/dd') as dbirth_dte, 
* 
from user_mst as U 
left outer join job_mst as J on U.job_id = J.job_id 
left outer join post_mst as P on U.post_id = P.post_id 
left outer join belong_chg_mst as CHG on U.belong_chg_id = CHG.belong_chg_id 
left outer join belong_sec_mst as SEC on U.belong_chg_id = SEC.belong_sec_id 
left outer join belong_dep_mst as DEP on U.belong_chg_id = DEP.belong_dep_id 
$where
SQL;

			$aryRet = $oMgr->oDb->getAll($sql);

			// 職員番号で検索結果
			if (is_array($aryRet) && count($aryRet) >= 2)
			{
				//複数一致した場合はエラー
				$message = "職員番号複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				file_put_contents("/var/www/phplib/import/"."shokuinNo_2over.csv", $body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				//１件一致した場合
				$message = "職員番号１件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit.csv", $body, FILE_APPEND);
				
				//20141117  カタカナ小文字を大文字に変換
				$kananame_db  = str_replace("　", "", str_replace(" ", "", $aryRet[0]['kanasei'].$aryRet[0]['kanamei']));
				$kananame_csv = str_replace("　", "", str_replace(" ", "", $vals['cnamekna']));
				$replace_of = array('ァ','ィ','ゥ','ェ','ォ','ャ','ュ','ョ','ッ');
				$replace_by = array('ア','イ','ウ','エ','オ','ヤ','ユ','ヨ','ツ');
				$kananame_db = str_replace($replace_of, $replace_by, $kananame_db);
				$kananame_csv = str_replace($replace_of, $replace_by, $kananame_csv);
				
				$kanjiname_db = str_replace("　", "", str_replace(" ", "", $aryRet[0]['kanjisei'].$aryRet[0]['kanjimei']));
				$kanjiname_csv = str_replace("，", "", str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])));

				//差分のチェック
				$change_flg = false;
				if($kanjiname_db != $kanjiname_csv)
				{
					$change_flg = true;
					file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "kanjisei", FILE_APPEND);
				}
				else if($kananame_db != $kananame_csv)
				{
					$change_flg = true;
					file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "kanasei", FILE_APPEND);
				}
				else if($aryRet[0]['sex'] == "0")
				{
					if($vals['seibetu_kbn'] != "1")
					{
						$change_flg = true;
						file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "seibetu_kbn", FILE_APPEND);
					}
				}
				else if($aryRet[0]['sex'] == "1")
				{
					if($vals['seibetu_kbn'] != "2")
					{
						$change_flg = true;
						file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "seibetu_kbn", FILE_APPEND);
					}
				}
				else if($aryRet[0]['dbirth_dte'] != $vals['dbirth_dte'])
				{
					$change_flg = true;
					file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "dbirth_dte", FILE_APPEND);
				}
				else if($aryRet[0]['post_code'] != $vals['syksy_cde'])
				{
					$change_flg = true;
					file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "post_code", FILE_APPEND);
				}
				else if($aryRet[0]['job_code'] != $vals['knmei_cde'])
				{
					$change_flg = true;
					file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "job_code", FILE_APPEND);
				}
				else if($aryRet[0]['joukin_kbn'] == "0")
				{
					if($vals['cteiinkb'] != "1")
					{
						$change_flg = true;
						file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", "cteiinkb", FILE_APPEND);
					}
				}
				else if($aryRet[0]['joukin_kbn'] == "1")
				{
					if($vals['cteiinkb'] != "2")
					{
						$change_flg = true;
						file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv","cteiinkb", FILE_APPEND);
					}
				}
//				else if($aryRet[0]['belong_code'] != $vals['dbirth_dte'])
//				{
//					$change_flg = true;
//				}
				
				//echo "change_flg:".$change_flg.PHP_EOL;

				//差分がある場合は、更新
				if($change_flg)
				{
				//１件一致した場合
					$message = "職員番号１件一致更新有り："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_ari.csv", $body, FILE_APPEND);
				}
				//差分がない場合は、更新しない
				else
				{
					$message = "職員番号１件一致更新無し："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/"."shokuinNo_1hit_kousin_nasi.csv", $body, FILE_APPEND);
				}
				
				
				continue;
			}
			else
			{
				//１件も一致しなかった場合
			}

			// 職員番号で一致しなかった場合、氏名でチェック
			$sql = <<< SQL
select 
case 
when CHG.belong_chg_code is not null and CHG.belong_chg_code != '' then 
CHG.belong_chg_code 
when SEC.belong_sec_code is not null and SEC.belong_sec_code != '' then 
SEC.belong_sec_code 
when DEP.belong_dep_code is not null and DEP.belong_dep_code != '' then 
DEP.belong_dep_code 
else 
'' 
end 
as belong_code, 
to_char(U.birthday,'yyyy/MM/dd') as dbirth_dte, 
* 
from user_mst as U 
left outer join job_mst as J on U.job_id = J.job_id 
left outer join post_mst as P on U.post_id = P.post_id 
left outer join belong_chg_mst as CHG on U.belong_chg_id = CHG.belong_chg_id 
left outer join belong_sec_mst as SEC on U.belong_chg_id = SEC.belong_sec_id 
left outer join belong_dep_mst as DEP on U.belong_chg_id = DEP.belong_dep_id 
$where2
SQL;

			$aryRet = $oMgr->oDb->getAll($sql);

			// 複数ある場合
			if (is_array($aryRet) && count($aryRet) >= 2)
			{
				//複数一致した場合はエラー
				$message = "氏名複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				file_put_contents("/var/www/phplib/import/"."simei_2over.csv", $body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				$user_id = $aryRet['user_id'];
				//氏名で一件一致した場合
				$message = "氏名一件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."simei_1hit.csv", $body, FILE_APPEND);

//var_dump($aryRet);
				//差分のチェック
				$change_flg = false;
				if($aryRet[0]['kanjisei'].$aryRet[0]['kanjimei'] != str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])))
				{
					file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "kanjisei", FILE_APPEND);
					$change_flg = true;
				}
				else if($aryRet[0]['kanasei'].$aryRet[0]['kanamei'] != str_replace("　", "", str_replace(" ", "", $vals['cnamekna'])))
				{
					file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "kanasei", FILE_APPEND);
					$change_flg = true;
				}
				else if($aryRet[0]['sex'] == "0")
				{
					if($vals['seibetu_kbn'] != "1")
					{
						file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "seibetu_kbn", FILE_APPEND);
						$change_flg = true;
					}
				}
				else if($aryRet[0]['sex'] == "1")
				{
					if($vals['seibetu_kbn'] != "2")
					{
						file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "seibetu_kbn", FILE_APPEND);
						$change_flg = true;
					}
				}
				else if($aryRet[0]['dbirth_dte'] != $vals['dbirth_dte'])
				{
					file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "dbirth_dte", FILE_APPEND);
					$change_flg = true;
				}
				else if($aryRet[0]['post_code'] != $vals['syksy_cde'])
				{
					file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "post_code", FILE_APPEND);
					$change_flg = true;
				}
				else if($aryRet[0]['job_code'] != $vals['knmei_cde'])
				{
					file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "job_code", FILE_APPEND);
					$change_flg = true;
				}
				else if($aryRet[0]['joukin_kbn'] == "0")
				{
					if($vals['cteiinkb'] != "1")
					{
						file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "joukin_kbn", FILE_APPEND);
						$change_flg = true;
					}
				}
				else if($aryRet[0]['joukin_kbn'] == "1")
				{
					if($vals['cteiinkb'] != "2")
					{
						$change_flg = true;
						file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", "joukin_kbn", FILE_APPEND);
					}
				}
//				else if($aryRet[0]['belong_code'] != $vals['dbirth_dte'])
//				{
//					$change_flg = true;
//				}
				
				//echo "change_flg:".$change_flg.PHP_EOL;

				//差分がある場合は、更新
				if($change_flg)
				{
				//１件一致した場合
					$message = "氏名１件一致更新有り："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/"."simei_kousin_ari.csv", $body, FILE_APPEND);
				}
				//差分がない場合は、更新しない
				else
				{
					$message = "氏名１件一致更新無し："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/"."simei_1hit_kousin_nasi.csv", $body, FILE_APPEND);
				}

			}

		}
	}
}




	echo "処理が正常に終了しました。".PHP_EOL;

exit;


?>
