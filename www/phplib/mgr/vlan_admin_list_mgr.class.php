<?php
/**********************************************************
* File         : vlan_admin_list_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/vlan_admin_list_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vlan_admin_list_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getVlanAdminData($vlan_id)
	{
		$sql = $this->getQuery('GET_VLAN_ADMIN_DATA', $vlan_id);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function insertData($request)
	{
		$vlan_id = $request['vlan_id'];
		$user_id = $request['user_id'];

		// 存在チェック
		$args = $this->getSqlArgs();
		$args[0] = $vlan_id;
		$args[1] = $user_id;
		$sql = $this->getQuery('EXISTS_VLAN_ADMIN', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		$this->oDb->begin();

		// 新規レコード作成
		$sql = $this->getQuery('INSERT_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$ret = $this->setUserAdminRole($user_id, ROLE_ID_VLAN_ADMIN);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}

	/*
	 * deleteData
	*/
	function deleteData($request)
	{
		$vlan_id = $request['vlan_id'];
		$user_id = $request['list_no'];

		// 削除
		$args = $this->getSqlArgs();
		$args[0] = $vlan_id;
		$args[1] = $user_id;

		$sql = $this->getQuery('DELETE_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

}

?>
