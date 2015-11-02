<?php
set_include_path('/usr/share/pear:/var/www/phplib');

require_once("lib/base.class.php");
require_once("mgr/common_mgr.class.php");
require_once("mgr/vpns_members_regist_common_mgr.class.php");
require_once("sql/vpns_members_edit_sql.inc.php");

	function checkAdLoginAuth($user_id, $login_passwd)
	{
		//接続開始
		$ldap_conn = ldap_connect(LDAP_HOST_1, LDAP_PORT);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
		}
		else
		{
			print_r("OK".PHP_EOL);
		}
		if (!$ldap_conn)
		{
			Debug_Trace("接続失敗");
			return false;
		}
		
		if($ldap_conn)
		{
			ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
			$ldap_bind = ldap_bind($ldap_conn, "CN=".$user_id . "," . LDAP_DN, $login_passwd);
			if($ldap_bind)
			{
				Debug_Trace("ADの認証に成功しました", 3);
				return true;
			}
			else
			{
				Debug_Trace("ADの認証に失敗しました", 3);
				Debug_Trace($user_id, 3);
				return false;
			}
		}
		else
		{
			Debug_Trace( 'ADサーバへの接続に失敗しました');
			return false;
		}

		ldap_close($ldap_conn);
		
		return true;
	}

print_r("check:".checkAdLoginAuth("aaa.aaa.qc","Test123!").PHP_EOL);
?>
