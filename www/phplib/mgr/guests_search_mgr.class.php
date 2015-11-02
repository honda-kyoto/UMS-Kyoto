<?php
/**********************************************************
* File         : guests_search_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/guests_regist_common_mgr.class.php");
require_once("sql/guests_search_sql.inc.php");

define ("DEFAULT_ORDER", "make_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class guests_search_mgr extends guests_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function saveSearchData($request)
	{
		$_SESSION['GT_SCH']['guest_name'] = @$request['guest_name'];
		$_SESSION['GT_SCH']['company_name'] = @$request['company_name'];
		$_SESSION['GT_SCH']['mac_addr'] = @$request['mac_addr'];
		$_SESSION['GT_SCH']['entry_date'] = @$request['entry_date'];
		$_SESSION['GT_SCH']['all_data_flg'] = @$request['all_data_flg'];

		$_SESSION['GT_SCH']['list_max'] = @$request['list_max'];
		$_SESSION['GT_SCH']['order']    = "";
		$_SESSION['GT_SCH']['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION['GT_SCH']['order'])
		{
			if (@$_SESSION['GT_SCH']['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION['GT_SCH']['order'] = $request['order'];
		$_SESSION['GT_SCH']['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION['GT_SCH']['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION['GT_SCH']['desc'];
	}

	function savePage($page)
	{
		$_SESSION['GT_SCH']['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION['GT_SCH']['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['guest_name'] = @$_SESSION['GT_SCH']['guest_name'];
		$request['company_name'] = @$_SESSION['GT_SCH']['company_name'];
		$request['mac_addr'] = @$_SESSION['GT_SCH']['mac_addr'];
		$request['entry_date'] = @$_SESSION['GT_SCH']['entry_date'];
		$request['all_data_flg'] = @$_SESSION['GT_SCH']['all_data_flg'];

		$request['list_max'] = @$_SESSION['GT_SCH']['list_max'];
		$request['order'] = @$_SESSION['GT_SCH']['order']   ;
		$request['desc'] = @$_SESSION['GT_SCH']['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION['GT_SCH']['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";

		$aryCond = array();

		// 名称
		if (@$request['guest_name'] != "")
		{
			$aryCond[] = "guest_name LIKE '%" . string::replaceSql($request['guest_name']) . "%'";
		}

		// アカウント
		if (@$request['company_name'] != "")
		{
			$aryCond[] = "company_name LIKE '%" . string::replaceSql($request['company_name']) . "%'";
		}

		// MACアドレス
		if (@$request['mac_addr'] != "")
		{
			$mac_addr = strtolower($request['mac_addr']);
			$mac_addr = str_replace(":", "", $mac_addr);
			$mac_addr = str_replace("-", "", $mac_addr);
			$aryCond[] = "mac_addr LIKE '%" . string::replaceSql($mac_addr) . "%'";
		}

		// 登録日
		if (@$request['entry_date'] != "")
		{
			$aryCond[] = "make_time::date = '" . string::replaceSql($request['entry_date']) . "'";
		}

		if (@$request['all_data_flg'] != '1')
		{
			$aryCond[] = "make_time >= current_timestamp + '-1 day'";
		}


		if ($this->isNormalUser())
		{
			// 一般ユーザの場合
			$user_id = $this->getSessionData('LOGIN_USER_ID');
			$aryCond[] = "make_id = " . string::replaceSql($user_id);
		}

		if (count($aryCond) > 0)
		{
			$args['COND'] = " AND " . join(" AND ", $aryCond);
		}

		return $args;
	}

	function getCount($request)
	{
		$args = $this->getSearchArgs($request);

		$sql = $this->getQuery('GETCOUNT', $args);

		$count = $this->oDb->getOne($sql);

		return $count;
	}

	/*
	 * getList
	*/
	function getList($request, $limit)
	{
		$args = $this->getSearchArgs($request);

		if ($request['order'] != "")
		{
			switch($request['order'])
			{
				//特殊処理なし
				default:
					$args['ORDER'] = $request['order'];
					break;
			}
			
			$args['DESC'] = $request['desc'];
		}
		else
		{
			$args['ORDER'] = DEFAULT_ORDER;
			$args['DESC'] = DEFAULT_DESC;
		}
		
		if ($limit == "")
		{
			$limit = DEFAULT_LIST_MAX;
		}

		$offset = ($request['page'] - 1) * $limit;

		$args['LIMIT'] = $limit;
		$args['OFFSET'] = $offset;

		$sql = $this->getQuery('GETLIST', $args);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function getPrintGuestData($guest_id)
	{
		$sql = $this->getQuery('GET_PRINT_GUEST_DATA', $guest_id);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}
	
}
?>
