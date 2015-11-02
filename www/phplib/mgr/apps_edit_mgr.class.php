<?php
/**********************************************************
* File         : apps_edit_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/apps_regist_common_mgr.class.php");
require_once("sql/apps_edit_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class apps_edit_mgr extends apps_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function updateAppData(&$request)
	{
		$app_id = $request['app_id'];
		$request['mac_addr'] = $this->getFlatMacAddr($request['mac_addr']);


		$aryOrg = $this->getAppHead($app_id);


		$this->oDb->begin();

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
		$args['USE_SBC'] = $this->sqlItemFlg(@$request['use_sbc']);

		$sql = $this->getQuery('UPDATE_APP_HEAD_TBL', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// 区分別にチェック
		list ($wire_kbn, $ip_kbn) = $this->getAppTypeKbns($request['app_type_id']);

		if ($wire_kbn == WIRE_KBN_FREE)
		{
			$wire_kbn = $request['wire_kbn'];
		}

		$aryOrgList = array();
		$aryList = array();
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryOrgList = $this->getAppList($app_id);
			$aryOrgListSrc = $this->getAppList($app_id);

			$aryVlan = $this->getTempVlanList();

			if (is_array($aryVlan))
			{
				foreach ($aryVlan AS $vlan_id => $data)
				{
					$aryList[$vlan_id] = $data;

					if (isset($aryOrgList[$vlan_id]))
					{
						// 内容に差異がないか確認
						$is_changed = false;
						if ($aryOrgList[$vlan_id]['busy_flg'] != $data['busy_flg'])
						{
							$is_changed = true;
						}

						// 処理するので消しておく
						unset($aryOrgList[$vlan_id]);

						// 変わってなければスキップ
						if (!$is_changed)
						{
							continue;
						}
					}

					$args = $this->getSqlArgs();
					$args['APP_ID'] = $this->sqlItemInteger($app_id);
					$args['VLAN_ID'] = $this->sqlItemInteger($vlan_id);
					$args['BUSY_FLG'] = $this->sqlItemFlg($data['busy_flg']);

					// 存在チェック
					$sql = $this->getQuery('EXISTS_APP_LIST_TBL', $args);

					$ext_id = $this->oDb->getOne($sql);

					if ($ext_id != "")
					{
						$sql_id = 'UPDATE_APP_LIST_TBL';
					}
					else
					{
						$sql_id = 'INSERT_APP_LIST_TBL';
					}

					$sql = $this->getQuery($sql_id, $args);

					$ret = $this->oDb->query($sql);

					if (!$ret)
					{
						Debug_Print($sql);
						return false;
					}
				}

				// 残ってたら消す
				if (is_array($aryOrgList))
				{
					foreach ($aryOrgList AS $vlan_id => $data)
					{
						$args = $this->getSqlArgs();
						$args['APP_ID'] = $this->sqlItemInteger($app_id);
						$args['VLAN_ID'] = $this->sqlItemInteger($vlan_id);

						$sql = $this->getQuery('DELETE_APP_LIST_TBL', $args);

						$ret = $this->oDb->query($sql);

						if (!$ret)
						{
							Debug_Print($sql);
							return false;
						}
					}
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
		$ret = $this->relationAd($wire_kbn, $aryOrg, $request, $aryOrgListSrc, $aryList, $make_wireless_id);

		if (!$ret)
		{
			$this->makeAppAdErrLog($app_id);
		}

		return true;
	}

}
?>
