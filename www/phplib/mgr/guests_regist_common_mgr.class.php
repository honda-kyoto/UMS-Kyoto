<?php
/**********************************************************
* File         : guests_regist_common_mgr.class.php
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
class guests_regist_common_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getGuestData($guest_id)
	{
		$sql = $this->getQuery('GET_GUEST_DATA', $guest_id);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function deleteGuestData($guest_id)
	{
		// 削除する前にデータを取得
		$aryGuest = $this->getGuestData($guest_id);
		//$mac_addr = $aryGuest['mac_addr'];
		$wireless_id = $aryGuest['wireless_id'];

		// 基本情報
		$args = $this->getSqlArgs();
		$args[0] = $guest_id;

		$sql = $this->getQuery('DELETE_GUEST_HEAD', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// 同期
		$this->deleteAd($wireless_id);

		return true;

	}

	function deleteAd($wireless_id)
	{
		if (!defined("LDAP_HOST_1"))
		{
			return true;
		}

		//接続開始
		$ldap_conn = ldap_connect(LDAP_HOST_1, LDAP_PORT);
		if (!$ldap_conn)
		{
			$ldap_conn = ldap_connect(LDAP_HOST_2, LDAP_PORT);
		}

		if (!$ldap_conn)
		{
			Debug_Trace("接続失敗", 9);
			return false;
		}

		// バインド
		$ldap_bind  = ldap_bind($ldap_conn, LDAP_DN, LDAP_PASS);

		if (!$ldap_bind)
		{
			Debug_Trace("バインド失敗", 9);
			return false;
		}

		// 削除
		$userDn = "CN=".$wireless_id.",".WLESS_ID_DN;

		if (ldap_delete($ldap_conn,$userDn))
		{
			Debug_Trace("削除は成功しました", 952);
		}
		else
		{
			Debug_Trace("削除は失敗しました", 952);
			return false;
		}

		return true;
	}

}
?>
