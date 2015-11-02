<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

require_once($dir."/define.inc.php");

$oMgr = new common_mgr();

$sql = "select parent_id, current_id, belong_name from garoon_belong_view order by current_id";

$aryRet = $oMgr->oDb->getAll($sql);

$csv = '"現組織コード","組織名","現組織コード","親組織コード","メモ"';
$csv .= "\n";
$csv .= '"1000","NCVC","1000","",""';
$csv .= "\n";
if (is_array($aryRet))
{
	foreach ($aryRet AS $data)
	{
		if ($data['parent_id'] == "")
		{
			$data['parent_id'] = "1000";
		}

		$csv .= '"' . $data['current_id'] . '"';		// 現組織コード
		$csv .= ',"' . $data['belong_name'] . '"';		// 組織名
		$csv .= ',"' . $data['current_id'] . '"';		// 現組織コード
		$csv .= ',"' . $data['parent_id'] . '"';		// 親組織コード
		$csv .= ',""';									// メモ
		$csv .= "\n";
	}
}

if ($csv != "")
{
	$csv = mb_convert_encoding($csv, "sjis-win", "UTF-8");
	file_put_contents(GAROON_OUTPUT_DIR."ounit.csv", $csv);
}

exit;


?>
