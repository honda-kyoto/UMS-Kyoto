<?php
/**********************************************************
* File         : apps_add_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/apps_regist_common_mgr.class.php");
require_once("sql/apps_add_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_add_mgr extends apps_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function insertAppData(&$request)
	{
		$request['mac_addr'] = $this->getFlatMacAddr($request['mac_addr']);

		$this->oDb->begin();

		// 区分別にチェック
		list ($wire_kbn, $ip_kbn) = $this->getAppTypeKbns($request['app_type_id']);

		if ($wire_kbn == WIRE_KBN_FREE)
		{
			$wire_kbn = $request['wire_kbn'];
		}

		$request['wireless_id'] = "";
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryUser = $this->getUserData($request['app_user_id']);
			$login_id = $aryUser['login_id'];
			$request['wireless_id'] = $this->createWirelessId($login_id);
		}

		$app_id = $this->oDb->getSequence("app_id_seq");

		$args = $this->getSqlArgs();
		$args['APP_ID'] = $this->sqlItemInteger($app_id);
		$args['APP_TYPE_ID'] = $this->sqlItemInteger($request['app_type_id']);
		$args['APP_NAME'] = $this->sqlItemChar($request['app_name']);
		$args['VLAN_ROOM_ID'] = $this->sqlItemInteger($request['vlan_room_id']);
		$args['VLAN_ID'] = $this->sqlItemInteger(@$request['vlan_id']);
		$args['MAC_ADDR'] = $this->sqlItemChar($request['mac_addr']);
		$args['IP_ADDR'] = $this->sqlItemChar($request['ip_addr']);
		$args['WIRE_KBN'] = $this->sqlItemChar(@$request['wire_kbn']);
		$args['IP_KBN'] = $this->sqlItemChar(@$request['ip_kbn']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['APP_USER_ID'] = $this->sqlItemInteger($request['app_user_id']);
		$args['WIRELESS_ID'] = $this->sqlItemChar($request['wireless_id']);
		$args['USE_SBC'] = $this->sqlItemFlg(@$request['use_sbc']);

		$sql = $this->getQuery('INSERT_APP_HEAD_TBL', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$aryEntList = array();
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryVlan = $this->getTempVlanList();

			if (is_array($aryVlan))
			{
				$args = $this->getSqlArgs();
				$args['APP_ID'] = $this->sqlItemInteger($app_id);
				foreach ($aryVlan AS $vlan_id => $data)
				{
					$args['VLAN_ID'] = $this->sqlItemInteger($vlan_id);
					$args['BUSY_FLG'] = $this->sqlItemFlg($data['busy_flg']);

					$sql = $this->getQuery('INSERT_APP_LIST_TBL', $args);

					$ret = $this->oDb->query($sql);

					if (!$ret)
					{
						Debug_Print($sql);
						$this->oDb->rollback();
						return false;
					}

					$aryEntList[$vlan_id] = $data;
				}
			}
		}

		$make_wireless_id = false;
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			// 無線IDチェック
			$wid = $this->getUserWirelessId($request['app_user_id']);

			if ($wid == "")
			{
				$this->makeWirelessId($request['app_user_id']);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}

				$make_wireless_id = true;
			}
		}

		// エントリーテーブルを更新
		$ret = $this->updateUserEntryFlg($request['app_user_id'], 'app_entry_flg');

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		$this->clearTempVlanList();

		// AD連携
		$aryOrg = array();
		$aryOrgList = array();
		$ret = $this->relationAd($wire_kbn, $aryOrg, $request, $aryOrgList, $aryEntList, $make_wireless_id);

		if (!$ret)
		{
			$this->makeAppAdErrLog($app_id);
		}

		$request['app_id'] = $app_id;

		return true;
	}

}
?>
