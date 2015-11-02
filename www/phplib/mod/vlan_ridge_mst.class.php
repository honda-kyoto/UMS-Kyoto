<?php
/**********************************************************
* File         : vlan_ridge_mst.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/msts_common.class.php");
require_once("mgr/vlan_ridge_mst_mgr.class.php");

class vlan_ridge_mst extends msts_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VLAN管理 -棟屋-";
		$this->header_file = "vlan_ridge_mst_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vlan_ridge_mst_mgr();
	}

	function setListData()
	{
		// モードがlist,completeの場合のみDBから取得
		if ($this->mode == 'init' || $this->mode == 'complete' || $this->mode == 'return')
		{
			$aryTmp = $this->oMgr->getVlanRidgeAry();
			// リクエスト値にセット
			$this->request['vlan_ridge_name'] = array();
			if (is_array($aryTmp))
			{
				foreach ($aryTmp AS $id => $name)
				{
					$this->request['vlan_ridge_name'][$id] = $name;
				}
			}
		}
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		//
		if ($this->mode == 'insert')
		{
			if ($this->request['new_vlan_ridge_name'] == "")
			{
				$this->oMgr->setErr('E001', '棟屋名');
			}
			else if ($this->oMgr->isExist($this->request['new_vlan_ridge_name']))
			{
				$this->oMgr->setErr('E005', '棟屋名');
			}
		}
		else if ($this->mode == 'update')
		{
			if ($this->request['vlan_ridge_name'][$this->request['vlan_ridge_id']] == "")
			{
				$this->oMgr->setErr('E001', '棟屋名');
			}
			else if ($this->oMgr->isExist($this->request['vlan_ridge_name'][$this->request['vlan_ridge_id']], $this->request['vlan_ridge_id']))
			{
				$this->oMgr->setErr('E005', '棟屋名');
			}
		}
		else if ($this->mode == 'editall')
		{
			$aryName = $this->request['vlan_ridge_name'];
			if ($this->request['new_vlan_ridge_name'] != "")
			{
				$aryName['new'] = $this->request['new_vlan_ridge_name'];
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
								$this->oMgr->setErr('E001', '棟屋名');
								$has_empty_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						else if ($name == $val)
						{
							if (!$has_double_error)
							{
								$this->oMgr->setErr('E005', '棟屋名');
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
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $param);
	}

}

?>
