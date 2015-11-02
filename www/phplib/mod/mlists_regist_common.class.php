<?php
/**********************************************************
* File         : mlists_regist_common.class.php
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

class mlists_regist_common extends common
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
		$param['complete'] = $status;
		if ($status == COMP_STATUS_DIRECT)
		{
			$param['mode'] = 'input';
			$page = 'mlists_edit.php';
			$param['mlist_id'] = $this->request['mlist_id'];
		}
		if ($status == COMP_STATUS_TEMP)
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
			$param['mlist_id'] = $this->request['mlist_id'];
			$param['entry_no'] = $this->request['entry_no'];
			$page = 'mlists_pending.php';
		}
		else if ($status == COMP_STATUS_CANCEL)
		{
			$param['mode'] = 'return';
			$page = 'mlists_search.php';
		}
		else if ($status == COMP_STATUS_AGREE)
		{
			$param['mode'] = 'return';
			$page = 'mlists_req.php';
		}

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

	function setEntryData()
	{
		$aryTmp = $this->oMgr->getMlistData($this->request['mlist_id'], @$this->request['entry_no']);

		$this->setOutputData($aryTmp);

		$this->output['sender_kbn_name'] = $this->oMgr->getValue('sender_kbn', $this->output['sender_kbn']);
		$this->output['mlist_kbn_name'] = $this->oMgr->getValue('mlist_kbn', $this->output['mlist_kbn']);

		$this->request['admin_id'] = $this->oMgr->getAdminId($this->request['mlist_id'], @$this->request['entry_no']);

		$this->setAdminName();
		// 申請データ
		$this->setCommonEntryData();
	}

	function setEditData()
	{
		$aryTmp = $this->oMgr->getMlistData($this->request['mlist_id'], @$this->request['entry_no']);

		$this->setRequestData($aryTmp);

		$this->request['admin_id'] = $this->oMgr->getAdminId($this->request['mlist_id'], @$this->request['entry_no']);

		$this->setAdminName();

		// 承認済みデータの場合最後の申請データを取ってくる
		if (@$this->request['entry_no'] == "")
		{
			$aryAg = $this->oMgr->getAgreedEntryData($this->request['mlist_id']);
			$this->setOutputData($aryAg);

			$this->setCommonEntryData();
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
		$mlist_id = @$this->request['mlist_id'];
		$mailMsg = "";

		// 名称
		if (!$this->oMgr->checkEmpty($this->request['mlist_name']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"名称");
		}
		else if ($this->oMgr->existsMlistName($this->request['mlist_name'], $mlist_id))
		{
			$this->oMgr->setErr('E017',"名称");
		}

		$old_mlist_acc = $this->oMgr->getMlistAcc($mlist_id);

		// アカウント
		if ($old_mlist_acc != "" && $old_mlist_acc == $this->request['mlist_acc'])
		{

		}
		else if (!$this->oMgr->checkEmpty($this->request['mlist_acc']))
		{
			//
			$this->oMgr->setErr('E001',"アカウント");
		}
		else if (!$this->oMgr->checkNcvcMailAcc($this->request['mlist_acc'], "アカウント", &$mailMsg))
		{
			// エラーメッセージをセット
			$this->oMgr->pushError($mailMsg);
		}
		else if ($this->oMgr->existsMlistAcc($this->request['mlist_acc'], $mlist_id))
		{
			$this->oMgr->setErr('E017',"アカウント");
		}
		else if ($this->oMgr->existsMailAcc($this->request['mlist_acc'], -1))
		{
			// メールアカウントとして使用されていないか
			$this->oMgr->setErr('E017',"アカウント");
		}
		else if ($this->oMgr->existsOldMail($this->request['mlist_acc']))
		{
			// エイリアスのアカウントとして使用中
			$this->oMgr->setErr('E017',"アカウント");
		}
		else if ($this->oMgr->existsLoginId($this->request['mlist_acc']))
		{
			// 統合IDとして使用されていないか
			$this->oMgr->setErr('E017',"アカウント");
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
