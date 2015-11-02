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

$dir = "import/";
$ido_file = 'idodata_update.csv';

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

		if ($body == "")
		{
			// 空行
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
		
		

		$cnameknjArray = explode("　", $vals['cnameknj']);
		$cnameknaArray = explode("　", $vals['cnamekna']);
		$sqlKanjisei = $oMgr->sqlItemChar($cnameknjArray[0]);
		$sqlKanjimei = $oMgr->sqlItemChar($cnameknjArray[1]);
		$sqlKanasei = $oMgr->sqlItemChar($cnameknaArray[0]);
		$sqlKanamei = $oMgr->sqlItemChar($cnameknaArray[1]);
		if($vals['seibetu_kbn'] == 1)
		{
			$sqlSeibetuKbn = $oMgr->sqlItemChar("0");
		}
		else
		{
			$sqlSeibetuKbn = $oMgr->sqlItemChar("1");
		}
		
		$sqlDbirthDte = $oMgr->sqlItemChar($vals['dbirth_dte']);
		
		$sqlBelongChgId = $oMgr->sqlItemChar($vals['kkrkoza_cde']);
		if($vals['kkrkoza_cde'] == "")
		{
			$sqlBelongChgId = $oMgr->sqlItemChar($vals['szk_cde']);
		}

		if($joukin_kbn == JOUKIN_KBN_FULLTIME)
		{
			$sqlPostId = $oMgr->sqlItemChar($vals['syksy_cde']);
			$sqlJobId = $oMgr->sqlItemChar($vals['knmei_cde']);
		}
		else
		{
			$sqlPostId = $oMgr->sqlItemChar($vals['hjksyk_misy_cde']);
			$sqlJobId = $oMgr->sqlItemChar($vals['hjksyk_skin_cde']);
		}
		$sqlJokinKbn = $oMgr->sqlItemChar($joukin_kbn);
		$sqlCshainno = $oMgr->sqlItemChar($vals['cshainno']);


		// 更新
		$sql = "UPDATE user_mst SET ";
		$sql .= "login_id = ".$sqlCshainno.", ";
		$sql .= "kanjisei = ".$sqlKanjisei.", ";
		$sql .= "kanjimei = ".$sqlKanjimei.", ";
		$sql .= "kanasei = ".$sqlKanasei.", ";
		$sql .= "kanamei = ".$sqlKanamei.", ";
		$sql .= "sex = ".$sqlSeibetuKbn.", ";
		$sql .= "birthday = ".$sqlDbirthDte.", ";
		$sql .= "belong_chg_id = (select belong_chg_id from belong_chg_mst where belong_chg_code = ".$sqlBelongChgId." ), ";
		$sql .= "post_id = (select post_id from post_mst where post_code = ".$sqlPostId." ), ";
		$sql .= "job_id = (select job_id from job_mst where job_code = ".$sqlJobId." ), ";
		$sql .= "joukin_kbn = ".$sqlJokinKbn.", ";
		$sql .= "update_time = now() ";
		$sql .= "WHERE staff_id = ".$sqlCshainno."";

//print_r($sql.PHP_EOL);
		$ret = $oMgr->oDb->query($sql);

		if (!$ret)
		{
			echo "ユーザテーブル更新に失敗しました。\n";
			print_r($sql.PHP_EOL);
			continue;
		}

	}
}

	echo "処理が正常に終了しました。".PHP_EOL;

exit;


?>
