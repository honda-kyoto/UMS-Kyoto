<?php
/**********************************************************
* File         : ncvc_history.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/users_regist_common.class.php");
require_once("mgr/ncvc_history_mgr.class.php");

class ncvc_history extends users_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "利用者編集履歴-" . MAIN_SYSTEM_NAME;
		$this->header_file = "ncvc_history_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new ncvc_history_mgr();

	}


	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInit()
	{
		$this->request['history_no'] = $this->oMgr->getLastHistoryNo($this->request['user_id']);

		$this->setViewData();

		return 1;
	}

	/*======================================================
	 * Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runView()
	{
		$this->setViewData();

		return 1;
	}

	function setViewData()
	{
		// NCVC情報取得
		$aryTmp = $this->oMgr->getUserNcvcHistoryData($this->request['user_id'], $this->request['history_no']);

		$this->request['mail_disused_flg'] = $aryTmp['mail_disused_flg'];

		$this->setOutputData($aryTmp);

		// 権限データ
		$aryTmp = $this->oMgr->getRoleHistoryData($this->request['user_id'], $this->request['history_no']);

		$this->request['user_type_id'] = $aryTmp['user_type_id'];
		$this->request['user_role_id'] = $aryTmp['user_role_id'];


		// 履歴データを取得
		$aryHistory = $this->oMgr->getNcvcHistoryList($this->request['user_id']);

		$cnt = count($aryHistory);

		if (is_array($aryHistory))
		{
			foreach ($aryHistory AS $history_no => $aryData)
			{
				$aryData['history_user_name'] = "　";
				if ($aryData['history_user_id'] != "")
				{
					$aryData['history_user_name'] = $this->oMgr->getUserName($aryData['history_user_id']);
				}
				$aryData['list_no'] = $cnt--;

				$this->setOutputData($aryData, $history_no);
			}
		}

	}


}

?>
