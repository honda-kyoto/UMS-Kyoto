<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$oMgr = new common_mgr();

$pass = $argv[1];


		$passwd = trim($pass, "\x22");

		$passwd = $oMgr->passwordDecrypt($passwd);
		
echo $passwd;
exit;


?>
