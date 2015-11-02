<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();
require_once($dir."/define.inc.php");

function getParentId($id, &$ary)
{
	global $oMgr;
	$sql = "SELECT parent_id FROM garoon_belong_view WHERE current_id = '" . $id . "'";

	$pid = $oMgr->oDb->getOne($sql);

	if ($pid != "")
	{
		array_unshift($ary, $pid);
		getParentId($pid, &$ary);
	}

	return;
}

function getOrderLevel($id)
{
	global $oMgr;
	$sql = "SELECT order_level FROM garoon_belong_view WHERE current_id = '" . $id . "'";

	$order = $oMgr->oDb->getOne($sql);

	return $order;
}


$oMgr = new common_mgr();

$sql = "select distinct UM.user_id, UM.login_id, GL.last_id from user_mst AS UM left outer join garoon_belong_last_id AS GL on UM.belong_chg_id = GL.belong_chg_id where UM.garoon_disused_flg != '1' and UM.login_id is not null and UM.login_id != '' and UM.start_date <= now()::date and COALESCE(UM.end_date, now()::date) >= now()::date and UM.belong_chg_id not in (408, 409)";

$aryRet = $oMgr->oDb->getAll($sql);

$csv = '"ログイン名","組織コード"';
$csv .= "\n";
if (is_array($aryRet))
{
	foreach ($aryRet AS $data)
	{
		$cid = $data['last_id'];

		if ($cid == "")
		{
			continue;
		}

		// 親IDをとる
		$parent = array();
		getParentId($cid, &$parent);

		$csv .= '"' . $data['login_id'] . '"';		// ログイン名

		// 並べ替えレベルを取得
		$c_order = getOrderLevel($cid);

		$aryParent = array();
		$aryParent[$c_order] = $cid;
		if (is_array($parent))
		{
			// 並べ替える
			foreach ($parent AS $pid)
			{
				$order = getOrderLevel($pid);

				$aryParent[$order] = $pid;
			}
		}

		ksort($aryParent);

		foreach ($aryParent AS $pid)
		{
			$csv .= ',"' . $pid . '"';	// 組織コード
		}

		// サブをとってくる
		$sql = "SELECT GL.last_id FROM user_sub_chg_tbl AS UM, garoon_belong_last_id AS GL where UM.belong_chg_id = GL.belong_chg_id and UM.del_flg = '0' and UM.user_id = " . $data['user_id'] . " order by UM.list_no";

		$arySub = $oMgr->oDb->getCol($sql);
		if (is_array($arySub))
		{
			foreach ($arySub AS $sub_cid)
			{
				// 親IDをとる
				$sub_parent = array();
				getParentId($sub_cid, &$sub_parent);

				if (is_array($sub_parent))
				{
					foreach ($sub_parent AS $pid)
					{
						$csv .= ',"' . $pid . '"';	// 組織コード
					}
				}
				$csv .= ',"' . $sub_cid . '"';
			}
		}

		$csv .= "\n";
	}
}

if ($csv != "")
{
	$csv = mb_convert_encoding($csv, "sjis-win", "UTF-8");
	file_put_contents(GAROON_OUTPUT_DIR."users_ounits.csv", $csv);
}

exit;


?>
