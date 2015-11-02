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
		$sqlCnamekna = $oMgr->sqlItemChar($vals['cnamekna']);
		$sqlDbirthDte = $oMgr->sqlItemChar($vals['dbirth_dte']);
		$sqlSeibetuKbn = $oMgr->sqlItemChar($vals['seibetu_kbn']);

		// 旧姓姓
		$aryKyusei = explode("　", $vals['kyu_kj_nme']);
		$sqlKyuKjSei = $oMgr->sqlItemChar($aryKyusei[0]);
		// カナ名
		$aryCnameKna = explode("　", $vals['cnamekna']);
		$sqlCnameknamei = $oMgr->sqlItemChar($aryCnameKna[1]);


		// 共通WHERE句
		$where1 = <<< SQL
 WHERE
  staff_id = $sqlCshainno;
SQL;

		$where2 = <<<SQL
WHERE
  kanjisei || '　' || kanjimei = $sqlCnameknj and
  birthday = $sqlDbirthDte;
SQL;

		$where3 = <<< SQL
 WHERE
  kanasei || '　' || kanamei = $sqlCnamekna;
SQL;

//		$where3 = <<< SQL
// WHERE
//  kanjisei || '　' || kanjimei = $sqlCnameknj and
//  birthday = $sqlDbirthDte and
//  sex = $sqlSeibetuKbn and
//  staff_id = $sqlCshainno;
//SQL;
		$where4 = <<< SQL
 WHERE
  kanjisei = $sqlKyuKjSei and kanamei = $sqlCnameknamei;
SQL;


		//
		// 既存か新規かチェック
		//
		if ($vals['cnameknj'] != "" || $vals['cshainno'] != "")
		{

			$err_msg = "[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";

//①
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
				$message = "職員番号複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				file_put_contents("/var/www/phplib/import/"."sonota_1only.csv", "①".$body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				//１件一致した場合
				//$message = "職員番号１件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
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
				$change = "";
				//if($aryRet[0]['kanjisei'].$aryRet[0]['kanjimei'] != str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])))
				//１．漢字姓名
				if($kanjiname_db != $kanjiname_csv)
				{
					$change_flg = true;
					$change .= "|漢字姓名|";
				}
				//else if($aryRet[0]['kanasei'].$aryRet[0]['kanamei'] != str_replace("　", "", str_replace(" ", "", $vals['cnamekna'])))
				//２．カナ姓名
				if($kananame_db != $kananame_csv)
				{
					$change_flg = true;
					$change .= "|カナ姓名|";
				}
				//３．性別
				if($aryRet[0]['sex'] == "0")
				{
					if($vals['seibetu_kbn'] != "1")
					{
						$change_flg = true;
						$change .= "|性別|";
					}
				}
				if($aryRet[0]['sex'] == "1")
				{
					if($vals['seibetu_kbn'] != "2")
					{
						$change_flg = true;
						$change .= "|性別|";
					}
				}
				
				//４．生年月日
				if($aryRet[0]['dbirth_dte'] != $vals['dbirth_dte'])
				{
					$change_flg = true;
					$change .= "|生年月日|";
				}
				
				//５．掛講座
				if($aryRet[0]['belong_code'] != $vals['kkrkoza_cde'])
				{
					$change_flg = true;
					$change .= "|掛講座|";
				}
				
				//６．定員区分
				if($aryRet[0]['joukin_kbn'] == "0")
				{
					if($vals['cteiinkb'] != "1")
					{
						$change_flg = true;
						$change .= "|定員区分|";
					}
					else
					{
						//定員区分が変更無し（定員）
						if($aryRet[0]['post_code'] != $vals['syksy_cde'])
						{
							$change_flg = true;
							$change .= "|役職|";
						}
						if($aryRet[0]['job_code'] != $vals['knmei_cde'])
						{
							$change_flg = true;
							$change .= "|職種|";
						}
					}
				}
				if($aryRet[0]['joukin_kbn'] == "1")
				{
					if($vals['cteiinkb'] != "2")
					{
						$change_flg = true;
						$change .= "|常勤/非常勤|";
					}
					else
					{
						//定員区分が変更無し（非常勤）
						if($aryRet[0]['post_code'] != $vals['hjksyk_misy_cde'])
						{
							$change_flg = true;
							$change .= "|役職|";
						}
						if($aryRet[0]['job_code'] != $vals['hjksyk_skin_cde'])
						{
							$change_flg = true;
							$change .= "|職種|";
						}
					}
				}

				//echo "change_flg:".$change_flg.PHP_EOL;

				//差分がある場合は、更新
				if($change_flg)
				{
				//１件一致した場合
					$message = "職員番号１件一致更新有り："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/"."sabun_ari_1only.csv", "①".$change.$body, FILE_APPEND);
				}
				//差分がない場合は、更新しない
				else
				{
					$message = "職員番号１件一致更新無し："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/"."sabun_nashi_1only.csv", "①".$body, FILE_APPEND);
				}
				
				
				continue;
			}
			else
			{
				//１件も一致しなかった場合
			}

