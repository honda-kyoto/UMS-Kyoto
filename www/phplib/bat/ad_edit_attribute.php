<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

define ("AD_HOST_1", "10.254.225.155");
define ("AD_HOST_2", "10.254.225.155");
//define ("AD_HOST_1", "10.1.2.10");
//define ("AD_HOST_2", "10.1.2.11");
define ("AD_PORT", 389);
define ("AD_PORT_SSL", 636);
define ("AD_PASS", "Passw0rd");
define ("AD_DN", "CN=Administrator,CN=Users,DC=y-ncvc,DC=local");
define ("AD_LOGINID_DN", "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local");
//define ("AD_PASS", "117#ncvc");
//define ("AD_DN", "CN=ncvcadmin,CN=Users,DC=ncvc,DC=local");
//define ("AD_LOGINID_DN", "OU=enable,OU=ncvc,DC=ncvc,DC=local");


$oMgr = new common_mgr();

//$sql = "select UM.login_id,UM.login_passwd,UM.kanjisei,UM.kanjimei,UM.kanjisei || ' ' || UM.kanjimei as kanjiname,UM.kanasei,UM.kanamei,UM.kanasei || ' ' || UM.kanamei as kananame from user_mst as UM where UM.login_id is not null and UM.login_id != '' and UM.belong_chg_id not in (408, 409) order by UM.login_id";

