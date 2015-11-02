<?php
/**********************************************************
* File         : apps_regist_common.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");

define ("COMP_STATUS_DIRECT", "1");
define ("COMP_STATUS_ENTRY", "2");
define ("COMP_STATUS_CANCEL", "3");
define ("COMP_STATUS_AGREE", "5");
define ("COMP_STATUS_TEMP", "9");
define ("COMP_STATUS_DETAIL", "6");

class apps_regist_common extends common
{
	var $wire_kbn;
	var $ip_kbn;
	var $is_wire_free = false;
	var $is_ip_free = false;
	var $is_agree_mode = false;

	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

	}

	function runJoin()
	{
		$ret = $this->checkWirelessdata();

		if (!$ret)
		{
			$this->setInputData();

			return 1;
		}

		$ret = $this->oMgr->joinAppList($this->request['wl_vlan_id'], @$this->request['app_user_id']);

		$this->request['wl_vlan_ridge_id'] = "";
		$this->request['wl_vlan_floor_id'] = "";
		$this->request['wl_vlan_room_id'] = "";
		$this->request['wl_vlan_id'] = "";

		$this->postComplete(COMP_STATUS_TEMP);
	}

	function runDefect()
	{
		$ret = $this->oMgr->defectAppList($this->request['target_vlan_id']);

		// リダイレクト
		$this->postComplete(COMP_STATUS_TEMP);
	}

	function postComplete($status)
	{
		$param = array();
		$param['complete'] = $status;
		if ($status == COMP_STATUS_DIRECT)
		{
			$param['mode'] = 'input';
			$page = 'apps_edit.php';
			$param['app_id'] = $this->request['app_id'];
		}
		else if ($status == COMP_STATUS_DETAIL)
		{
			$param['mode'] = 'view';
			$page = 'apps_detail.php';
			$param['app_id'] = $this->request['app_id'];
		}
		else if ($status == COMP_STATUS_TEMP)
		{
			$param['mode'] = 'reload';
			if (is_array($this->request))
			{
				foreach ($this->request AS $key => $val)
				{
					switch ($key)
					{
						case 'mode':
						case 'complete':
							break;
						default:
							$param[$key] = $val;
							break;
					}
				}
			}
			$page = $_SERVER['SCRIPT_NAME'];
		}
		else if ($status == COMP_STATUS_ENTRY)
		{
			$param['mode'] = 'view';
			$param['app_id'] = $this->request['app_id'];
			$param['entry_no'] = $this->request['entry_no'];
			$page = 'apps_pending.php';
		}
		else if ($status == COMP_STATUS_CANCEL)
		{
			$param['mode'] = 'return';
			$page = 'apps_search.php';
		}
		else if ($status == COMP_STATUS_AGREE)
		{
			$param['mode'] = 'return';
			$page = 'apps_req.php';
		}

		$this->oMgr->postTo($page, $param);
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		$app_id = @$this->request['app_id'];

		// 機器種別
		if (!$this->oMgr->checkEmpty($this->request['app_type_id']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"機器種別");

			// 続きのチェックはしない
			// エラー発生
			$this->errMsg = $this->oMgr->getErrMsg();
			return false;
		}

		// 名称
		if (!$this->oMgr->checkEmpty($this->request['app_name']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"名称");
		}

		// 区分別にチェック
		list ($wire_kbn, $ip_kbn) = $this->oMgr->getAppTypeKbns($this->request['app_type_id']);

		if ($wire_kbn == WIRE_KBN_FREE)
		{
			$wire_kbn = $this->request['wire_kbn'];
		}

		if ($ip_kbn == IP_KBN_FREE)
		{
			$ip_kbn = $this->request['ip_kbn'];
		}

		// 設置場所
		if ($wire_kbn == WIRE_KBN_WIRED)
		{
			if (!$this->oMgr->checkEmpty($this->request['vlan_id']))
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E007',"VLAN管理者");
			}
		}
		else if ($wire_kbn == WIRE_KBN_WLESS)
		{
			if (!$this->oMgr->checkEmpty($this->request['vlan_room_id']))
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E007',"部屋");
			}
		}

		// MACアドレス
		if (!$this->oMgr->checkEmpty($this->request['mac_addr']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"MACアドレス");
		}
		else if (!string::checkMacAddr($this->request['mac_addr']))
		{
			$this->oMgr->setErr('E006', 'MACアドレス');
		}
		else if ($this->oMgr->existsMacAddr($this->request['mac_addr'], $app_id))
		{
			// 新規申請で有線の場合
			$is_mac_err = true;
			if ($wire_kbn == WIRE_KBN_WIRED && $this->oMgr->isNormalUser() && $app_id == "")
			{
				$aryOrg = array();
				if ($this->request['mac_addr'] != "" && $this->request['vlan_id'] != "")
				{
					if ($this->oMgr->isUnknownUsersApp($this->request['mac_addr'], $this->request['vlan_id'], &$aryOrg))
					{
						$is_mac_err = false;
						$this->request['unknown_data'] = $aryOrg;
					}
				}
			}

			// エラー
			if ($is_mac_err)
			{
				$this->oMgr->setErr('E017',"MACアドレス");
			}
		}

		// システム管理者の場合
		if ($this->oMgr->isAdminUser())
		{
			if ($ip_kbn == IP_KBN_FIXD)
			{
				if (!$this->oMgr->checkEmpty($this->request['ip_addr']))
				{
					// エラーメッセージをセット
					$this->oMgr->setErr('E001',"IPアドレス");
				}
				else if (!string::checkIpAddr($this->request['ip_addr']))
				{
					$this->oMgr->setErr('E006', 'IPアドレス');
				}
			}

			if (!$this->oMgr->checkEmpty($this->request['app_user_id']))
			{
				$this->oMgr->setErr('E007', '機器利用者');
			}
		}

		// 無線の場合
		if ($wire_kbn == WIRE_KBN_WLESS)
		{
			$aryVlan = $this->oMgr->getTempVlanList();

			if (count($aryVlan) == 0)
			{
				// 利用中VLANがない場合新規VLAN必須
				if (!$this->oMgr->checkEmpty($this->request['wl_vlan_id']))
				{
					$this->oMgr->setErr('E007', '無線VLAN');
				}
			}
			else
			{
				// 重複チェック
				if ($this->oMgr->checkEmpty($this->request['wl_vlan_id']))
				{
					if (in_array($this->request['wl_vlan_id'], $aryVlan))
					{
						$this->oMgr->setErr('E017',"無線VLAN");
					}
				}

			}
		}

		// プリンタの場合
		$this->is_vdi_app = $this->oMgr->checkVdiApp($this->request['app_type_id']);
		if ($this->is_vdi_app && ($this->isVlanAdminUser() || $this->isAdminUser()))
		{
			if ($this->request['use_sbc'] == "")
			{
				if ($this->oMgr->checkDeviceUsed($this->request['app_id']))
				{
					$this->oMgr->setErr('D002');
				}
			}
		}

		// エラーなし
		if (sizeof($this->oMgr->aryErrMsg) == 0)
		{
			return true;
		}

		// エラー発生
		$this->errMsg = $this->oMgr->getErrMsg();
		return false;
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkWirelessdata()
	{
		if (!$this->oMgr->checkEmpty($this->request['wl_vlan_id']))
		{
			$this->oMgr->setErr('E007',"VLAN管理者");
		}
		else
		{
			$aryVlan = $this->oMgr->getTempVlanList();
			if (in_array($this->request['wl_vlan_id'], $aryVlan))
			{
				$this->oMgr->setErr('E017',"VLAN");
			}
		}

		// エラーなし
		if (sizeof($this->oMgr->aryErrMsg) == 0)
		{
			return true;
		}

		// エラー発生
		$this->errMsg = $this->oMgr->getErrMsg();
		return false;
	}

	function setEntryData()
	{
		$aryTmp = $this->oMgr->getAppHead($this->request['app_id'], @$this->request['entry_no']);

		$this->setRequestData($aryTmp);

		list ($this->wire_kbn, $this->ip_kbn) = $this->oMgr->getAppTypeKbns($this->request['app_type_id']);

		if ($this->wire_kbn == WIRE_KBN_FREE)
		{
			$this->wire_kbn = @$this->request['wire_kbn'];
			$this->is_wire_free = true;
		}

		if ($this->ip_kbn == IP_KBN_FREE)
		{
			$this->ip_kbn = @$this->request['ip_kbn'];
			$this->is_ip_free = true;
		}

		if ($this->wire_kbn == WIRE_KBN_WIRED)
		{
			$this->oMgr->getVlanAreaName($this->request['vlan_id'], &$this->request);
		}
		else
		{
			$this->oMgr->getVlanRoomName($this->request['vlan_room_id'], &$this->request);
		}

		$mac_addr = substr($this->request['mac_addr'], 0, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->request['mac_addr'], 2, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->request['mac_addr'], 4, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->request['mac_addr'], 6, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->request['mac_addr'], 8, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->request['mac_addr'], 10, 2);

		$this->request['mac_addr'] = $mac_addr;

		$aryTmp = $this->oMgr->getAppList($this->request['app_id'], @$this->request['entry_no']);

		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $vlan_id => $data)
			{
				$aryVlanIds = array();
				$data['vlan_area_name'] = $this->oMgr->getVlanAreaName($vlan_id, &$aryVlanIds);
				if ($this->mode == 'retry' && $data['agree_flg'] == '0')
				{
					$this->request['wl_vlan_ridge_id'] = $aryVlanIds['vlan_ridge_id'];
					$this->request['wl_vlan_floor_id'] = $aryVlanIds['vlan_floor_id'];
					$this->request['wl_vlan_room_id'] = $aryVlanIds['vlan_room_id'];
					$this->request['wl_vlan_id'] = $vlan_id;
					$this->oMgr->defectAppList($vlan_id);
					continue;
				}
				$this->output['vlan_area_name'][$vlan_id] = $data['vlan_area_name'];
				$this->output['agree_flg'][$vlan_id] = $data['agree_flg'];
				$this->output['busy_flg'][$vlan_id] = $data['busy_flg'];

				$this->oMgr->setTempVlanList($vlan_id, $data);
			}
		}

		// 承認済みデータの場合最後の申請データを取ってくる
		if (@$this->request['entry_no'] == "")
		{
			$aryAg = $this->oMgr->getAgreedEntryData($this->request['app_id']);
			if (is_array($aryAg))
			{
				$this->setOutputData($aryAg);
				$this->setCommonEntryData();
			}
		}

		// 仮想環境で使用できる種別かチェック
		$this->is_vdi_app = $this->oMgr->checkVdiApp($this->request['app_type_id']);

	}

	function setEditData()
	{
		$aryTmp = $this->oMgr->getAppHead($this->request['app_id'], @$this->request['entry_no']);

		$this->setOutputData($aryTmp);
		$this->request['ip_addr'] = $this->output['ip_addr'];
		$this->request['use_sbc'] = $this->output['use_sbc'];


		list ($this->wire_kbn, $this->ip_kbn) = $this->oMgr->getAppTypeKbns($this->output['app_type_id']);

		if ($this->wire_kbn == WIRE_KBN_FREE)
		{
			$this->wire_kbn = @$this->output['wire_kbn'];
			$this->output['wire_kbn_name'] = $this->oMgr->getValue('wire_kbn', $this->output['wire_kbn']);
			$this->is_wire_free = true;
		}

		if ($this->ip_kbn == IP_KBN_FREE)
		{
			$this->ip_kbn = @$this->output['ip_kbn'];
			$this->output['ip_kbn_name'] = $this->oMgr->getValue('ip_kbn', $this->output['ip_kbn']);
			$this->is_ip_free = true;
		}

		if ($this->wire_kbn == WIRE_KBN_WLESS)
		{
			$vlan_name = $this->oMgr->getVlanRoomName($this->output['vlan_room_id']);
		}
		else
		{
			$vlan_name = $this->oMgr->getVlanAreaName($this->output['vlan_id']);
		}
		$this->output['vlan_name'] = $vlan_name;

		if ($this->ip_kbn == IP_KBN_DHCP)
		{
			$ip_addr = "DHCP";
		}
		else
		{
			if ($this->output['ip_addr'] == "")
			{
				$ip_addr = "未割当";
			}
			else
			{
				$ip_addr = $this->output['ip_addr'];
			}
		}
		$this->output['ip_addr'] = $ip_addr;

		$mac_addr = substr($this->output['mac_addr'], 0, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->output['mac_addr'], 2, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->output['mac_addr'], 4, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->output['mac_addr'], 6, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->output['mac_addr'], 8, 2);
		$mac_addr .= ":";
		$mac_addr .= substr($this->output['mac_addr'], 10, 2);

		$this->output['mac_addr'] = $mac_addr;

		// 機器利用ユーザかどうか
		$this->output['is_other_user'] = false;

		// 機器利用ユーザ
		if ($this->output['app_user_id'] != "" && $this->output['app_user_id'] != $this->oMgr->getSessionData('LOGIN_USER_ID'))
		{
			$this->output['is_other_user'] = true;
		}

		$aryTmp = $this->oMgr->getAppList($this->request['app_id'], @$this->request['entry_no']);

		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $id => $data)
			{
				$this->output['vlan_area_name'][$id] = $this->oMgr->getVlanAreaName($id);
				$this->output['agree_flg'][$id] = $data['agree_flg'];
				$this->output['busy_flg'][$id] = $data['busy_flg'];
				if ($this->is_agree_mode)
				{
					$this->output['is_vlan_admin'][$id] = $this->oMgr->isVlanAdmin($id);
				}
			}
		}

		// 仮想環境で使用できる種別かチェック
		$this->is_vdi_app = $this->oMgr->checkVdiApp($this->output['app_type_id']);

		$this->is_vdi_only_edit_mode = false;
		if (!$this->is_agree_mode && $this->is_vdi_app && $this->isVlanAdminUser())
		{
			$this->is_vdi_only_edit_mode = true;
		}

		// 申請データ
		$this->setCommonEntryData();
	}

	function setInputData()
	{
		if (@$this->request['app_type_id'] == "")
		{
			return false;
		}

		list ($this->wire_kbn, $this->ip_kbn) = $this->oMgr->getAppTypeKbns($this->request['app_type_id']);

		if ($this->wire_kbn == WIRE_KBN_FREE)
		{
			$this->wire_kbn = @$this->request['wire_kbn'];
			$this->is_wire_free = true;
		}

		if ($this->ip_kbn == IP_KBN_FREE)
		{
			$this->ip_kbn = @$this->request['ip_kbn'];
			$this->is_ip_free = true;
		}

		$aryVlan = $this->oMgr->getTempVlanList();

		if (is_array($aryVlan))
		{
			foreach ($aryVlan AS $id => $data)
			{
				$this->output['vlan_area_name'][$id] = $data['vlan_area_name'];
				$this->output['agree_flg'][$id] = $data['agree_flg'];
				$this->output['busy_flg'][$id] = $data['busy_flg'];
			}
		}

		// 仮想環境で使用できる種別かチェック
		$this->is_vdi_app = $this->oMgr->checkVdiApp($this->request['app_type_id']);

	}
}

?>