//②
			$where = $where2;	// 条件は氏名+生年月日をセット
			
			// 職員番号で一致しなかった場合、氏名+生年月日でチェック
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

			// 複数ある場合
			if (is_array($aryRet) && count($aryRet) >= 2)
			{
				//複数一致した場合はエラー
				$message = "氏名+生年月日複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."sonota.csv", "②".$body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				$user_id = $aryRet['user_id'];
				//氏名+生年月日で一件一致した場合
				//$message = "氏名+生年月日一件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."kanjisimei_1hit.csv", $body, FILE_APPEND);

//var_dump($aryRet);
				//差分のチェック
				$change_flg = false;
				$change = "";
				//if($aryRet[0]['kanjisei'].$aryRet[0]['kanjimei'] != str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])))
				//１．漢字姓名
				if($kanjiname_db != $kanjiname_csv)
				{
					$change_flg = true;
					$change .= "cnameknj";
				}
				//else if($aryRet[0]['kanasei'].$aryRet[0]['kanamei'] != str_replace("　", "", str_replace(" ", "", $vals['cnamekna'])))
				//２．カナ姓名
				if($kananame_db != $kananame_csv)
				{
					$change_flg = true;
					$change .= "cnamekna";
				}
				//３．性別
				if($aryRet[0]['sex'] == "0")
				{
					if($vals['seibetu_kbn'] != "1")
					{
						$change_flg = true;
						$change .= "seibetu_kbn";
					}
				}
				if($aryRet[0]['sex'] == "1")
				{
					if($vals['seibetu_kbn'] != "2")
					{
						$change_flg = true;
						$change .= "seibetu_kbn";
					}
				}
				
				//４．生年月日
				if($aryRet[0]['dbirth_dte'] != $vals['dbirth_dte'])
				{
					$change_flg = true;
					$change .= "dbirth_dte";
				}
				
				//５．掛講座
				if($aryRet[0]['belong_code'] != $vals['kkrkoza_cde'])
				{
					$change_flg = true;
					$change .= "kkrkoza_cde";
				}
				
				//６．定員区分
				if($aryRet[0]['joukin_kbn'] == "0")
				{
					if($vals['cteiinkb'] != "1")
					{
						$change_flg = true;
						$change .= "cteiinkb";
					}
					else
					{
						//定員区分が変更無し（定員）
						if($aryRet[0]['post_code'] != $vals['syksy_cde'])
						{
							$change_flg = true;
							$change .= "syksy_cde";
						}
						if($aryRet[0]['job_code'] != $vals['knmei_cde'])
						{
							$change_flg = true;
							$change .= "knmei_cde";
						}
					}
				}
				if($aryRet[0]['joukin_kbn'] == "1")
				{
					if($vals['cteiinkb'] != "2")
					{
						$change_flg = true;
						$change .= "cteiinkb";
					}
					else
					{
						//定員区分が変更無し（非常勤）
						if($aryRet[0]['post_code'] != $vals['hjksyk_misy_cde'])
						{
							$change_flg = true;
							$change .= "syksy_cde";
						}
						if($aryRet[0]['job_code'] != $vals['hjksyk_skin_cde'])
						{
							$change_flg = true;
							$change .= "knmei_cde";
						}
					}
				}
				
				//echo "change_flg:".$change_flg.PHP_EOL;

				//差分がある場合は、更新
				if($change_flg)
				{
				//１件一致した場合
					$message = "氏名+生年月日１件一致更新有り："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					//file_put_contents("/var/www/phplib/import/"."sabun_ari.csv", "②".$change.$body, FILE_APPEND);
				}
				//差分がない場合は、更新しない
				else
				{
					$message = "氏名+生年月日１件一致更新無し："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					//file_put_contents("/var/www/phplib/import/"."sabun_nashi.csv", "②".$body, FILE_APPEND);
				}

				continue;
			}
			else
			{
				//１件も一致しなかった場合
			}