//$sql = "select UM.login_id,UM.login_passwd,UM.kanjisei,UM.kanjimei,UM.kanjisei || ' ' || UM.kanjimei as kanjiname,UM.kanasei,UM.kanamei,UM.kanasei || ' ' || UM.kanamei as kananame ,UM.naisen,UM.mail_acc,UM.pbno,UM.belong_chg_id,UM.post_id,UM.kanasei,UM.kanamei from user_mst as UM where UM.login_id is not null and UM.login_id != '' and UM.login_id = 'zheng.can.ri' order by UM.login_id";
//$sql = "select distinct UM.login_id,UM.kanjisei,UM.kanjimei,UM.kanjisei || ' ' || UM.kanjimei as kanjiname,UM.kanasei,UM.kanamei,UM.kanasei || ' ' || UM.kanamei as kananame,UM.login_passwd,UM.mail_acc || case when UM.mail_acc is not null then '@ncvc.go.jp' else '' end as mail_addr,PM.post_name || case when usp.sub_post_name is not null then ' / ' || usp.sub_post_name else '' end as post_name,JM.job_name || case when usj.sub_job_name is not null then ' / ' || usj.sub_job_name else '' end as job_name,UM.naisen,UM.pbno,bc.belong_class_name||bv.belong_div_name||bp.belong_dep_name||bs.belong_sec_name||bg.belong_chg_name || case when usc.sub_belong_name is not null then ' / ' || usc.sub_belong_name else '' end as belong_name from user_mst as UM ".
//	"left outer join post_mst as PM on UM.post_id = PM.post_id and PM.del_flg = '0' ".
//	"left outer join (select um.user_id,array_to_string(array(select pm.post_name from user_sub_post_tbl as usp, post_mst as pm where usp.post_id = pm.post_id and pm.del_flg = '0' and usp.del_flg = '0' and usp.user_id = um.user_id order by user_id ,list_no), ' / ') as sub_post_name from user_sub_post_tbl as um where um.post_id is not null) as usp on UM.user_id = usp.user_id ".
//	"left outer join (select um.user_id,array_to_string(array(select bc.belong_class_name|| bv.belong_div_name||bp.belong_dep_name||bs.belong_sec_name||bg.belong_chg_name as belong_name from user_sub_chg_tbl as usc ".
//	" left outer join belong_chg_mst bg ON usc.belong_chg_id = bg.belong_chg_id AND bg.del_flg::text = '0'::text left outer join belong_sec_mst bs ON bg.belong_sec_id = bs.belong_sec_id AND bs.del_flg::text = '0'::text ".
//	" left outer join belong_dep_mst bp ON bs.belong_dep_id = bp.belong_dep_id AND bp.del_flg::text = '0'::text left outer join belong_div_mst bv ON bp.belong_div_id = bv.belong_div_id AND bv.del_flg::text = '0'::text ".
//	" left outer join belong_class_mst bc ON bv.belong_class_id = bc.belong_class_id AND bc.del_flg::text = '0'::text where usc.del_flg = '0' and usc.user_id = um.user_id ".
//	" order by user_id , list_no), ' / ') as sub_belong_name from user_sub_chg_tbl as um where um.belong_chg_id is not null) as usc on UM.user_id = usc.user_id ".
//	"left outer join belong_chg_mst bg ON um.belong_chg_id = bg.belong_chg_id AND bg.del_flg::text = '0'::text ".
//	"left outer join belong_sec_mst bs ON bg.belong_sec_id = bs.belong_sec_id AND bs.del_flg::text = '0'::text ".
//	"left outer join belong_dep_mst bp ON bs.belong_dep_id = bp.belong_dep_id AND bp.del_flg::text = '0'::text ".
//	"left outer join belong_div_mst bv ON bp.belong_div_id = bv.belong_div_id AND bv.del_flg::text = '0'::text ".
//	"left outer join belong_class_mst bc ON bv.belong_class_id = bc.belong_class_id AND bc.del_flg::text = '0'::text ".
//	"left outer join job_mst as JM on UM.job_id = JM.job_id and JM.del_flg = '0' ".
//	"left outer join (select um.user_id, array_to_string(array(select jm.job_name from user_sub_job_tbl as usj, job_mst as jm where usj.job_id = jm.job_id and jm.del_flg = '0' and usj.del_flg = '0' and usj.user_id = um.user_id order by user_id, list_no), ' / ') as sub_job_name from user_sub_job_tbl as um where um.job_id is not null) as usj on UM.user_id = usj.user_id ".
//	"where UM.garoon_disused_flg != '1' and UM.login_id is not null order by UM.login_id";
//	"where UM.garoon_disused_flg != '1' and UM.login_id is not null and UM.login_id != '' and UM.start_date <= now()::date and COALESCE(UM.end_date, now()::date) >= now()::date and UM.belong_chg_id not in (408, 409) order by UM.login_id";
$sql = "select distinct UM.login_id,UM.kanjisei,UM.kanjimei,UM.kanjisei || ' ' || UM.kanjimei as kanjiname,UM.kanasei,UM.kanamei,UM.kanasei || ' ' || UM.kanamei as kananame,UM.login_passwd,UM.mail_acc || case when UM.mail_acc is not null then '@ncvc.go.jp' else '' end as mail_addr,PM.post_name,JM.job_name,UM.naisen,UM.pbno,bc.belong_class_name||bv.belong_div_name||bp.belong_dep_name||bs.belong_sec_name||bg.belong_chg_name as belong_name from user_mst as UM ".
	"left outer join post_mst as PM on UM.post_id = PM.post_id and PM.del_flg = '0' ".
	"left outer join belong_chg_mst bg ON um.belong_chg_id = bg.belong_chg_id AND bg.del_flg::text = '0'::text ".
	"left outer join belong_sec_mst bs ON bg.belong_sec_id = bs.belong_sec_id AND bs.del_flg::text = '0'::text ".
	"left outer join belong_dep_mst bp ON bs.belong_dep_id = bp.belong_dep_id AND bp.del_flg::text = '0'::text ".
	"left outer join belong_div_mst bv ON bp.belong_div_id = bv.belong_div_id AND bv.del_flg::text = '0'::text ".
	"left outer join belong_class_mst bc ON bv.belong_class_id = bc.belong_class_id AND bc.del_flg::text = '0'::text ".
	"left outer join job_mst as JM on UM.job_id = JM.job_id and JM.del_flg = '0' ".
	"where UM.garoon_disused_flg != '1' and UM.login_id is not null order by UM.login_id";


$aryRet = $oMgr->oDb->getAll($sql);
//print_r($aryRet);
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
		$kanjisei = $data['kanjisei'];	// ①姓
		$kanjimei = $data['kanjimei'];	// ②名
		$kanjiname = $data['kanjiname'];	// ③名前
		$job_name = $data['job_name'];	// ④職種
		$naisen = $data['naisen'];	// ⑤内線
		$mail_acc = $data['mail_addr'];	// ⑥メールアカウント
		$pbno = $data['pbno'];	// ⑦PHS
		$belong_name = mb_substr($data['belong_name'],0,64);	// ⑧所属
		$post_name = $data['post_name'];	// ⑨役職
		$kanasei = $data['kanasei'];	// ⑩カナ性
		$kanamei = $data['kanamei'];	// ⑪カナ名
		$kananame = $data['kananame'];	// ⑫カナ
