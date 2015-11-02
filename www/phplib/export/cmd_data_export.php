<?php
/**********************************************************
* File         : cmd_data_export.php
* Authors      : mie tsutsui
* Date         : 2013.04.11
* Last Update  : 2013.04.11
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/data_export_mgr.class.php");


$oMgr = new data_export_mgr();

$role_id = $argv[1];
$myDir = dirname(__FILE__);

list($mode, $title) = $oMgr->getTargetData($role_id);

if ($mode == "")
{
	echo "パラメータに不備があります";
	exit;
}

// ディレクトリの存在チェック
if (!file_exists($myDir."/".$mode))
{
	umask(0);
	mkdir($myDir."/".$mode, 0777);
}

$funcMode = ucfirst($mode);
$funcName = "output" . $funcMode . "Data";

$file = $oMgr->{$funcName}($mode);

$extension = pathinfo($file, PATHINFO_EXTENSION);

$file_path = EXPTEMP_PATH . $file;

$new_path = $myDir . "/" . $mode . "/" . $mode . "." . $extension;

rename($file_path, $new_path);

echo date("[Y-m-d H:i:s]") . "エクスポートが正常に完了しました\n";
exit;


?>
