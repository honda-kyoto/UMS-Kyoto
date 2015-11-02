<?php
/**********************************************************
* File         : app_printer_add_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/app_printer_add_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class app_printer_add_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getTerminalDeviceList($terminal_id)
	{
		$sql = $this->getQuery('GET_TERMINAL_DEVICE_LIST', $terminal_id);

		$aryRet = $this->oDb->getCol($sql);

		return $aryRet;

	}

	function updateData($request)
	{
		$terminal_id = $request['terminal_id'];

		$this->oDb->begin();

		// 一旦すべて削除
		$sql = $this->getQuery('DELETE_TERMINAL_DEVICE', $terminal_id);

		$this->oDb->query($sql);

		$args = $this->getSqlArgs();
		$args[0] = $terminal_id;
		$disp_num = 1;
		if (is_array($request['device_id']))
		{
			foreach ($request['device_id'] AS $device_id)
			{
				$args[1] = $device_id;
				$args[2] = $disp_num++;

				$sql = $this->getQuery('INSERT_TERMINAL_DEVICE', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}
			}
		}

		$this->oDb->end();

		return true;
	}

	function makeTerminalList($request)
	{
		// 条件からプリンタのリストを取得
		$aryCond = array();

		if (@$request['tm_vlan_room_id'] != "")
		{
			$aryCond[] = "APP.vlan_room_id = " . string::replaceSql($request['tm_vlan_room_id']);
		}
		else if (@$request['tm_vlan_floor_id'] != "")
		{
			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst WHERE APP.vlan_room_id = vlan_room_id AND del_flg = '0' AND vlan_floor_id = " . string::replaceSql($request['tm_vlan_floor_id']) . ")";
		}
		else if (@$request['tm_vlan_ridge_id'] != "")
		{

			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst AS VRM,vlan_floor_mst AS VFM WHERE APP.vlan_room_id = VRM.vlan_room_id AND VRM.vlan_floor_id = VFM.vlan_floor_id AND VRM.del_flg = '0' AND VFM.del_flg = '0' AND VFM.vlan_ridge_id = " . string::replaceSql($request['tm_vlan_ridge_id']) . ")";
		}

		if ($request['unallocated_only'] == "1")
		{
			$aryCond[] = "NOT EXISTS (SELECT * FROM sbc_terminal_device_tbl WHERE APP.app_id = terminal_id)";
		}


		$args = array();
		$args['APP_TYPE_ID'] = "1, 2";
		$args['COND'] = "";

		if (count($aryCond) > 0)
		{
			$args['COND'] = " AND " . join(" AND ", $aryCond);
		}

		$sql = $this->getQuery('GET_TERMINAL_LIST', $args);

		$aryRet = $this->oDb->getAssoc($sql);

		$ary = array();
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $app_id => $aryData)
			{
				$room_name = $this->getVlanRoomName($aryData['vlan_room_id']);
				$ary[$app_id] = "＜" . $app_id . "＞" . $aryData['app_name'] . "(" . $room_name . ")";
			}
		}

		$options = $this->makeSelectOptions($ary, @$request['terminal_id']);

		return $options;
	}

	function makeDeviceList($request)
	{
		// 条件からプリンタのリストを取得
		$aryCond = array();

		if (@$request['dv_vlan_room_id'] != "")
		{
			$aryCond[] = "APP.vlan_room_id = " . string::replaceSql($request['dv_vlan_room_id']);
		}
		else if (@$request['dv_vlan_floor_id'] != "")
		{
			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst WHERE APP.vlan_room_id = vlan_room_id AND del_flg = '0' AND vlan_floor_id = " . string::replaceSql($request['dv_vlan_floor_id']) . ")";
		}
		else if (@$request['dv_vlan_ridge_id'] != "")
		{

			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst AS VRM,vlan_floor_mst AS VFM WHERE APP.vlan_room_id = VRM.vlan_room_id AND VRM.vlan_floor_id = VFM.vlan_floor_id AND VRM.del_flg = '0' AND VFM.del_flg = '0' AND VFM.vlan_ridge_id = " . string::replaceSql($request['dv_vlan_ridge_id']) . ")";
		}


		$args = array();
		$args['APP_TYPE_ID'] = "3";
		$args['COND'] = "";

		if (count($aryCond) > 0)
		{
			$args['COND'] = " AND " . join(" AND ", $aryCond);
		}

		$sql = $this->getQuery('GET_DEVICE_LIST', $args);

		$aryRet = $this->oDb->getAssoc($sql);

		// 選択済みを削除
		if (is_array($request['device_id']))
		{
			foreach ($request['device_id'] AS $device_id)
			{
				unset($aryRet[$device_id]);
			}
		}

		$ary = array();
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $app_id => $aryData)
			{
				$room_name = $this->getVlanRoomName($aryData['vlan_room_id']);
				$ary[$app_id] = $aryData['app_name'] . "(" . $room_name . ")";
			}
		}

		$options = $this->makeSelectOptionsTooltip($ary, $driver_name);

		return $options;
	}

	function makeDeviceSelectedList($request)
	{
		$aryList = array();
		if (is_array($request['device_id']))
		{
			foreach ($request['device_id'] AS $device_id)
			{
				$sql = $this->getQuery('GET_APP_NAME', $device_id);

				$aryRet = $this->oDb->getRow($sql);

				$room_name = $this->getVlanRoomName($aryRet['vlan_room_id']);

				$aryList[$device_id] = $aryRet['app_name'] . "(" . $room_name . ")";;
			}
		}

		$options = $this->makeSelectOptionsTooltip($aryList, "");

		return $options;
	}
}

?>
