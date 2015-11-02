<?php
/**********************************************************
* File         : vpns_members_regist_common_mgr.class.php
* Authors      : sumio imoto
* Date         : 2013.06.19
* Last Update  : 2013.06.19
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vpns_members_regist_common_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getVpnMembersData($vpn_id, $vpn_user_id)
	{
		$args = array();
		$args[0] = $vpn_id;
		$args[1] = $vpn_user_id;
		
		$sql = $this->getQuery('GET_VPN_MEMBERS_DATA', $args);
		
		$ret = $this->oDb->getRow($sql);

		return $ret;
	}
}
?>
