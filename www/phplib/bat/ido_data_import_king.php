<?php
/**********************************************************
* File         : ido_data_import_king.php
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
$ido_file = 'king.csv';

$fields_value = array(
'cshainno' => '職員ID',
'ckaishibi' => '有効開始日',
'csyuryou' => '有効終了日',
'cactive' => 'アクティブフラグ',
'cnameknj' => '職員名',
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
			$vals[$fields[$key]] = $val;

		}
//echo $vals['cshainno'];///////////////////////////////////////////////////test////////////////
//echo $vals['cnameknj'];///////////////////////////////////////////////////test////////////////
		$sqlCshainno = $oMgr->sqlItemChar($vals['cshainno']);
		$sqlCnameknj = $oMgr->sqlItemChar($vals['cnameknj']);

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
  staff_id = $sqlCshainno;
SQL;

		//
		// 既存か新規かチェック
		//
		if ($vals['cnameknj'] != "" || $vals['cshainno'] != "")
		{

			$err_msg = "[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "]";
//echo $err_msg;
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
				$message = "職員番号複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "]";
				//echo $message.PHP_EOL;
				file_put_contents("/var/www/phplib/import/kingcsv/"."shokuinNo_2over_king.csv", $body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				//１件一致した場合
				$message = "職員番号１件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "]";
				//echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/kingcsv/"."shokuinNo_1hit_king.csv", $body, FILE_APPEND);
				
				//差分のチェック
				$change_flg = false;
				if($aryRet[0]['kanjisei'].$aryRet[0]['kanjimei'] != str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])))
				{
					$change_flg = true;
				}
				else if($aryRet[0]['kanasei'].$aryRet[0]['kanamei'] != str_replace("　", "", str_replace(" ", "", $vals['cnamekna'])))
				{
					$change_flg = true;
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
				$message = "氏名複数一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "]";
				//echo $message.PHP_EOL;
				file_put_contents("/var/www/phplib/import/kingcsv/"."simei_2over_king.csv", $body, FILE_APPEND);
				continue;
			}
			else if (is_array($aryRet) && count($aryRet) == 1)
			{
				$user_id = $aryRet['user_id'];
				//氏名で一件一致した場合
				$message = "氏名一件一致："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "]";
				echo $message.PHP_EOL;
				//file_put_contents("/var/www/phplib/import/kingcsv/"."simei_1hit_king.csv", $body, FILE_APPEND);

//var_dump($aryRet);
				//差分のチェック
				$change_flg = false;
				if($aryRet[0]['kanjisei'].$aryRet[0]['kanjimei'] != str_replace("　", "", str_replace(" ", "", $vals['cnameknj'])))
				{
					$change_flg = true;
				}

			
				//echo "change_flg:".$change_flg.PHP_EOL;

				//差分がある場合は、更新
				if($change_flg)
				{
				//１件一致した場合
					$message = "氏名１件一致更新有り："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/kingcsv/"."simei_kousin_ari_king.csv", $body, FILE_APPEND);
				}
				//差分がない場合は、更新しない
				else
				{
					$message = "氏名１件一致更新無し："."[職員番号：" . $vals['cshainno'] . "、氏名：" . $vals['cnameknj'] . "]";
					//echo $message.PHP_EOL;
					file_put_contents("/var/www/phplib/import/kingcsv/"."simei_1hit_kousin_nasi_king.csv", $body, FILE_APPEND);
				}

			}
			else
			{
//echo test;
			}
		}
	}
}




	echo "処理が正常に終了しました。".PHP_EOL;

exit;


?>
