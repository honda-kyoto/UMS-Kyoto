<?php

set_include_path('.:/usr/share/pear:/var/www/phplib');

//require_once("bat/daily_data_import.php");
require_once("mgr/common_mgr.class.php");

$dir = "/var/www/phplib/import/";

if($argv[1] == null || $argv[1] == "")
{
	print_r("no parameter\n");
	exit;

}
$ido_file = $argv[1];

if ($data = file_get_contents($dir.$ido_file, FILE_USE_INCLUDE_PATH))
{

	$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

	$aryData = explode("\n", $data);
	$cnt=0;

	$oMgr = new common_mgr();
	
	foreach ($aryData AS $body)
	{

		if($body=="" || $body=="\22")
		{
		}
		else
		{
			$aryBody = explode(",", $body);
			$belong_chg_code = trim($aryBody[1]);
			$belong_chg_name = trim($aryBody[2]);
			$belong_chg_code = trim($belong_chg_code,"\x22");
			$belong_chg_name = trim($belong_chg_name,"\x22");
			
			//print_r("belong_chg_code=".$belong_chg_code.",belong_chg_name=".$belong_chg_name."\n");
			$sql = "select * from belong_chg_mst where belong_chg_code = '" .$belong_chg_code ."' and belong_chg_name='" .$belong_chg_name ."'";
			//print_r($sql."\n");
			$aryRet = $oMgr->oDb->getAll($sql);
			if ($aryRet)
			{
				//print_r("OK"."\n");
			}
			else
			{
				print_r($aryBody[1].",".$aryBody[2]."\n");
				//INSERT INTO belong_chg_mst VALUES (nextval('belong_chg_id_seq'), 9, '放射線部画像診断部門', NULL, (SELECT COALESCE(MAX(disp_num),0) + 1 FROM belong_chg_mst ), '0', now(), NULL, now(), NULL, '080532');
			}
		}
		$cnt++;
	}
	
}


?>
