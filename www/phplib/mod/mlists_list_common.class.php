<?php
/**********************************************************
* File         : mlists_list_common.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");

class mlists_list_common extends common
{
	var $req_only = false;

	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);
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
					$this->aryList[$id]['mlist_name'] = htmlspecialchars($aryData['mlist_name']);
					$this->aryList[$id]['mlist_acc'] = htmlspecialchars($aryData['mlist_acc']);
					$kbn_name = $this->oMgr->getValue('mlist_kbn', $aryData['mlist_kbn']);
					$this->aryList[$id]['kbn_name'] = htmlspecialchars($kbn_name);
					$aryAdmin = $this->oMgr->getAdminList($id, $aryData['entry_no']);

					$strAdminName = "";
					if (is_array($aryAdmin))
					{
						$sep = "";
						foreach ($aryAdmin AS $admin_name)
						{
							$strAdminName .= $sep . htmlspecialchars($admin_name);
							$sep = "<br />";
						}
					}
					$this->aryList[$id]['admin_name'] = $strAdminName;

					// 申請ステータス
					if ($aryData['entry_kbn'] == "" && $aryData['entry_status'] == "")
					{
						// これは実データ
						$this->aryList[$id]['entry_bkn_status'] = "&nbsp;";
						$this->aryList[$id]['is_pending_data'] = false;
					}
					else
					{
						$kbn_status = $aryData['entry_kbn'] . "_" . $aryData['entry_status'];

						$entry_kbn_status = $this->oMgr->getValue('entry_kbn_status', $kbn_status);
						$this->aryList[$id]['entry_kbn_status'] = htmlspecialchars($entry_kbn_status);
						$this->aryList[$id]['is_pending_data'] = true;
					}
				}
			}
		}
	}

}

?>
