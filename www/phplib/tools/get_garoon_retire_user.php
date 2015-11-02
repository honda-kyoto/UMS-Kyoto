<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

require_once($dir."/define.inc.php");

$oMgr = new common_mgr();

$sql = "
SELECT
  UM.user_id,
  UM.login_id,
  UM.kanjisei || ' ' || UM.kanjimei AS kanjiname,
  UM.login_passwd,
  UM.kanasei || ' ' || UM.kanamei AS kananame,
  UM.mail_acc || CASE WHEN UM.mail_acc IS NOT NULL THEN '@ncvc.go.jp' else '' END AS mail_addr,
  PM.post_name || CASE WHEN usp.sub_post_name IS NOT NULL THEN usp.sub_post_name else '' END AS post_name,
  UM.naisen,
  UM.pbno
FROM
  user_mst AS UM
  LEFT OUTER JOIN post_mst AS PM ON
    UM.post_id = PM.post_id AND
    PM.del_flg = '0'
  LEFT OUTER JOIN (
      SELECT
        um.user_id,
        array_to_string(array(SELECT pm.post_name FROM user_sub_post_tbl AS usp, post_mst AS pm WHERE usp.post_id = pm.post_id AND pm.del_flg = '0' AND usp.del_flg = '0' AND usp.user_id = um.user_id), '|') AS sub_post_name
      FROM
        user_sub_post_tbl AS um
      WHERE
        um.post_id IS NOT NULL
    ) AS usp ON
    UM.user_id = usp.user_id
WHERE
  UM.garoon_processed_flg = '0' AND
  (
    (
      UM.garoon_disused_flg != '1' AND
      UM.login_id IS NOT NULL AND
      UM.login_id != '' AND
      UM.retire_flg = '1' AND
      COALESCE(UM.end_date, now()::date) < now()::date
    ) OR
    UM.garoon_disused_flg = '1'
  ) AND
  UM.belong_chg_id not in (408, 409)
";

$aryRet = $oMgr->oDb->getAssoc($sql);

$csv = '"現ログイン名","名前","新ログイン名","パスワード","ロケール","拠点","表示優先度","使用/停止","削除フラグ","よみ","E-mail","メモ","役職","連絡先","内線番号","PHS番号"';
$csv .= "\n";
if (is_array($aryRet))
{
	foreach ($aryRet AS $user_id => $data)
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
		$csv .= ',"1"';					// 削除フラグ
		$csv .= ',"' . $data['kananame'] . '"';		// よみ
		$csv .= ',"' . $data['mail_addr'] . '"';	// E-mail
		$csv .= ',""';					// メモ
		$csv .= ',"' . $data['post_name'] . '"';	// 役職
		$csv .= ',""';					// 連絡先
		//$csv .= ',"' . $data['naisen'] . '"';		// 内線番号
		$csv .= ',"' . $data['pbno'] . '"';		// PHS番号
//		$csv .= ',""';					// 所属学会
		$csv .= "\n";
	}
}

if ($csv != "")
{
	$csv = mb_convert_encoding($csv, "sjis-win", "UTF-8");
	file_put_contents(GAROON_OUTPUT_DIR."users_retire.csv", $csv);
}

// フラグを更新
$aryUserId = array_keys($aryRet);

$strIds = implode(",", $aryUserId);

if ($strIds != "")
{
	$sql = "update user_mst set garoon_processed_flg = '1', garoon_process_time = now() where user_id in (" . $strIds . ")";

	$ret = $oMgr->oDb->query($sql);

	if (!$ret)
	{
		echo "フラグ更新失敗";
	}

}

exit;


?>
