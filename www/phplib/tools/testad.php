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

		// バインド
		$ldap_bind  = ldap_bind($ldap_conn, "CN=ncvcadmin,CN=Users,DC=y-ncvc,DC=local", "Passw0rd");

		if (!$ldap_bind)
		{
			print_r("バインド失敗");
			return;
		}
        $ldap_search = ldap_search($ldap_conn, "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local","cn=*",array( "*") ,0,-1);
        $get_entries = ldap_get_entries($ldap_conn,$ldap_search);


        //$ldap_at = ldap_first_entry($ldap_conn, $ldap_search);
        //$ldap = ldap_get_attributes($ldap_conn, $ldap_at);
        //var_dump($get_entries); 
        
         //エントリ情報出力
//        print_r($get_entries['count']); 
//        print_r('|||||||||'.PHP_EOL); 
                //print_r($get_entries[1010]["mail"][0].PHP_EOL); 
                //print_r($get_entries[1010]["cn"][0].PHP_EOL); 
                //print_r($get_entries[1010]["dn"][0].PHP_EOL); 
        //print_r($get_entries[0]["cn"][0]); 
//        foreach( $get_entries as $key=>$val ){
//			print_r($get_entries[$key]["cn"][0] .PHP_EOL) ;
//	}

		for ($i = 0 ; $i < $get_entries['count'] ; $i++)
		{
			print_r($get_entries[$i]["cn"][0].PHP_EOL);
		}


        
/*
		$vpn_user_id = 'abe.tadaaki.hp';
		$last_name = '忠明';
		$first_name = '阿部';

		$add["cn"] = 'shibuta';
		$add["objectClass"] = "user";
		$add["sAMAccountName"] = 'shibuta';
		$add["userPrincipalName"] = 'shibuta@dan-tec.co.jp';
		$add['unicodePwd'] = mb_convert_encoding("password", "UTF-16LE");
		$add["UserAccountControl"] = "66048";


		// 更新時のパラメータ
//		$userDn = "CN=".$vpn_user_id.",".LOGINID_DN;

		// 更新
//		if (ldap_modify($ldap_conn,"CN=0001Naika,OU=SmicUser,OU=SMIC,DC=y-ncvc,DC=local",$mod))
		if (ldap_add($ldap_conn,"CN=shibuta,OU=SmicUser,OU=SMIC,DC=y-ncvc,DC=local",$add))

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
//			print_r($userDn);
//			print_r($add);
			var_dump($ldap_conn);
			return;
		}
*/
		ldap_close($ldap_conn);

exit;


?>
