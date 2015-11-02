<?php
/**********************************************************
* File         : users_search.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/users_search_mgr.class.php");

class users_search extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "利用者検索";
		$this->header_file = "users_search_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new users_search_mgr();
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
		$this->request['search_option'] = '2';

		// 条件を保存
		$this->oMgr->saveSearchData($this->request);
		$this->setListData();
		return 1;
	}

	function runSearch()
	{
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
			$this->aryList = $this->oMgr->getList($this->request, $this->list_max);

			if (is_array($this->aryList))
			{
				foreach ($this->aryList AS $id => $aryData)
				{
					$belong_name = "";
					if ($aryData['belong_dep_name'] != "")
					{
						$belong_name  = $aryData['belong_dep_name'];
					}
					else if ($aryData['belong_sec_name'] != "")
					{
						$belong_name  = $aryData['belong_sec_name'];
					}
					else if ($aryData['belong_chg_name'] != "")
					{
						$belong_name  = $aryData['belong_chg_name'];
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
					$this->aryList[$id]['kanji_name'] = htmlspecialchars($aryData['kanjisei'] . "　" . $aryData['kanjimei']);
					$this->aryList[$id]['kana_name'] = htmlspecialchars($aryData['kanasei'] . "　" . $aryData['kanamei']);
					if ($aryData['over_flg'] == '1')
					{
							$end_date = "利用期間終了";
					}
					else if ($aryData['until_flg'] == '1')
					{
						$end_date = "利用開始前";
					}
					else
					{
						$end_date = $aryData['end_date'];
					}
					$this->aryList[$id]['end_date'] = htmlspecialchars($end_date);
				}
			}
		}
	}

}

?>
