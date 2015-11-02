<?php
/**********************************************************
* File         : card_search.class.php
* Authors      : kazuyoshi shibuta
* Date         : 2015.06.10
* Last Update  : 2015.06.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/card_search_mgr.class.php");

class card_search extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "入退室管理システム登録情報検索";
		$this->header_file = "card_search_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new card_search_mgr();
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
					$this->aryList[$id]['card_name'] = htmlspecialchars($aryData['card_name']);
					$this->aryList[$id]['card_name_kana'] = htmlspecialchars($aryData['card_name_kana']);
					$this->aryList[$id]['card_uid'] = htmlspecialchars($aryData['card_uid']);
					$this->aryList[$id]['card_biko1'] = htmlspecialchars($aryData['card_biko1']);
					$this->aryList[$id]['card_biko2'] = htmlspecialchars($aryData['card_biko2']);
					$this->aryList[$id]['card_biko3'] = htmlspecialchars($aryData['card_biko3']);

				}
			}
		}
	}
}

?>
