<?php
/**********************************************************
* File         : job_mst.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/msts_common.class.php");
require_once("mgr/job_mst_mgr.class.php");

class job_mst extends msts_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "職種管理";
		$this->header_file = "job_mst_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new job_mst_mgr();
	}

	function setListData()
	{
		// モードがlist,completeの場合のみDBから取得
		if ($this->mode == 'init' || $this->mode == 'complete' || $this->mode == 'return')
		{
			$aryTmp = $this->oMgr->getJobAry();
			// リクエスト値にセット
			$this->request['job_name'] = array();
			if (is_array($aryTmp))
			{
				foreach ($aryTmp AS $id => $name)
				{
					$this->request['job_name'][$id] = $name;
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
			if ($this->request['new_job_name'] == "")
			{
				$this->oMgr->setErr('E001', '職種名');
			}
			else if ($this->oMgr->isExist($this->request['new_job_name']))
			{
				$this->oMgr->setErr('E005', '職種名');
			}
		}
		else if ($this->mode == 'update')
		{
			if ($this->request['job_name'][$this->request['job_id']] == "")
			{
				$this->oMgr->setErr('E001', '職種名');
			}
			else if ($this->oMgr->isExist($this->request['job_name'][$this->request['job_id']], $this->request['job_id']))
			{
				$this->oMgr->setErr('E005', '職種名');
			}
		}
		else if ($this->mode == 'editall')
		{
			$aryName = $this->request['job_name'];
			if ($this->request['new_job_name'] != "")
			{
				$aryName['new'] = $this->request['new_job_name'];
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
								$this->oMgr->setErr('E001', '職種名');
								$has_empty_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						else if ($name == $val)
						{
							if (!$has_double_error)
							{
								$this->oMgr->setErr('E005', '職種名');
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
