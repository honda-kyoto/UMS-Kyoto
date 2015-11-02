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

			$sql = "select * from user_mst " . $where;


			$oMgr->oDb->begin();
			$aryRet = $oMgr->oDb->getAll($sql);

			// 職員番号で検索結果
			if (is_array($aryRet) && count($aryRet) >= 2)
			{

			}

				$sql = "INSERT INTO job_mst (job_name,disp_num,job_code) VALUES('名前',1,'コード')";
				$ret = $oMgr->oDb->query($sql);

				if (!$ret)
				{
					echo $err_msg."中継テーブル登録に失敗しました。\n";

					var_dump($ret);
					$oMgr->oDb->rollback();
				}

				$sql = "INSERT INTO job_mst (job_id,job_name,disp_num,job_code) VALUES(12, '名前',1,'コード')";
				$ret = $oMgr->oDb->query($sql);
				
			// 職員番号で一致しなかった場合、氏名でチェック
			$sql = "select * from user_mst " . $where2;

			$aryRet = $oMgr->oDb->getAll($sql);
			
			$oMgr->oDb->end();

		}
	}
}




	echo "処理が正常に終了しました。".PHP_EOL;

exit;


?>
