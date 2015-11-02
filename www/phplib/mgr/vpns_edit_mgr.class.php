<?php
/**********************************************************
* File         : vpns_edit_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/vpns_regist_common_mgr.class.php");
require_once("sql/vpns_edit_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vpns_edit_mgr extends vpns_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function updateVpnData($request)
	{
		// ID
		$vpn_id = $request['vpn_id'];

		$aryOld = $this->getVpnData($vpn_id);

		$this->oDb->begin();

		// 基本情報
		$args = $this->getSqlArgs();
		$args['VPN_ID'] = $this->sqlItemInteger($vpn_id);
		$args['VPN_KBN'] = $this->sqlItemChar($request['vpn_kbn']);
		$args['VPN_NAME'] = $this->sqlItemChar($request['vpn_name']);
		$args['GROUP_NAME'] = $this->sqlItemChar($request['group_name']);
		$args['GROUP_CODE'] = $this->sqlItemChar($request['group_code']);
		$args['USAGE'] = $this->sqlItemChar($request['usage']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);

		$sql = $this->getQuery('UPDATE_VPN_HEAD', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		//
		// 管理者データ
		//
		// 一旦論理削除
		$args = $this->getSqlArgs();
		$args['VPN_ID'] = $this->sqlItemInteger($vpn_id);
		$args['USER_ID'] = $this->sqlItemInteger($user_id);
		
		$sql = $this->getQuery('DELETE_VPN_ADMIN_DATA', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}
		
		if (is_array($request['admin_id']))
		{
			$args = $this->getSqlArgs();
			$args['VPN_ID'] = $this->sqlItemInteger($vpn_id);

			foreach ($request['admin_id'] AS $no => $user_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['USER_ID'] = $this->sqlItemInteger($user_id);

				// 存在チェック
				$sql = $this->getQuery('EXISTS_VPN_ADMIN', $args);

				$list_no = $this->oDb->getOne($sql);

				if ($list_no == "")
				{
					$sql_id = "INSERT_VPN_ADMIN";
				}
				else
				{
					$sql_id = "UPDATE_VPN_ADMIN";
				}

				$sql = $this->getQuery($sql_id, $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}

				// 管理者ロールを割り当てる
				$ret = $this->setUserAdminRole($user_id, ROLE_ID_VPN_ADMIN);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}

			}
		}

		// 一旦トランザクション終了
		$this->oDb->end();

		return true;
	}
}
?>
