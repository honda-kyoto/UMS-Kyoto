<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

$oMgr = new common_mgr();

$file = $argv[1];

$buff = file_get_contents($dir."/".$file);

$aryLines = explode("\n", $buff);

$csv = "";
if (is_array($aryLines))
{
	foreach ($aryLines AS $line)
	{
		$line = trim($line);
		list($login_id, $passwd) = explode(",", $line);
		//$login_id = trim($login_id, "\x22");
		$passwd = trim($passwd, "\x22");

		$passwd = $oMgr->passwordDecrypt($passwd);
		
		$csv .= $login_id . ',"' . $passwd . '"' . "\r\n";
	}
}

if ($csv != "")
{
	file_put_contents($dir."/result_".$file, $csv);
}

exit;


?>
