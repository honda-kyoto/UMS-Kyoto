<?php
set_include_path('/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");
// AD関連
define ("LDAP_HOST_1", "10.255.15.30");
define ("LDAP_PORT", 389);
define ("LDAP_PORT_SSL", 636);
//define ("LDAP_DN", "CN=SG_SBC_USERS,OU=SBC_USER,OU=SBC_KING5,DC=kuhp.net");
define ("LDAP_DN", "OU=SBC_USER,OU=SBC_KING5,DC=kuhp,DC=net");
	function checkAdLoginAuth($user_id, $login_passwd)
	{
	
		//接続開始
		$ldap_conn = ldap_connect(LDAP_HOST_1, LDAP_PORT);
		if (!$ldap_conn)
		{
			print_r("ldap_conn_NG".PHP_EOL);
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
		}
		else
		{
			print_r("ldap_conn_OK".PHP_EOL);
		}
		if (!$ldap_conn)
		{
			print_r("接続失敗".PHP_EOL);
			return false;
		}
		else
		{
			print_r("接続成功".PHP_EOL);
		}

		if($ldap_conn)
		{
			ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);
			$ldap_bind = ldap_bind($ldap_conn, "CN=".$user_id . "," . LDAP_DN, $login_passwd);
			if($ldap_bind)
			{
				print_r("ADの認証に成功しました".PHP_EOL);
				return true;
			}
			else
			{
				print_r("ADの認証に失敗しました".PHP_EOL);
				var_dump($ldap_bind);
				print_r($user_id);

				return false;
			}
		}
		else
		{
			print_r( "ADサーバへの接続に失敗しました".PHP_EOL);
			return false;
		}

		ldap_close($ldap_conn);
		
		return true;
	}
print_r("start".PHP_EOL);
checkAdLoginAuth("SY004039","hhonda");
print_r("end".PHP_EOL);
?>
