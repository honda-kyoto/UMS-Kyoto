<?php
/**********************************************************
* File         : vlan_mst.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/msts_common.class.php");
require_once("mgr/vlan_mst_mgr.class.php");

class vlan_mst extends msts_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VLAN管理 -VLAN-";
		$this->header_file = "vlan_mst_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vlan_mst_mgr();
	}

	function setListData()
	{
		$vlan_room_id = $this->request['vlan_room_id'];

		// モードがlist,completeの場合のみDBから取得
		if ($this->mode == 'init' || $this->mode == 'complete' || $this->mode == 'return')
		{
			$aryTmp = $this->oMgr->getVlanList($vlan_room_id);
			// リクエスト値にセット
			$this->request['vlan_name'] = array();
			if (is_array($aryTmp))
			{
				foreach ($aryTmp AS $id => $data)
				{
					$this->request['vlan_name'][$id] = $data['vlan_name'];
					$this->request['admin_name'][$id] = $data['admin_name'];

					$aryAdmin = $this->oMgr->getVlanAdminList($id);

					$strAdminName = "";
					if (is_array($aryAdmin))
					{
						$sep = "";
						foreach ($aryAdmin AS $admin_user_name)
						{
							$strAdminName .= $sep . $admin_user_name;
							$sep = "\n";
						}
					}
					$this->output['vlan_admin_list'][$id] = $strAdminName;
				}
			}
		}

		// ヘッダ情報
		$aryParent = array();
		$this->output['vlan_room_name'] = $this->oMgr->getVlanRoomName($vlan_room_id, &$aryParent);
		$this->request['vlan_floor_id'] = $aryParent['vlan_floor_id'];

	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		$vlan_room_id = $this->request['vlan_room_id'];

		//
		if ($this->mode == 'insert')
		{
			if ($this->request['new_vlan_name'] == "")
			{
				$this->oMgr->setErr('E001', 'VLAN名');
			}
			else if ($this->oMgr->isExist($vlan_room_id, $this->request['new_vlan_name']))
			{
				$this->oMgr->setErr('E005', 'VLAN名');
			}
			else if (!string::checkNumber($this->request['new_vlan_name'], 3))
			{
				$param = array();
				$param[0] = "VLAN名";
				$param[1] = "半角数字3桁";
				$this->oMgr->setErr('E004', $param);
			}

			if ($this->request['new_admin_name'] == "")
			{
				$this->oMgr->setErr('E001', '管理者名');
			}
		}
		else if ($this->mode == 'update')
		{
			if ($this->request['vlan_name'][$this->request['vlan_id']] == "")
			{
				$this->oMgr->setErr('E001', 'VLAN名');
			}
			else if ($this->oMgr->isExist($vlan_room_id, $this->request['vlan_name'][$this->request['vlan_id']], $this->request['vlan_id']))
			{
				$this->oMgr->setErr('E005', 'VLAN名');
			}
			else if (!string::checkNumber($this->request['vlan_name'][$this->request['vlan_id']], 3))
			{
				$param = array();
				$param[0] = "VLAN名";
				$param[1] = "半角数字3桁";
				$this->oMgr->setErr('E004', $param);
			}

			if ($this->request['admin_name'][$this->request['vlan_id']] == "")
			{
				$this->oMgr->setErr('E001', '管理者名');
			}
		}
		else if ($this->mode == 'editall')
		{
			$aryName = $this->request['vlan_name'];
			$aryAdmin = $this->request['admin_name'];
			if ($this->request['new_vlan_name'] != "")
			{
				$aryName['new'] = $this->request['new_vlan_name'];
				$aryName['new'] = $this->request['new_admin_name'];
			}
			$has_empty_error = false;
			$has_admin_error = false;
			$has_format_error = false;
			$has_double_error = false;
			if (is_array($aryName))
			{
				foreach ($aryName AS $id => $name)
				{
					foreach ($aryName AS $key => $val)
					{
						if ($id == $key)
						{
							continue;
						}
						if ($name == "")
						{
							if (!$has_empty_error)
							{
								$this->oMgr->setErr('E001', 'VLAN名');
								$has_empty_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						else if ($name == $val)
						{
							if (!$has_double_error)
							{
								$this->oMgr->setErr('E005', 'VLAN名');
								$has_double_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						else if (!string::checkNumber($name, 3))
						{
							if (!$has_format_error)
							{
								$param = array();
								$param[0] = "VLAN名";
								$param[1] = "半角数字3桁";
								$this->oMgr->setErr('E004', $param);
								$has_format_error = true;
							}
						}

						if ($has_empty_error && $has_double_error && $has_format_error)
						{
							break 2;
						}
					}

					if ($aryAdmin[$id] == "")
					{
						if (!$has_admin_error)
						{
							$this->oMgr->setErr('E001', '管理者名');
							$has_admin_error = true;
							// ↑同じメッセージは１回でよい
						}
					}
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


	function postComplete()
	{
		$param = array();
		$param['mode'] = "complete";
		$param['vlan_room_id'] = $this->request['vlan_room_id'];
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $param);
	}

}

?>
