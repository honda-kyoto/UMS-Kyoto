<?php
/**********************************************************
* File         : user_card_issue_list.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/user_card_issue_list_mgr.class.php");

class user_card_issue_list extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "入退室カード一覧";
		$this->script_dir = "view_h_kyoto";
		$this->header_file = "user_card_issue_list_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new user_card_issue_list_mgr();
	}


	/*======================================================
	 * Name         : runInit
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInit()
	{
		$this->request['page'] = 1;
		$this->request['search_option'] = "0";
		$this->request['data_type'] = "0";

		// 条件を保存
		$this->oMgr->saveSearchData($this->request);
		$this->setListData();
		return 1;
	}

	function runSearch()
	{
	file_put_contents("/var/www/phplib/card/log/nyutaishitsu.log",date("Y/m/d H:i:s")."  ユーザ：".$_SESSION['LOGIN_USER_ID']."  更新", FILE_APPEND);
		$this->request['page'] = 1;

		// 条件を保存
		$this->oMgr->saveSearchData($this->request);

		$this->setListData();

		return 1;
	}

	/*======================================================
	 * Name         : runTurn
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runTurn()
	{
		$this->setListData();

		return 1;
	}

	/*======================================================
	 * Name         : runMax
	* IN           :
	* OUT          : 画面番号
	* Discription  : 一覧
	=======================================================*/
	function runMax()
	{
		// セッションに件数を登録
		$this->oMgr->saveListMax($this->request['list_max']);

		$this->setListData();
		return 1;
	}

	/*======================================================
	 * Name         : runReturn
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runReturn()
	{
		// 表示するページを取得
		$this->oMgr->loadPage(&$this->request['page']);

		$this->setListData();

		if ($this->request['complete'] != "")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}

		return 1;
	}

	/*======================================================
	* Name         : runSort
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runSort()
	{
		// オーダー条件を保存
		$this->oMgr->saveOrderData($this->request);

		$this->setListData();

		return 1;
	}

	function runOutputData()
	{
		$this->oMgr->outputCardListData($this->request['checked_id']);
		exit;
	}

	function setListData()
	{
		// セッションから検索条件を取得
		$this->oMgr->loadSearchData(&$this->request);

		if (@$this->request['list_max'] == "")
		{
			$this->request['list_max'] = DEFAULT_LIST_MAX;
		}
		$this->list_max = $this->request['list_max'];

		$this->list_cnt = $this->oMgr->getCount($this->request);

		// ページ番号の精査
		$this->oMgr->checkPageExists($this->list_cnt, $this->list_max, &$this->request['page']);

		$this->cur_page = $this->request['page'];

		// 表示するページを保存
		$this->oMgr->savePage($this->cur_page);

		$this->aryList = array();
		if ($this->list_cnt > 0)
		{
			// 一覧データ取得
			$aryTmpList = $this->oMgr->getList($this->request, $this->list_max);

			if (is_array($aryTmpList))
			{
				foreach ($aryTmpList AS $aryData)
				{
					$user_id = $aryData['user_id'];
					$list_no = $aryData['list_no'];

					$id = $user_id . "_" . $list_no;
					$this->aryList[$id] = $aryData;

					if ($list_no == "0")
					{
						$this->aryList[$id]['data_type'] = "メイン";
					}
					else
					{
						$this->aryList[$id]['data_type'] = "サブ".$list_no;
					}

					$this->aryList[$id]['reissue'] = "-";
					if ($aryData['reissue_flg'] == "1")
					{
						$this->aryList[$id]['reissue'] = "○";
					}

					$this->aryList[$id]['suspend'] = "-";
					if ($aryData['suspend_flg'] == "1")
					{
						$this->aryList[$id]['suspend'] = "○";
					}

					$this->aryList[$id]['delete'] = "-";
					if ($aryData['del_flg'] == "1")
					{
						$this->aryList[$id]['delete'] = "○";
					}

					$this->aryList[$id]['kanji_name'] = htmlspecialchars($aryData['kanjisei']) . "　" . htmlspecialchars($aryData['kanjimei']);
					$belong_name = "";
					if ($aryData['belong_chg_name'] != "")
					{
						$belong_name  = $aryData['belong_chg_name'];
					}
					else if ($aryData['belong_sec_name'] != "")
					{
						$belong_name  = $aryData['belong_sec_name'];
					}
					else if ($aryData['belong_dep_name'] != "")
					{
						$belong_name  = $aryData['belong_dep_name'];
					}
					else if ($aryData['belong_div_name'] != "")
					{
						$belong_name  = $aryData['belong_div_name'];
					}
					else if ($aryData['belong_class_name'] != "")
					{
						$belong_name  = $aryData['belong_class_name'];
					}
					$this->aryList[$id]['belong_name'] = htmlspecialchars($belong_name);

					$this->aryList[$id]['job_name'] = htmlspecialchars($aryData['job_name']);
					$this->aryList[$id]['post_name'] = htmlspecialchars($aryData['post_name']);
				}


			}
		}
	}



}

?>
