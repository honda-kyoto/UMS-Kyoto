<?php
/**********************************************************
* File         : printer_driver_add_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/printer_driver_add_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class printer_driver_add_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function updateData($request)
	{
		$app_id = $request['app_id'];
		$driver_name = $request['driver_name'];

		if ($driver_name == "")
		{
			// 削除の場合
			$sql_id = 'DELETE_DEVICE_DRIVERNAME';
		}
		else
		{
			// 新規または更新の場合
			// 存在チェック
			$ext_driver_name = $this->getPrinterDriverName($app_id);

			if ($ext_driver_name == $driver_name)
			{
				return true;
			}

			if ($ext_driver_name == "")
			{
				$sql_id = 'INSERT_DEVICE_DRIVERNAME';
			}
			else
			{
				$sql_id = 'UPDATE_DEVICE_DRIVERNAME';
			}
		}


		$args = $this->getSqlArgs();
		$args[0] = $app_id;
		$args[1] = $driver_name;

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function getPrinterDriverName($app_id)
	{
		$sql = $this->getQuery('EXISTS_APP_ID', $app_id);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}

	function makePrinterList($request)
	{
		// 条件からプリンタのリストを取得
		$aryCond = array();

		if (@$request['vlan_room_id'] != "")
		{
			$aryCond[] = "APP.vlan_room_id = " . string::replaceSql($request['vlan_room_id']);
		}
		else if (@$request['vlan_floor_id'] != "")
		{
			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst WHERE APP.vlan_room_id = vlan_room_id AND del_flg = '0' AND vlan_floor_id = " . string::replaceSql($request['vlan_floor_id']) . ")";
		}
		else if (@$request['vlan_ridge_id'] != "")
		{

			$aryCond[] = "EXISTS (SELECT * FROM vlan_room_mst AS VRM,vlan_floor_mst AS VFM WHERE APP.vlan_room_id = VRM.vlan_room_id AND VRM.vlan_floor_id = VFM.vlan_floor_id AND VRM.del_flg = '0' AND VFM.del_flg = '0' AND VFM.vlan_ridge_id = " . string::replaceSql($request['vlan_ridge_id']) . ")";
		}

		if ($request['unallocated_only'] == "1")
		{
			$aryCond[] = "NOT EXISTS (SELECT * FROM sbc_device_drivername WHERE APP.app_id = app_id)";
		}


		$args = array();
		$args['APP_TYPE_ID'] = "3";
		$args['COND'] = "";

		if (count($aryCond) > 0)
		{
			$args['COND'] = " AND " . join(" AND ", $aryCond);
		}

		$sql = $this->getQuery('GET_PRINTER_LIST', $args);

		$aryRet = $this->oDb->getAssoc($sql);

		$ary = array();
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $app_id => $aryData)
			{
				$room_name = $this->getVlanRoomName($aryData['vlan_room_id']);
				$ary[$app_id] = $aryData['app_name'] . "(" . $room_name . ")";
			}
		}

		$options = $this->makeSelectOptions($ary, @$request['app_id']);

		return $options;
	}

	function makeDriverList($driver_name)
	{
		$args = array();
		$args['APP_TYPE_ID'] = "3";

		$sql = $this->getQuery('GET_DRIVERNAME_LIST', $args);

		$aryRet = $this->oDb->getCol($sql);

		$aryList = array();
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $dname)
			{
				$aryList[$dname] = $dname;
			}
		}

		$options = $this->makeSelectOptions($aryList, $driver_name);

		return $options;
	}
}

?>