//③
			$where = $where3;	// 条件は氏名カナをセット
			
			// 職員番号、氏名+生年月日で一致しなかった場合、氏名カナでチェック
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

			// 複数ある場合
			if (is_array($aryRet) && count($aryRet) >= 2)
			{
				//複数一致した場合はエラー
				$message = "氏名カナ複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."sonota.csv", "③".$body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				$user_id = $aryRet['user_id'];
				//氏名カナで一件一致した場合
				//$message = "氏名カナ一件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."kanaseimei_1hit.csv", $body, FILE_APPEND);

//var_dump($aryRet);
				//差分のチェック
				$change_flg = false;
				$change = "";
				//if($aryRet[0]['kanjisei'].$aryRet[0]['kanjimei'] != str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])))
				//１．漢字姓名
				if($kanjiname_db != $kanjiname_csv)
				{
					$change_flg = true;
					$change .= "cnameknj";
				}
				//else if($aryRet[0]['kanasei'].$aryRet[0]['kanamei'] != str_replace("　", "", str_replace(" ", "", $vals['cnamekna'])))
				//２．カナ姓名
				if($kananame_db != $kananame_csv)
				{
					$change_flg = true;
					$change .= "cnamekna";
				}
				//３．性別
				if($aryRet[0]['sex'] == "0")
				{
					if($vals['seibetu_kbn'] != "1")
					{
						$change_flg = true;
						$change .= "seibetu_kbn";
					}
				}
				if($aryRet[0]['sex'] == "1")
				{
					if($vals['seibetu_kbn'] != "2")
					{
						$change_flg = true;
						$change .= "seibetu_kbn";
					}
				}
				
				//４．生年月日
//				if($aryRet[0]['dbirth_dte'] != $vals['dbirth_dte'])
//				{
//					$change_flg = true;
//					$change .= "dbirth_dte";
//				}
				
				//５．掛講座
				if($aryRet[0]['belong_code'] != $vals['kkrkoza_cde'])
				{
					$change_flg = true;
					$change .= "kkrkoza_cde";
				}
				
				//６．定員区分
				if($aryRet[0]['joukin_kbn'] == "0")
				{
					if($vals['cteiinkb'] != "1")
					{
						$change_flg = true;
						$change .= "cteiinkb";
					}
					else
					{
						//定員区分が変更無し（定員）
						if($aryRet[0]['post_code'] != $vals['syksy_cde'])
						{
							$change_flg = true;
							$change .= "syksy_cde";
						}
						if($aryRet[0]['job_code'] != $vals['knmei_cde'])
						{
							$change_flg = true;
							$change .= "knmei_cde";
						}
					}
				}
				if($aryRet[0]['joukin_kbn'] == "1")
				{
					if($vals['cteiinkb'] != "2")
					{
						$change_flg = true;
						$change .= "cteiinkb";
					}
					else
					{
						//定員区分が変更無し（非常勤）
						if($aryRet[0]['post_code'] != $vals['hjksyk_misy_cde'])
						{
							$change_flg = true;
							$change .= "syksy_cde";
						}
						if($aryRet[0]['job_code'] != $vals['hjksyk_skin_cde'])
						{
							$change_flg = true;
							$change .= "knmei_cde";
						}
					}
				}
				
				
				//echo "change_flg:".$change_flg.PHP_EOL;

				//差分がある場合は、更新
				if($change_flg)
				{
				//１件一致した場合
					$message = "氏名カナ１件一致更新有り："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					//file_put_contents("/var/www/phplib/import/"."sabun_ari.csv", "③".$change.$body, FILE_APPEND);
				}
				//差分がない場合は、更新しない
				else
				{
					$message = "氏名カナ１件一致更新無し："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					//file_put_contents("/var/www/phplib/import/"."sabun_nashi.csv", "③".$body, FILE_APPEND);
				}

				continue;
			}
			else
			{
				//１件も一致しなかった場合
			}
			
