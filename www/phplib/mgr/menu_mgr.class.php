<?php
/**********************************************************
* File         : menu_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/menu_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class menu_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function chkShiftPage()
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$sql = $this->getQuery('CHKSHIFTPAGE', $user_id);

		$chk = $this->oDb->getRow($sql);

		return $chk;
	}

	function makeMenuList()
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$sql = $this->getQuery('GET_MENU_LIST', $user_id);

		$aryMenuList = $this->oDb->getAssoc($sql);

		if (!is_array($aryMenuList))
		{
			return "";
		}

		foreach ($aryMenuList AS $menu_id => $aryData)
		{
			// 表示対象チェック
			if ($aryData['chk_col_flg'] == '1' && $aryData['chk_col'] != "")
			{
				$chk_col = $aryData['chk_col'];
				if ($aryData[$chk_col] == '0')
				{
					continue;
				}
			}

			$menu_name = $aryData['menu_name'];
			$menu_str = $aryData['menu_str'];
			$script_name = $aryData['script_name'];

			$chk_file = $_SERVER['DOCUMENT_ROOT'] . "/" . $script_name;
			if (!file_exists($chk_file))
			{
				$script_name = "prepare.php";
			}

			$list .= <<< HTML
              <li><a href="$script_name" class="menuBtn" title="$menu_name"><div><span>$menu_str</span></div></a></li>

HTML;

		}

		return $list;
	}
}
?>
