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

//最新ファイルからデータ取得
$dir = "import/";
$ido_file = 'idodata.csv';
$old_file = 'idodata_old.csv';

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

$oMgr = new users_detail_mgr();

// 最新確定データを取得
//
// ファイルをチェック
//
if ($data = file_get_contents($dir.$ido_file, FILE_USE_INCLUDE_PATH))
{

	$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

	$aryData = explode("\n", $data);

	$vals = array();

	$cnt = 0;
	foreach ($aryData AS $body)
	{
		$cnt++;
		if ($cnt == 1 || $body == "")
		{
			// 1行目はタイトル
			continue;
		}

		$aryBody = explode(",", $body);

		$user_exists = false;


		$val = trim($aryBody[2]);
		$val = trim($val,"\x22");
		//$vals[] = $val;
		//echo $val."\n";
		
		$sql = "select * from idodata where cshainno = '$val'";
		$ret = $oMgr->oDb->getAll($sql);
		
		if(is_array($ret) && count($ret) > 0)
		{
			$body = str_replace("\n", "\r\n", $body);
			$body = mb_convert_encoding( $body, "sjis-win", "UTF-8");
			file_put_contents("/var/www/phplib/import/"."update.csv", $body."\r\n", FILE_APPEND);
		}
		else
		{
			$body = str_replace("\n", "\r\n", $body);
			$body = mb_convert_encoding( $body, "sjis-win", "UTF-8");
			file_put_contents("/var/www/phplib/import/"."shinki.csv", $body."\r\n", FILE_APPEND);
		}
		
	}

}
else
{
	echo "no file $ido_file\n";
	exit;
}

echo "処理が正常に終了しました。\n";

exit;


?>