//print_r(string::strlen($data['belong_name']));
		
		if($kanjisei=='')
		{
			$kanjisei = ' ';// ①姓
		}
		if($kanjimei=='')
		{
			$kanjimei = ' ';// ②名
		}
		if($kanjiname=='')
		{
			$kanjiname = ' ';// ③名前
		}
		if($job_name=='')
		{
			$job_name = ' ';// ④職種
		}
		if($naisen=='')
		{
			$naisen = ' ';// ⑤内線
		}
		if($mail_acc=='')
		{
			$mail_acc = ' ';// ⑥メールアカウント
		}
		if($pbno=='')
		{
			$pbno = ' ';// ⑦PHS
		}
		if($belong_name=='')
		{
			$belong_name = ' ';// ⑧所属
		}
		if($post_name=='')
		{
			$post_name = ' ';// ⑨役職
		}
		if($kanasei=='')
		{
			$kanasei = ' ';// ⑩カナ性
		}
		if($kanamei=='')
		{
			$kanamei = ' ';// ⑪カナ名
		}
		if($kananame=='')
		{
			$kananame = ' ';// ⑫カナ
		}
//		print_r($login_id . '|' . $passwd . '|' . $kanjisei .'|' . $kanjimei . '|' . $kanjiname .'|' . PHP_EOL);
//		print_r($job_name . '|' . $naisen . '|' . $mail_acc .'|' . $pbno . '|' . $belong_name .'|' . PHP_EOL);
//		print_r($post_name . '|' . $kanasei . '|' . $kanamei .'|' . $kananame . PHP_EOL);

	    $ldap_search = ldap_search($ldap_conn, AD_LOGINID_DN, "cn=" . $login_id );
		$get_entries = ldap_get_entries($ldap_conn,$ldap_search);
//    	print_r($get_entries['count'].PHP_EOL); 
    	if($get_entries['count'] > 0)
    	{
			// ①姓
			$mod["sn"] = mb_convert_encoding($kanjisei, "sjis-win", "auto");
			// ②名
			$mod["givenname"] = mb_convert_encoding($kanjimei, "sjis-win", "auto");
			// ③名前
			$mod["displayname"] = mb_convert_encoding($kanjiname, "sjis-win", "auto");
			// ④職種
			$mod["Description"] = mb_convert_encoding($job_name, "sjis-win", "auto");
			// ⑤内線
			$mod["telephoneNumber"] = mb_convert_encoding($naisen, "sjis-win", "auto");
			// ⑥メールアカウント
			$mod["mail"] = mb_convert_encoding($mail_acc, "sjis-win", "auto");
			// ⑦PHS
			$mod["mobile"] = mb_convert_encoding($pbno, "sjis-win", "auto");
			// ⑧所属
			$mod["department"] = mb_convert_encoding($belong_name, "sjis-win", "auto");
			// ⑨役職
			$mod["title"] = mb_convert_encoding($post_name, "sjis-win", "auto");
			// ⑩カナ性
			$mod["msDS-PhoneticLastName"] = mb_convert_encoding($kanasei, "sjis-win", "auto");
			// ⑪カナ名
			$mod["msDS-PhoneticFirstName"] = mb_convert_encoding($kanamei, "sjis-win", "auto");
			// ⑫カナ
			$mod["msDS-PhoneticDisplayName"] = mb_convert_encoding($kananame, "sjis-win", "auto");
    		
    		$userDn = "CN=".$login_id.",".AD_LOGINID_DN;
    		if (ldap_modify($ldap_conn,$userDn,$mod))
			{
//				print_r($userDn . PHP_EOL);
				print_r($login_id . '|' . $passwd . '|' . $kanjisei .'|' . $kanjimei . '|' . $kanjiname .'|' . PHP_EOL);
				print_r('更新は成功しました' . PHP_EOL);
			}
			else
			{
				print_r($login_id . '|' . $passwd . '|' . $kanjisei .'|' . $kanjimei . '|' . $kanjiname .'|' . PHP_EOL);
				print_r($job_name . '|' . $naisen . '|' . $mail_acc .'|' . $pbno . '|' . $belong_name .'|' . PHP_EOL);
				print_r($post_name . '|' . $kanasei . '|' . $kanamei .'|' . $kananame . PHP_EOL);
				print_r("更新は失敗しました。" . PHP_EOL);
			}
			
    	}

	}
	ldap_close($ldap_conn);
}

exit;


?>
