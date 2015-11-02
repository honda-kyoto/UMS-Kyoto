<?php
/**********************************************************
* File         : mlists_req_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/mlists_regist_common_mgr.class.php");
require_once("sql/mlists_req_sql.inc.php");

define ("DEFAULT_ORDER", "MHE.update_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_req_mgr extends mlists_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}
	function saveSearchData($request)
	{
		$_SESSION['ML_SCH']['mlist_name'] = @$request['mlist_name'];
		$_SESSION['ML_SCH']['mlist_acc'] = @$request['mlist_acc'];
		$_SESSION['ML_SCH']['mlist_kbn'] = @$request['mlist_kbn'];
		//$_SESSION['ML_SCH']['entry_kbn_status'] = @$request['entry_kbn_status'];

		$_SESSION['ML_SCH']['list_max'] = @$request['list_max'];
		$_SESSION['ML_SCH']['order']    = "";
		$_SESSION['ML_SCH']['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION['ML_SCH']['order'])
		{
			if (@$_SESSION['ML_SCH']['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION['ML_SCH']['order'] = $request['order'];
		$_SESSION['ML_SCH']['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION['ML_SCH']['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION['ML_SCH']['desc'];
	}

	function savePage($page)
	{
		$_SESSION['ML_SCH']['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION['ML_SCH']['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['mlist_name'] = @$_SESSION['ML_SCH']['mlist_name'];
		$request['mlist_acc'] = @$_SESSION['ML_SCH']['mlist_acc'];
		$request['mlist_kbn'] = @$_SESSION['ML_SCH']['mlist_kbn'];
		//$request['entry_kbn_status'] = @$_SESSION['ML_SCH']['entry_kbn_status'];

		$request['list_max'] = @$_SESSION['ML_SCH']['list_max']   ;
		$request['order'] = @$_SESSION['ML_SCH']['order']   ;
		$request['desc'] = @$_SESSION['ML_SCH']['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION['ML_SCH']['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";
		$args['ENTRY_STATUS_ENTRY'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);

		$aryCond = array();

			// 名称
		if (@$request['mlist_name'] != "")
		{
			$aryCond[] = "mlist_name LIKE '%" . string::replaceSql($request['mlist_name']) . "%'";
		}

		// アカウント
		if (@$request['mlist_acc'] != "")
		{
			$aryCond[] = "mlist_acc LIKE '%" . string::replaceSql($request['mlist_acc']) . "%'";
		}

		// 種別
		if (@$request['mlist_kbn'] != "")
		{
			$aryCond[] = "mlist_kbn = '" . string::replaceSql($request['mlist_kbn']) . "'";
		}

		/*
		$cnt = 0;
		if (is_array($request['entry_kbn_status']))
		{
			foreach ($request['entry_kbn_status'] AS $key => $val)
			{
				if ($val != "1")
				{
					continue;
				}

				if ($key == 'agreed')
				{
					$aryKbnStat[] = "(APP.entry_kbn = NULL AND APP.entry_status = NULL)";
				}

				list ($entry_kbn, $entry_status) = explode("_", $key);

				$aryKbnStat[] = "(APP.entry_kbn = '" . string::replaceSql($entry_kbn) . "' AND APP.entry_status = '" . string::replaceSql($entry_status) . "')";

				$cnt++;
			}
		}
		if ($cnt > 0)
		{
			$aryCond[] = "(" . implode(" OR ", $aryKbnStat) . ")";
		}

		*/

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
				case "admin_name":
					$args['ORDER'] = "sort_admin_name";
					break;
				case "entry_status":
					$args['ORDER'] = "entry_kbn || '_' || entry_status";
					break;
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


}
?>
