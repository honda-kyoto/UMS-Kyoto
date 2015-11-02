<?php
/**********************************************************
* File         : vpns_regist_common.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");

class vpns_regist_common extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

	}

	function postComplete($status)
	{
		$param = array();
		$param['complete'] = "1";
		$param['mode'] = 'input';
		$param['vpn_id'] = $this->request['vpn_id'];
		$page = 'vpns_edit.php';

		$this->oMgr->postTo($page, $param);
	}

	function setAdminName()
	{
		if (is_array($this->request['admin_id']))
		{
			foreach ($this->request['admin_id'] AS $key => $val)
			{
				$this->output['admin_name'][$key] = $this->oMgr->getAdminName($val);
			}
		}
	}

	function setEditData()
	{
		$aryTmp = $this->oMgr->getVpnData($this->request['vpn_id']);

		$this->setRequestData($aryTmp);

		$this->request['admin_id'] = $this->oMgr->getAdminId($this->request['vpn_id']);

		$this->setAdminName();
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		$vpn_id = @$this->request['vpn_id'];

		// 名称
		if (!$this->oMgr->checkEmpty($this->request['vpn_name']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"ネットワークポリシー名");
		}
		else if ($this->oMgr->existsVpnName($this->request['vpn_name'], $vpn_id))
		{
			$this->oMgr->setErr('E017',"ネットワークポリシー名");
		}

		// グループ名
		if (!$this->oMgr->checkEmpty($this->request['group_name']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"グループ／プロジェクト名");
		}
		else if ($this->oMgr->existsGroupName($this->request['group_name'], $vpn_id))
		{
			$this->oMgr->setErr('E017',"グループ／プロジェクト名");
		}

		// グループコード
		$this->request['group_code'] = string::zen2han($this->request['group_code']);
		if (!$this->oMgr->checkEmpty($this->request['group_code']))
		{
			//
			$this->oMgr->setErr('E001',"グループコード");
		}
		else if (!ereg("^[A-Z]{2}$", $this->request['group_code']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "グループコード";
			$param[1] = "英字大文字2文字";
			$this->oMgr->setErr('E004', $param);
		}
		else if ($this->oMgr->existsGroupCode($this->request['group_code'], $vpn_id))
		{
			$this->oMgr->setErr('E017',"グループコード");
		}

		// 管理者
		if (!is_array($this->request['admin_id']) || count($this->request['admin_id']) == 0)
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"管理者");
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
}

?>
