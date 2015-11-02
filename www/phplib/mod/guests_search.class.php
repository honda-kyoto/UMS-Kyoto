<?php
/**********************************************************
* File         : guests_search.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/guests_search_mgr.class.php");

class guests_search extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "ゲスト検索";
		$this->header_file = "guests_search_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new guests_search_mgr();
	}

	/*======================================================
	 * Name         : runDelete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runDelete()
	{
		$ret = $this->oMgr->deleteGuestData($this->request['guest_id']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param = array();
		$param['mode'] = 'return';
		$param['complete'] = "1";
		$this->oMgr->postTo('guests_search.php', $param);
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
	
	/*======================================================
	* Name         : runPrePrint
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runPrePrint()
	{
		$guest_id = $this->request['guest_id'];

		$_SESSION['print_guest_id'] = $guest_id;
		
		echo "1";
	}

	/*======================================================
	* Name         : runPrint
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runPrint()
	{
		$this->setPrintData();

		$print_time = date("Y年m月d日");
		
		for( $i = 0; $i < $this->output['print_cnt']; $i++) {
			
			$this->output['print_time'][$i] = $print_time;
		}

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
					$mac_addr = substr($aryData['mac_addr'], 0, 2);
					$mac_addr .= ":";
					$mac_addr .= substr($aryData['mac_addr'], 2, 2);
					$mac_addr .= ":";
					$mac_addr .= substr($aryData['mac_addr'], 4, 2);
					$mac_addr .= ":";
					$mac_addr .= substr($aryData['mac_addr'], 6, 2);
					$mac_addr .= ":";
					$mac_addr .= substr($aryData['mac_addr'], 8, 2);
					$mac_addr .= ":";
					$mac_addr .= substr($aryData['mac_addr'], 10, 2);

					$entry_name = $this->oMgr->getUserName($aryData['make_id']);

					$this->aryList[$id]['guest_name'] = htmlspecialchars($aryData['guest_name']);
					$this->aryList[$id]['company_name'] = htmlspecialchars($aryData['company_name']);
					$this->aryList[$id]['belong_name'] = htmlspecialchars($aryData['belong_name']);
					$this->aryList[$id]['telno'] = htmlspecialchars($aryData['telno']);
					$this->aryList[$id]['mac_addr'] = htmlspecialchars($mac_addr);
					$this->aryList[$id]['wireless_id'] = htmlspecialchars($aryData['wireless_id']);
					$this->aryList[$id]['entry_name'] = htmlspecialchars($entry_name);
				}
			}
		}
	}
	
	function setPrintData()
	{
		foreach ($_SESSION['print_guest_id'] AS $index => $guest_id)
		{
			
			$aryTmp = $this->oMgr->getPrintGuestData($guest_id);
	
			if ($aryTmp['mac_addr'] != "")
			{
				$mac_addr = substr($aryTmp['mac_addr'], 0, 2);
				$mac_addr .= ":";
				$mac_addr .= substr($aryTmp['mac_addr'], 2, 2);
				$mac_addr .= ":";
				$mac_addr .= substr($aryTmp['mac_addr'], 4, 2);
				$mac_addr .= ":";
				$mac_addr .= substr($aryTmp['mac_addr'], 6, 2);
				$mac_addr .= ":";
				$mac_addr .= substr($aryTmp['mac_addr'], 8, 2);
				$mac_addr .= ":";
				$mac_addr .= substr($aryTmp['mac_addr'], 10, 2);
				$aryTmp['mac_addr'] = $mac_addr;
			}
	
			$this->setOutputData($aryTmp, $index);
	
			$this->output['password'][$index] = htmlspecialchars($this->oMgr->passwordDecrypt($aryTmp['password']));
			
			$this->output['password_furigana'][$index] = htmlspecialchars($this->oMgr->makePasswordFurigana($this->output['password'][$index]));
			
			$this->output['entry_name'][$index] = $this->oMgr->getUserName($this->output['make_id'][$index]);
		}
		
		$this->output['print_cnt'] = count($_SESSION['print_guest_id']);
	}
	

}

?>
