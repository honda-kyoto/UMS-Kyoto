<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

//define ("AD_HOST_1", "10.254.225.155");
//define ("AD_HOST_2", "10.254.225.155");
define ("AD_HOST_1", "10.1.2.10");
define ("AD_HOST_2", "10.1.2.11");
define ("AD_PORT", 389);
define ("AD_PORT_SSL", 636);
//define ("AD_PASS", "Passw0rd");
//define ("AD_DN", "CN=ncvcadmin,CN=Users,DC=y-ncvc,DC=local");
//define ("LOGINID_DN", "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local");
define ("AD_PASS", "117#ncvc");
define ("AD_DN", "CN=ncvcadmin,CN=Users,DC=ncvc,DC=local");
define ("AD_LOGINID_DN", "OU=enable,OU=ncvc,DC=ncvc,DC=local");


$oMgr = new common_mgr();

//$sql = "select UM.login_id,UM.login_passwd,UM.kanjisei,UM.kanjimei,UM.kanjisei || ' ' || UM.kanjimei as kanjiname,UM.kanasei,UM.kanamei,UM.kanasei || ' ' || UM.kanamei as kananame from user_mst as UM where UM.login_id is not null and UM.login_id != '' and UM.belong_chg_id not in (408, 409) order by UM.login_id";

$sql = "select UM.login_id,UM.login_passwd,UM.kanjisei,UM.kanjimei,UM.kanjisei || ' ' || UM.kanjimei as kanjiname,UM.kanasei,UM.kanamei,UM.kanasei || ' ' || UM.kanamei as kananame ,UM.naisen,UM.mail_acc,UM.pbno,UM.belong_chg_id,UM.post_id,UM.kanasei,UM.kanamei from user_mst as UM where UM.login_id is not null and UM.login_id != '' and UM.login_id = 'zheng.can.ri' order by UM.login_id";

$aryRet = $oMgr->oDb->getAll($sql);
print_r($aryRet);
if (is_array($aryRet))
{

	if (!defined("AD_HOST_1"))
	{
		print_r("error");
		return;
	}

	//接続開始
	$ldap_conn = ldap_connect(AD_HOST_1,"389");
	if (!$ldap_conn)
	{
		$ldap_conn = ldap_connect("ldaps://".AD_HOST_2);
	}

	if (!$ldap_conn)
	{
		print_r("接続失敗");
		return;
	}

	// バインド
	$ldap_bind  = ldap_bind($ldap_conn, AD_DN, AD_PASS);

	if (!$ldap_bind)
	{
		print_r("バインド失敗");
		return;
	}

	foreach ($aryRet AS $data)
	{
		$passwd = $oMgr->passwordDecrypt($data['login_passwd']);
		$login_id = $data['login_id'];		// ログイン名
		$kanjiname = $data['kanjiname'];	// 名前
		$kanjisei = $data['kanjisei'];	// 姓
		$kanjimei = $data['kanjimei'];	// 名
		$naisen = $data['naisen'];	// 内線
		$mail_acc = $data['mail_acc'];	// メールアカウント
		$pbno = $data['pbno'];	// PHS
		$belong_chg_id = $data['belong_chg_id'];	// 所属
		$post_id = $data['post_id'];	// 役職
		$kanasei = $data['kanasei'];	// カナ性
		$kanamei = $data['kanamei'];	// カナ名
		$kananame = $data['kananame'];	// カナ
		
		print_r($login_id . '|' . $passwd . '|' . $kanjisei .'|' . $kanjimei . '|' . $kanjiname .'|' . PHP_EOL);

	    $ldap_search = ldap_search($ldap_conn, AD_LOGINID_DN, "cn=" . $login_id );
		$get_entries = ldap_get_entries($ldap_conn,$ldap_search);
    	print_r($get_entries['count'].PHP_EOL); 
    	if($get_entries['count'] > 0)
    	{
    		$mod["sn"] = mb_convert_encoding($kanjisei, "sjis-win", "auto");
			$mod["givenname"] = mb_convert_encoding($kanjimei, "sjis-win", "auto");
			$mod["displayname"] = mb_convert_encoding($kanjiname, "sjis-win", "auto");
			$mod["telephoneNumber"] = mb_convert_encoding($naisen, "sjis-win", "auto");
			$mod["mail"] = mb_convert_encoding($mail_acc, "sjis-win", "auto");
//空の場合はエラーとなる
//			$mod["mobile"] = mb_convert_encoding($pbno, "sjis-win", "auto");
			$mod["department"] = mb_convert_encoding($belong_chg_id, "sjis-win", "auto");
			$mod["title"] = mb_convert_encoding($post_id, "sjis-win", "auto");
			$mod["msDS-PhoneticLastName"] = mb_convert_encoding($kanasei, "sjis-win", "auto");
			$mod["msDS-PhoneticFirstName"] = mb_convert_encoding($kanamei, "sjis-win", "auto");
			$mod["msDS-PhoneticDisplayName"] = mb_convert_encoding($kananame, "sjis-win", "auto");
    		
    		$userDn = "CN=".$login_id.",".AD_LOGINID_DN;
    		if (ldap_modify($ldap_conn,$userDn,$mod))
			{
				print_r($userDn . PHP_EOL);
				print_r($login_id . '|' . $passwd . '|' . $kanjisei .'|' . $kanjimei . '|' . $kanjiname .'|' . PHP_EOL);
				print_r('更新は成功しました');
			}
			else
			{
				print_r("更新は失敗しました。");
			}
			
    	}

	}
	ldap_close($ldap_conn);
}

exit;


?>
