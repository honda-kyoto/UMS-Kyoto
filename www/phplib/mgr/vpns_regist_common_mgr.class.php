<?php
/**********************************************************
* File         : vpns_regist_common_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vpns_regist_common_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getVpnData($vpn_id)
	{
		$sql = $this->getQuery('GET_VPN_DATA', $vpn_id);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function getAdminList($vpn_id)
	{
		$sql = $this->getQuery('GET_ADMIN_LIST', $vpn_id);

		$ret = $this->oDb->getCol($sql);

		return $ret;
	}

	function getAdminId($vpn_id)
	{
		$sql = $this->getQuery('GET_ADMIN_ID', $vpn_id);

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getAdminName($user_id)
	{
		$sql = $this->getQuery('GET_ADMIN_NAME', $user_id);

		$name = $this->oDb->getOne($sql);

		return $name;
	}

	function existsVpnName($vpn_name, $vpn_id="")
	{
		$args = array();
		$args[0] = $vpn_name;
		$args['COND'] = "";
		if ($vpn_id != "")
		{
			$args['COND'] = " AND vpn_id != " . string::replaceSql($vpn_id);
		}

		$sql = $this->getQuery('EXISTS_VPN_NAME', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;
	}

	function existsGroupName($group_name, $vpn_id="")
	{
		$args = array();
		$args[0] = $group_name;
		$args['COND'] = "";
		if ($vpn_id != "")
		{
			$args['COND'] = " AND vpn_id != " . string::replaceSql($vpn_id);
		}

		$sql = $this->getQuery('EXISTS_GROUP_NAME', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;
	}

	function existsGroupCode($group_code, $vpn_id="")
	{
		$args = array();
		$args[0] = $group_code;
		$args['COND'] = "";
		if ($vpn_id != "")
		{
			$args['COND'] = " AND vpn_id != " . string::replaceSql($vpn_id);
		}

		$sql = $this->getQuery('EXISTS_GROUP_CODE', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;
	}
}
?>
