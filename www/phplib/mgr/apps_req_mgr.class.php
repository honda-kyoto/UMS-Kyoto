<?php
/**********************************************************
* File         : apps_req_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/apps_req_sql.inc.php");

define ("DEFAULT_ORDER", "AHE.update_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_req_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}
	function saveSearchData($request)
	{
		$_SESSION['AP_SCH']['app_type_id'] = @$request['app_type_id'];
		$_SESSION['AP_SCH']['vlan_ridge_id'] = @$request['vlan_ridge_id'];
		$_SESSION['AP_SCH']['vlan_floor_id'] = @$request['vlan_floor_id'];
		$_SESSION['AP_SCH']['vlan_room_id'] = @$request['vlan_room_id'];
		$_SESSION['AP_SCH']['vlan_id'] = @$request['vlan_id'];
		$_SESSION['AP_SCH']['mac_addr'] = @$request['mac_addr'];
		$_SESSION['AP_SCH']['ip_addr'] = @$request['ip_addr'];
		$_SESSION['AP_SCH']['app_name'] = @$request['app_name'];
		$_SESSION['AP_SCH']['entry_user_name'] = @$request['entry_user_name'];
		//		$_SESSION['AP_SCH']['entry_kbn_status'] = @$request['entry_kbn_status'];

		$_SESSION['AP_SCH']['list_max'] = @$request['list_max'];
		$_SESSION['AP_SCH']['order']    = "";
		$_SESSION['AP_SCH']['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION['AP_SCH']['order'])
		{
			if (@$_SESSION['AP_SCH']['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION['AP_SCH']['order'] = $request['order'];
		$_SESSION['AP_SCH']['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION['AP_SCH']['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION['AP_SCH']['desc'];
	}

	function savePage($page)
	{
		$_SESSION['AP_SCH']['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION['AP_SCH']['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['app_type_id'] = @$_SESSION['AP_SCH']['app_type_id'];
		$request['vlan_ridge_id'] = @$_SESSION['AP_SCH']['vlan_ridge_id'];
		$request['vlan_floor_id'] = @$_SESSION['AP_SCH']['vlan_floor_id'];
		$request['vlan_room_id'] = @$_SESSION['AP_SCH']['vlan_room_id'];
		$request['vlan_id'] = @$_SESSION['AP_SCH']['vlan_id'];
		$request['mac_addr'] = @$_SESSION['AP_SCH']['mac_addr'];
		$request['ip_addr'] = @$_SESSION['AP_SCH']['ip_addr'];
		$request['app_name'] = @$_SESSION['AP_SCH']['app_name'];
		$request['entry_user_name'] = @$_SESSION['AP_SCH']['entry_user_name'];
		//		$request['entry_kbn_status'] = @$_SESSION['AP_SCH']['entry_kbn_status'];
//
		$request['list_max'] = @$_SESSION['AP_SCH']['list_max']   ;
		$request['order'] = @$_SESSION['AP_SCH']['order']   ;
		$request['desc'] = @$_SESSION['AP_SCH']['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION['AP_SCH']['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";
		$args['ENTRY_STATUS_ENTRY'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);

		// ソート処理のために以下の定数をSQLパラメータに追加
		$args['IP_KBN_DHCP'] = $this->sqlItemChar(IP_KBN_DHCP);
		$args['IP_KBN_FREE'] = $this->sqlItemChar(IP_KBN_FREE);
		$args['DUMY_IP_ADDR_DHCP'] = $this->sqlItemChar(DUMY_IP_ADDR_DHCP);
		$args['DUMY_IP_ADDR_NONE'] = $this->sqlItemChar(DUMY_IP_ADDR_NONE);
		$args['WIRE_KBN_WLESS'] = $this->sqlItemChar(WIRE_KBN_WLESS);
		$args['WIRE_KBN_FREE'] = $this->sqlItemChar(WIRE_KBN_FREE);

		$aryCond = array();

		// 機器種別
		if (@$request['app_type_id'] != "")
		{
			$aryCond[] = "AHE.app_type_id = " . string::replaceSql($request['app_type_id']);
		}

		// 設置場所
		if (@$request['vlan_id'] != "")
		{
			$aryCond[] = "AHE.vlan_id = " . string::replaceSql($request['vlan_id']);
		}
		else if (@$request['vlan_room_id'] != "")
		{
			$aryCond[] = "AHE.vlan_room_id = " . string::replaceSql($request['vlan_room_id']);
		}
		else if (@$request['vlan_floor_id'] != "")
		{
			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst WHERE AHE.vlan_room_id = vlan_room_id AND del_flg = '0' AND vlan_floor_id = " . string::replaceSql($request['vlan_floor_id']) . ")";
		}
		else if (@$request['vlan_ridge_id'] != "")
		{

			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst AS VRM,vlan_floor_mst AS VFM WHERE AHE.vlan_room_id = VRM.vlan_room_id AND VRM.vlan_floor_id = VFM.vlan_floor_id AND VRM.del_flg = '0' AND VFM.del_flg = '0' AND VFM.vlan_ridge_id = " . string::replaceSql($request['vlan_ridge_id']) . ")";
		}

		// 申請者名（漢字・カナ）
		if (@$request['entry_user_name'] != "")
		{
			$kananame = string::han2zen($request['entry_user_name']);
			$kananame = str_replace("　", " ", $kananame);
			$kananame = str_replace(" ", "", $kananame);
			$strBuff  = "EXISTS (SELECT * FROM user_mst AS UM WHERE AHE.entry_id = UM.user_id AND ";
			$strBuff .= "(";
			$strBuff .= "UM.kanasei || UM.kanamei LIKE '%" . string::replaceSql($kananame) . "%'";
			$strBuff .= " OR ";
			$strBuff .= "UM.kanjisei || UM.kanjimei LIKE '%" . string::replaceSql($kananame) . "%'";
			$strBuff .= ")";
			$strBuff .= ")";
			$aryCond[] = $strBuff;
		}

		// MACアドレス
		if (@$request['mac_addr'] != "")
		{
			$mac_addr = strtolower($request['mac_addr']);
			$mac_addr = str_replace(":", "", $mac_addr);
			$mac_addr = str_replace("-", "", $mac_addr);
			$aryCond[] = "AHE.mac_addr LIKE '%" . string::replaceSql($mac_addr) . "%'";
		}

		// IPアドレス
		if (@$request['ip_addr'] != "")
		{
			$aryCond[] = "AHE.ip_addr LIKE '%" . string::replaceSql($request['ip_addr']) . "%'";
		}

		// 名称
		if (@$request['app_name'] != "")
		{
			$aryCond[] = "AHE.app_name LIKE '%" . string::replaceSql($request['app_name']) . "%'";
		}

		// ログインユーザの権限によって処理分け

		// 管理権限があるか？
		if (!$this->hasAdminActType('apps_req.php'))
		{
			// VLAN管理者になっているもののみ表示
			$user_id = $this->getSessionData('LOGIN_USER_ID');
			$uid = string::replaceSql($user_id);
			$aryCond[] = <<<  SQL
(
EXISTS (SELECT * FROM vlan_admin_list WHERE AHE.vlan_id = vlan_id AND del_flg = '0' AND user_id = $uid)
OR
EXISTS (SELECT * FROM vlan_admin_list AS VA, app_list_entry AS AL WHERE VA.vlan_id = AL.vlan_id AND AL.app_id = AHE.app_id AND AL.entry_no = AHE.entry_no AND VA.del_flg = '0' AND AL.del_flg = '0' AND VA.user_id = $uid)
)

SQL;
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
				case "ip_addr":
					$args['ORDER'] = "LPAD(split_part(ip_addr_sort, '.', 1), 3,'0') " .
									"|| LPAD(split_part(ip_addr_sort, '.', 2), 3,'0') " .
									"|| LPAD(split_part(ip_addr_sort, '.', 3), 3,'0') " .
									"|| LPAD(split_part(ip_addr_sort, '.', 4), 3,'0') ";
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
