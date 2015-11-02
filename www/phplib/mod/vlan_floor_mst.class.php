<?php
/**********************************************************
* File         : vlan_floor_mst.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/msts_common.class.php");
require_once("mgr/vlan_floor_mst_mgr.class.php");

class vlan_floor_mst extends msts_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VLAN管理 -階-";
		$this->header_file = "vlan_floor_mst_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vlan_floor_mst_mgr();
	}

	function setListData()
	{
		$vlan_ridge_id = $this->request['vlan_ridge_id'];

		// モードがlist,completeの場合のみDBから取得
		if ($this->mode == 'init' || $this->mode == 'complete' || $this->mode == 'return')
		{
			$aryTmp = $this->oMgr->getVlanFloorAry($vlan_ridge_id);
			// リクエスト値にセット
			$this->request['vlan_floor_name'] = array();
			if (is_array($aryTmp))
			{
				foreach ($aryTmp AS $id => $name)
				{
					$this->request['vlan_floor_name'][$id] = $name;
				}
			}
		}

		// ヘッダ情報
		$aryRidge = $this->oMgr->getVlanRidgeAry();
		$this->output['vlan_ridge_name'] = $aryRidge[$vlan_ridge_id];
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		$vlan_ridge_id = $this->request['vlan_ridge_id'];

		//
		if ($this->mode == 'insert')
		{
			if ($this->request['new_vlan_floor_name'] == "")
			{
				$this->oMgr->setErr('E001', '階名');
			}
			else if ($this->oMgr->isExist($vlan_ridge_id, $this->request['new_vlan_floor_name']))
			{
				$this->oMgr->setErr('E005', '階名');
			}
		}
		else if ($this->mode == 'update')
		{
			if ($this->request['vlan_floor_name'][$this->request['vlan_floor_id']] == "")
			{
				$this->oMgr->setErr('E001', '階名');
			}
			else if ($this->oMgr->isExist($vlan_ridge_id, $this->request['vlan_floor_name'][$this->request['vlan_floor_id']], $this->request['vlan_floor_id']))
			{
				$this->oMgr->setErr('E005', '階名');
			}
		}
		else if ($this->mode == 'editall')
		{
			$aryName = $this->request['vlan_floor_name'];
			if ($this->request['new_vlan_floor_name'] != "")
			{
				$aryName['new'] = $this->request['new_vlan_floor_name'];
			}
			$has_empty_error = false;
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
								$this->oMgr->setErr('E001', '階名');
								$has_empty_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						else if ($name == $val)
						{
							if (!$has_double_error)
							{
								$this->oMgr->setErr('E005', '階名');
								$has_double_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						if ($has_empty_error && $has_double_error)
						{
							break 2;
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
		$param['vlan_ridge_id'] = $this->request['vlan_ridge_id'];
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $param);
	}

}

?>
