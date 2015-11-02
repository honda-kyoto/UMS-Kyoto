<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");
require_once("mgr/vpns_members_regist_common_mgr.class.php");
require_once("sql/vpns_members_edit_sql.inc.php");
define ("LDAP_HOST_1", "10.254.225.155");
define ("LDAP_HOST_2", "10.254.225.155");
define ("LDAP_PORT", 389);
define ("LDAP_PORT_SSL", 636);
define ("LDAP_PASS", "Passw0rd");
define ("LDAP_DN", "CN=ncvcadmin,CN=Users,DC=y-ncvc,DC=local");

define("LOGINID_DN", "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local");
define("VLAN400_DN", "CN=vlan400,OU=VLAN,OU=NetAuth,DC=y-ncvc,DC=local");

define("VLAN_DN", "CN=vlan###VLAN_NAME###,OU=VLAN,OU=NetAuth,DC=y-ncvc,DC=local");

define("WIRED_DN", "OU=enable,OU=wired,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");
define("MAC_WIRED_DN", "CN=mac_wired,OU=wired,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");

define("WLESS_DN", "OU=enable,OU=wireless,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");
define("MAC_WLESS_DN", "CN=mac_wireless,OU=wireless,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");

define("WLESS_ID_DN", "OU=enable,OU=WirelessID,OU=NetAuth,DC=y-ncvc,DC=local");
define("VLAN300_DN", "CN=vlan300,OU=VLAN,OU=NetAuth,DC=y-ncvc,DC=local");

define ("VPN_DN", "OU=VPN,OU=NetAuth,DC=y-ncvc,DC=local");
define ("VPN_GROUP_DN", "CN=###GROUP_NAME###,OU=VPN,OU=NetAuth,DC=y-ncvc,DC=local");

define ("PASSLOGIC_DN", "OU=passlogic,OU=NetAuth,DC=y-ncvc,DC=local");

define ("GUEST_DN", "CN=Wireless_Guest_Access,OU=WirelessID,OU=NetAuth,DC=y-ncvc,DC=local");
		print_r('start');
		if (!defined("LDAP_HOST_1"))
		{
		print_r('end');
			return;
		}

		//接続開始
		$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_1);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect("ldaps://".LDAP_HOST_2);
		}

		if (!$ldap_conn)
		{
				print_r('end');
			Debug_Trace("接続失敗", 9);
			return;
		}

		// バインド
		$ldap_bind  = ldap_bind($ldap_conn, "CN=ncvcadmin,CN=Users,DC=y-ncvc,DC=local", "Passw0rd");

		if (!$ldap_bind)
		{
						print_r('end');
			Debug_Trace("バインド失敗", 9);
			return;
		}
        $ldap_search = ldap_search($ldap_conn, "CN=tsukada.masanori.hp,OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local","cn=*");
        $get_entries = ldap_get_entries($ldap_conn,$ldap_search);
        
         //エントリ情報出力
        print_r($get_entries); 
        
        
        
		$vpn_user_id = 'abe.tadaaki.hp';
		$last_name = '忠明';
		$first_name = '阿部';

$mod['givenname'] =  mb_convert_encoding('1', "sjis-win", "auto");
$mod['sn'] =  mb_convert_encoding('2', "sjis-win", "auto");

$mod['displayname'] =  mb_convert_encoding('6', "sjis-win", "auto");

		// 更新時のパラメータ
		$userDn = "CN=".$vpn_user_id.",".LOGINID_DN;

		// 更新
		if (ldap_modify($ldap_conn,"CN=0001Naika,OU=SmicUser,OU=SMIC,DC=y-ncvc,DC=local",$mod))

//		if (ldap_modify($ldap_conn,"CN=abe.tadaaki.hp,OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local",$mod))
		{
		print_r('更新は成功しました');
			Debug_Trace("更新は成功しました", 414);
		}
		else
		{
		$errno = ldap_errno($ldap_conn);
   $errmsg = ldap_error($ldap_conn);
			print_r('|');
			print_r($errno);
			print_r('|');
			print_r($errmsg);
						print_r('|');
			print_r($userDn);
			print_r($mod);
			var_dump($ldap_conn);
			return;
		}

		ldap_close($ldap_conn);

exit;


?>
