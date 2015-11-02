<?php
/**********************************************************
* File         : base_history.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/users_regist_common.class.php");
require_once("mgr/base_history_mgr.class.php");

class base_history extends users_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "利用者編集履歴-基本情報";
		$this->header_file = "base_history_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new base_history_mgr();

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
		$aryJob = $this->oMgr->getJobAry();
		$aryPost = $this->oMgr->getPostAry();

		$aryTmp = $this->oMgr->getUserBaseHistoryData($this->request['user_id'], $this->request['history_no']);

		$aryTmp['sex_name'] = $this->oMgr->getValue('sex', $aryTmp['sex']);
		$aryTmp['joukin_kbn_name'] = $this->oMgr->getValue('joukin_kbn', $aryTmp['joukin_kbn']);
		$aryTmp['belong_name'] = $this->oMgr->getBelongName(&$aryTmp);
		$aryTmp['job_name'] = $aryJob[$aryTmp['job_id']];
		$aryTmp['post_name'] = $aryPost[$aryTmp['post_id']];

		$this->setOutputData($aryTmp);

		$this->request['staff_id_flg'] = $aryTmp['staff_id_flg'];

		// サブ所属
		$aryTmp = $this->oMgr->getSubBelongHistoryData($this->request['user_id'], $this->request['history_no']);

		$belong_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->output['sub_belong_chg_id'][$no] = $aryData['sub_belong_chg_id'];

				$param = array();
				$param['belong_chg_id'] = $aryData['sub_belong_chg_id'];

				$this->output['sub_belong_name'][$no] = $this->oMgr->getBelongName(&$param);

				$belong_cnt++;
			}
		}

		// サブ職種
		$aryTmp = $this->oMgr->getSubJobHistoryData($this->request['user_id'], $this->request['history_no']);

		$job_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->output['sub_job_name'][$no] = $aryJob[$aryData['sub_job_id']];
				$job_cnt++;
			}
		}

		// サブ役職
		$aryTmp = $this->oMgr->getSubPostHistoryData($this->request['user_id'], $this->request['history_no']);

		$post_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->output['sub_post_name'][$no] = $aryJob[$aryData['sub_post_id']];
				$post_cnt++;
			}
		}

		// サブ職員番号
		$aryTmp = $this->oMgr->getSubStaffIdHistoryData($this->request['user_id'], $this->request['history_no']);

		$sid_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->output['sub_staff_id'][$no] = $aryData['sub_staff_id'];
				$sid_cnt++;
			}
		}

		$this->output['sub_data_cnt'] = max(array($belong_cnt, $job_cnt, $post_cnt, $sid_cnt));

		// 履歴データを取得
		$aryHistory = $this->oMgr->getBaseHistoryList($this->request['user_id']);

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
