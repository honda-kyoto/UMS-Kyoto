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
$ido_file_old = 'idodata_old.csv';


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
		$is_diff = true;

		$cnt++;
		if ($cnt == 1)
		{
			// 1行目はタイトル
			continue;
		}

		if ($data_old = file_get_contents($dir.$ido_file_old, FILE_USE_INCLUDE_PATH))
		{
			$data_old = mb_convert_encoding($data_old, "UTF-8", "SJIS, sjis-win");

			$aryData_old = explode("\n", $data_old);

			$cnt = 0;
			foreach ($aryData_old AS $body_old)
			{
				$cnt++;
				if ($cnt == 1)
				{
					// 1行目はタイトル
					continue;
				}

				$aryBody = explode(",", $body);
				$aryBody_old = explode(",", $body_old);
				
				$same_cnt = 0;
				$vals = array();
				foreach ($aryBody AS $key => $val)
				{
					if ($fields[$key] == "dhtreingb_dte" || $fields[$key] == "dsaiyo_dte" || $fields[$key] == "dninyo_dte" || $fields[$key] == "dnnki_mr_dte" || 
						$fields[$key] == "djosin_prt_dte" || $fields[$key] == "djirei_prt_dte" || $fields[$key] == "getuji_flg")
					{
						
					}
					else
					{
						if($aryBody_old[$fields[$key]] == $val)
						{
							$same_cn++;
						}
					}
//					if($body == $body_old)
//					{
//						$is_diff = false;
//						continue;
//					}
					if($same_cn == 28)
					{
						$is_diff = false;
						continue;
					}
				}
			}
		}

		if($is_diff)
		{
//			print_r($body);
			file_put_contents("/var/www/phplib/import/"."idodata_sabun.csv", $body, FILE_APPEND);
		}

	}
}



	echo "処理が正常に終了しました。\n";

exit;


?>
