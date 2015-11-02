<?php
set_include_path('/usr/share/pear:/var/www/phplib');

//require_once("mgr/common_mgr.class.php");
//require_once("mgr/vpns_members_regist_common_mgr.class.php");
//require_once("sql/vpns_members_edit_sql.inc.php");

define ("LDAP_HOST_1", "10.254.225.155");
define ("LDAP_HOST_2", "10.254.225.155");
define ("LDAP_PORT", 389);
define ("LDAP_PORT_SSL", 636);
define ("LDAP_PASS", "Passw0rd");
define ("LDAP_DN", "CN=ncvcadmin,CN=Users,DC=y-ncvc,DC=local");

define("LOGINID_DN", "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local");

print_r('start'.PHP_EOL);
		if (!defined("LDAP_HOST_1"))
		{
		print_r('end');
			return;
		}

		//接続開始
//		$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
		$ldap_conn = ldap_connect(LDAP_HOST_1,"389");
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
		}

		if (!$ldap_conn)
		{
			print_r("接続失敗");
			return;
		}

define( "AD_LOGINAUTH_DN",  'OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local' );
$login_id = "aaa.aaa.qc";
$password = "Test123!";

		if($ldap_conn)
		{
			ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
			$ldap_bind = ldap_bind($ldap_conn, "CN=".$login_id . "," . AD_LOGINAUTH_DN, $password);
			if($ldap_bind)
			{
				print_r("OK".PHP_EOL);
				return true;
			}
			else
			{
				print_r( "NG".PHP_EOL);
				return false;
			}
		}
		else
		{
			print_r( 'ADサーバへの接続に失敗しました');
			return false;
		}

		ldap_close($ldap_conn);

exit;


?>
