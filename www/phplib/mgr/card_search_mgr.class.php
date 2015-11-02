<?php
/**********************************************************
* File         : card_search_mgr.class.php
* Authors      : kazuyoshi shibuta
* Date         : 2015.06.04
* Last Update  : 2015.06.04
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/card_search_sql.inc.php");

define ("DEFAULT_ORDER", "card_1");
define ("DEFAULT_DESC", "ASC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class card_search_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function saveSearchData($request)
	{
		$_SESSION['ML_SCH']['card_id'] = @$request['card_id'];
		$_SESSION['ML_SCH']['card_name'] = @$request['card_name'];
		$_SESSION['ML_SCH']['card_name_kana'] = @$request['card_name_kana'];

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
		$request['card_id'] = @$_SESSION['ML_SCH']['card_id'];
		$request['card_name'] = @$_SESSION['ML_SCH']['card_name'];
		$request['card_name_kana'] = @$_SESSION['ML_SCH']['card_name_kana'];

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

		$aryCond = array();

		// キー番号
		if (@$request['card_id'] != "")
		{
			$aryCond[] = "card_6 LIKE '%" . string::replaceSql($request['card_id']) . "%'";
		}

		// 氏名
		if (@$request['card_name'] != "")
		{
			$aryCond[] = "card_8 LIKE '%" . string::replaceSql($request['card_name']) . "%'";
		}

		// カナ
		if (@$request['card_name_kana'] != "")
		{
			$aryCond[] = "card_9 LIKE '%" . string::replaceSql($request['card_name_kana']) . "%'";
		}

		if (count($aryCond) > 0)
		{
			$args['COND'] = " WHERE " . join(" AND ", $aryCond);
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
			$args['ORDER'] = $request['order'];
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
