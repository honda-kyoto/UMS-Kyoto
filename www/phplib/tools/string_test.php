<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$oMgr = new common_mgr();

$pass = $argv[1];


		$kanjiname = str_replace(" ", "　", "渋田 和芳");
print_r($kanjiname);
print_r(":");
		$kanjiname = string::han2zen($kanjiname);
print_r($kanjiname);
print_r(":");
		$strUser .= string::mb_str_pad($kanjiname, 20,"　");
print_r($strUser);
print_r(":");

		$passwd = trim($pass, "\x22");

		$passwd = $oMgr->passwordDecrypt($passwd);
		

exit;


?>
