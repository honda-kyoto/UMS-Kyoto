<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

require_once($dir."/define.inc.php");

$oMgr = new common_mgr();

$sql = "select distinct UM.login_id,UM.kanjisei || ' ' || UM.kanjimei as kanjiname,UM.login_passwd,UM.kanasei || ' ' || UM.kanamei as kananame,UM.mail_acc || case when UM.mail_acc is not null then '@ncvc.go.jp' else '' end as mail_addr,PM.post_name || case when usp.sub_post_name is not null then '|' || usp.sub_post_name else '' end as post_name,JM.job_name || case when usj.sub_job_name is not null then '|' || usj.sub_job_name else '' end as job_name,UM.naisen,UM.pbno from user_mst as UM left outer join post_mst as PM on UM.post_id = PM.post_id and PM.del_flg = '0' left outer join (select um.user_id, array_to_string(array(select pm.post_name from user_sub_post_tbl as usp, post_mst as pm where usp.post_id = pm.post_id and pm.del_flg = '0' and usp.del_flg = '0' and usp.user_id = um.user_id), '|') as sub_post_name from user_sub_post_tbl as um where um.post_id is not null) as usp on UM.user_id = usp.user_id left outer join job_mst as JM on UM.job_id = JM.job_id and JM.del_flg = '0' left outer join (select um.user_id, array_to_string(array(select jm.job_name from user_sub_job_tbl as usj, job_mst as jm where usj.job_id = jm.job_id and jm.del_flg = '0' and usj.del_flg = '0' and usj.user_id = um.user_id), '|') as sub_job_name from user_sub_job_tbl as um where um.job_id is not null) as usj on UM.user_id = usj.user_id where UM.garoon_disused_flg != '1' and UM.login_id is not null and UM.login_id != '' and UM.start_date <= now()::date and COALESCE(UM.end_date, now()::date) >= now()::date and UM.belong_chg_id not in (408, 409)";

$aryRet = $oMgr->oDb->getAll($sql);

$csv = '"現ログイン名","名前","新ログイン名","パスワード","ロケール","拠点","表示優先度","使用/停止","削除フラグ","よみ","E-mail","メモ","役職","連絡先","職種","内線番号","PHS番号"';
$csv .= "\n";
if (is_array($aryRet))
{
	foreach ($aryRet AS $data)
	{
		$passwd = $oMgr->passwordDecrypt($data['login_passwd']);
		if ($data['post_name'] == '一般')
		{
			$data['post_name'] = "";
		}

		$csv .= '"' . $data['login_id'] . '"';		// 現ログイン名
		$csv .= ',"' . $data['kanjiname'] . '"';	// 名前
		$csv .= ',"' . $data['login_id'] . '"';	// 新ログイン名
		$csv .= ',"' . $passwd . '"';			// パスワード
		$csv .= ',""';					// ロケール
		$csv .= ',""';					// 拠点
		$csv .= ',""';					// 表示優先度
		$csv .= ',"1"';					// 使用/停止
		$csv .= ',""';					// 削除フラグ
		$csv .= ',"' . $data['kananame'] . '"';		// よみ
		$csv .= ',"' . $data['mail_addr'] . '"';	// E-mail
		$csv .= ',""';					// メモ
		$csv .= ',"' . $data['post_name'] . '"';	// 役職
		$csv .= ',""';					// 連絡先
		$csv .= ',"' . $data['job_name'] . '"';	// 職種
		//$csv .= ',"' . $data['naisen'] . '"';		// 内線番号
		$csv .= ',"' . $data['pbno'] . '"';		// PHS番号
//		$csv .= ',""';					// 所属学会
		$csv .= "\n";
	}
}

if ($csv != "")
{
	$csv = mb_convert_encoding($csv, "SJIS-win", "UTF-8");
	file_put_contents(GAROON_OUTPUT_DIR."users.csv", $csv);
}

exit;


?>