//④
			$where = $where4;	// 旧姓漢字姓+氏名カナ名をセット
			
			// 旧姓漢字姓+氏名カナ名でチェック
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

			// 複数ある場合
			if (is_array($aryRet) && count($aryRet) >= 2)
			{
				//複数一致した場合はエラー
				$message = "旧姓漢字姓+カナ名複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."sonota.csv", "④".$body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				$user_id = $aryRet['user_id'];
				//氏名カナで一件一致した場合
				//$message = "氏名カナ一件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/"."kanaseimei_1hit.csv", $body, FILE_APPEND);

//var_dump($aryRet);
				//差分のチェック
				$change_flg = false;
				$change = "";
				//if($aryRet[0]['kanjisei'].$aryRet[0]['kanjimei'] != str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])))
				//１．漢字姓名
				if($kanjiname_db != $kanjiname_csv)
				{
					$change_flg = true;
					$change .= "cnameknj";
				}
				//else if($aryRet[0]['kanasei'].$aryRet[0]['kanamei'] != str_replace("　", "", str_replace(" ", "", $vals['cnamekna'])))
				//２．カナ姓名
				if($kananame_db != $kananame_csv)
				{
					$change_flg = true;
					$change .= "cnamekna";
				}
				//３．性別
				if($aryRet[0]['sex'] == "0")
				{
					if($vals['seibetu_kbn'] != "1")
					{
						$change_flg = true;
						$change .= "seibetu_kbn";
					}
				}
				if($aryRet[0]['sex'] == "1")
				{
					if($vals['seibetu_kbn'] != "2")
					{
						$change_flg = true;
						$change .= "seibetu_kbn";
					}
				}
				
				//４．生年月日
				if($aryRet[0]['dbirth_dte'] != $vals['dbirth_dte'])
				{
					$change_flg = true;
					$change .= "dbirth_dte";
				}
				
				//５．掛講座
				if($aryRet[0]['belong_code'] != $vals['kkrkoza_cde'])
				{
					$change_flg = true;
					$change .= "kkrkoza_cde";
				}
				
				//６．定員区分
				if($aryRet[0]['joukin_kbn'] == "0")
				{
					if($vals['cteiinkb'] != "1")
					{
						$change_flg = true;
						$change .= "cteiinkb";
					}
					else
					{
						//定員区分が変更無し（定員）
						if($aryRet[0]['post_code'] != $vals['syksy_cde'])
						{
							$change_flg = true;
							$change .= "syksy_cde";
						}
						if($aryRet[0]['job_code'] != $vals['knmei_cde'])
						{
							$change_flg = true;
							$change .= "knmei_cde";
						}
					}
				}
				if($aryRet[0]['joukin_kbn'] == "1")
				{
					if($vals['cteiinkb'] != "2")
					{
						$change_flg = true;
						$change .= "cteiinkb";
					}
					else
					{
						//定員区分が変更無し（非常勤）
						if($aryRet[0]['post_code'] != $vals['hjksyk_misy_cde'])
						{
							$change_flg = true;
							$change .= "syksy_cde";
						}
						if($aryRet[0]['job_code'] != $vals['hjksyk_skin_cde'])
						{
							$change_flg = true;
							$change .= "knmei_cde";
						}
					}
				}
				
				//echo "change_flg:".$change_flg.PHP_EOL;

				//差分がある場合は、更新
				if($change_flg)
				{
				//１件一致した場合
					$message = "旧姓漢字姓+カナ名１件一致更新有り："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					//file_put_contents("/var/www/phplib/import/"."sabun_ari.csv", "④".$change.$body, FILE_APPEND);
				}
				//差分がない場合は、更新しない
				else
				{
					$message = "旧姓漢字姓+カナ名１件一致更新無し："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
					//echo $message.PHP_EOL;
					//file_put_contents("/var/www/phplib/import/"."sabun_nashi.csv", "④".$body, FILE_APPEND);
				}

				continue;
			}
			else
			{

				// 新規登録
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

//コメントアウト
//				$ret = $oMgr->oDb->query($sql);
//				if (!$ret)
//				{
//					echo "中継テーブル登録に失敗しました。\n";
//					continue;
//				}

		// 共通WHERE句
		$where = <<< SQL
WHERE
  cshainno = $sqlCshainno;
SQL;

			// 連携項目を編集して取得
			$sql = <<< SQL
SELECT
user_id,
cshainno as staff_id,
cshainno as login_id,
case
when cnameknj != '' and cnameknj is not null then
(select substr(cnameknj, 0, position('　' in cnameknj)))
else
(select substr(cnameknj, 0, position('　' in cnameknj)))
end as kanjisei,
case
when cnameknj != '' and cnameknj is not null then
(select substr(cnameknj, position('　' in cnameknj)+1))
else
(select substr(cnameknj, position('　' in cnameknj)+1))
end as kanjimei,
case
when cnamekna != '' and cnamekna is not null then
(select substr(cnamekna, 0, position('　' in cnamekna)))
else
(select substr(cnamekna, 0, position('　' in cnamekna)))
end as kanasei,
case
when cnamekna != '' and cnamekna is not null then
(select substr(cnamekna, position('　' in cnamekna)+1))
else
(select substr(cnamekna, position('　' in cnamekna)+1))
end as kanamei,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(kyu_kj_nme, 0, position('　' in kyu_kj_nme)))
else '' end as kanjisei_real,
case
when kyu_kj_nme != '' and kyu_kj_nme is not null then
(select substr(kyu_kj_nme, position('　' in kyu_kj_nme)+1))
else '' end as kanjimei_real,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(kyu_kn_nme, 0, position('　' in kyu_kn_nme)))
else '' end as kanasei_real,
case
when kyu_kn_nme != '' and kyu_kn_nme is not null then
(select substr(kyu_kn_nme, position('　' in kyu_kn_nme)+1))
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

			// 新規
//コメントアウト
//			$ret = $oMgr->insertUserData(&$request);
//			if (!$ret)
//			{
//				echo $err_msg."利用者情報新規登録に失敗しました。";
//				continue;
//			}

			// 中間テーブルのユーザIDを更新
			$sql = "update idodata set user_id = " . $request['user_id'] . $where;
//コメントアウト
//			$ret = $oMgr->oDb->query($sql);
//			if (!$ret)
//			{
//
//				echo "中継テーブルの更新に失敗しました。\n";
//				continue;
//			}

			//職員番号でも氏名でも一致しなかった場合は、新規登録
			$message = "新規登録："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "、カナ：" . $vals['cnamekna'] . "、生年月日：" . $vals['dbirth_dte'] . "、性別：" . $vals['seibetu_nme'] . "]";
			//echo $message.PHP_EOL;
			//file_put_contents("/var/www/phplib/import/"."sinki_toroku.csv", $body, FILE_APPEND);

			}
		}
	}
}




	echo "処理が正常に終了しました。".PHP_EOL;

exit;


?>
