<?php
/**********************************************************
* File         : vpns_search_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/vpns_regist_common_mgr.class.php");
require_once("sql/vpns_search_sql.inc.php");

define ("DEFAULT_ORDER", "VHT.update_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vpns_search_mgr extends vpns_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function saveSearchData($request)
	{
		$_SESSION['VP_SCH']['vpn_kbn'] = @$request['vpn_kbn'];
		$_SESSION['VP_SCH']['vpn_name'] = @$request['vpn_name'];
		$_SESSION['VP_SCH']['group_name'] = @$request['group_name'];

		$_SESSION['VP_SCH']['list_max'] = @$request['list_max'];
		$_SESSION['VP_SCH']['order']    = "";
		$_SESSION['VP_SCH']['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION['VP_SCH']['order'])
		{
			if (@$_SESSION['VP_SCH']['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION['VP_SCH']['order'] = $request['order'];
		$_SESSION['VP_SCH']['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION['VP_SCH']['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION['VP_SCH']['desc'];
	}

	function savePage($page)
	{
		$_SESSION['VP_SCH']['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION['VP_SCH']['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['vpn_kbn'] = @$_SESSION['VP_SCH']['vpn_kbn'];
		$request['vpn_name'] = @$_SESSION['VP_SCH']['vpn_name'];
		$request['group_name'] = @$_SESSION['VP_SCH']['group_name'];

		$request['list_max'] = @$_SESSION['VP_SCH']['list_max']   ;
		$request['order'] = @$_SESSION['VP_SCH']['order']   ;
		$request['desc'] = @$_SESSION['VP_SCH']['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION['VP_SCH']['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";

		$aryCond = array();

		// 種別
		if (@$request['vpn_kbn'] != "")
		{
			$aryCond[] = "VHT.vpn_kbn = '" . string::replaceSql($request['vpn_kbn']) . "'";
		}

		// 名称
		if (@$request['vpn_name'] != "")
		{
			$aryCond[] = "VHT.vpn_name LIKE '%" . string::replaceSql($request['vpn_name']) . "%'";
		}

		// アカウント
		if (@$request['group_name'] != "")
		{
			$aryCond[] = "VHT.group_name LIKE '%" . string::replaceSql($request['group_name']) . "%'";
		}

		if ($this->isNormalUser())
		{
			// 一般ユーザの場合
			$user_id = $this->getSessionData('LOGIN_USER_ID');
			$aryCond[] = "EXISTS (SELECT * FROM vpn_admin_list WHERE VHT.vpn_id = vpn_id AND user_id = " . string::replaceSql($user_id) . ")";
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
				case "admin_name":
					$args['ORDER'] = "sort_admin_name";
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

	function deleteVpnData($vpn_id)
	{
		$this->oDb->begin();

		// 基本情報
		$args = $this->getSqlArgs();
		$args[0] = $vpn_id;

		$sql = $this->getQuery('DELETE_VPN_HEAD', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$sql = $this->getQuery('DELETE_VPN_ADMIN', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$sql = $this->getQuery('DELETE_VPN_MEMBERS', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// 同期(メンバーだけ削除）
		//$this->delVpnMemberList($vpn_id);

		return true;

	}

}
?>
