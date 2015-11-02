<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

$oMgr = new common_mgr();

$cnt = $argv[1];

$csv = "";
for ($i = 0 ; $i < $cnt ; $i++)
{
	$src_pwd = $oMgr->createPassword();

	$pwd = $oMgr->passwordEncrypt($src_pwd);

	$csv .= $i . "," . $pwd . "," . $src_pwd . "\n";
}

if ($csv != "")
{
	file_put_contents($dir."/passwd.csv", $csv);
}

exit;


?>
