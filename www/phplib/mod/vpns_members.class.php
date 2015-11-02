<?php
/**********************************************************
* File         : vpns_members.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/vpns_members_regist_common.class.php");
require_once("mgr/vpns_members_mgr.class.php");

class vpns_members extends vpns_members_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VPN参加者リスト　";
		$this->header_file = "vpns_members_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vpns_members_mgr();
	}

	/*======================================================
	* Name         : runList
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runList()
	{
		$this->setListData();

		if ($this->request['complete'] == "1")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}
		
		// 有効期限の初期値設定（年度対応）
		$real_year = (int)date("Y");
		$limit_time = date("m");
		if ($limit_time > 3)
		{
			$this->request['expiry_date']        = ($real_year+1) . "/03/31";
			$this->request['update_expiry_date'] = ($real_year+2) . "/03/31";
			
		}
		else
		{
			$this->request['expiry_date']        = date("Y/03/31");
			$this->request['update_expiry_date'] = ($real_year+1) . "/03/31";
		}
		

		return 1;
	}

	function runAdd()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setListData();
			return 1;
		}

		$ret = $this->oMgr->addMember($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

	/*======================================================
	 * Name         : runDelete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runDelete()
	{
		$ret = $this->oMgr->deleteMember($this->request['vpn_id'], $this->request['vpn_user_id']);

		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

	function runViewVpnPasswd()
	{
		$aryTmp = $this->oMgr->getList($this->request['vpn_id']);
		$aryMem = $aryTmp[$this->request['vpn_user_id']];

		$passwd = htmlspecialchars($this->oMgr->passwordDecrypt($aryMem['passwd']));

		echo $passwd;
	}

	/*======================================================
	* Name         : runPrePrint
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runPrePrint()
	{
		$vpn_id = $this->request['vpn_id'];
		$vpn_user_id = $this->request['vpn_user_id'];
		
		$_SESSION['print_vpn_id']      = $vpn_id;
		$_SESSION['print_vpn_user_id'] = $vpn_user_id;
		
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
	
	/*======================================================
	* Name         : runUpdate
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runUpdate()
	{
		// 入力値チェック
		$ret = $this->checkInputdataForUpdate();

		// エラーあり
		if (!$ret)
		{
			$this->setListData();
			return 1;
		}

 		$ret = $this->oMgr->updateVpnMembersExpiry($this->request);

 		if (!$ret)
 		{
 			$this->oMgr->showSystemError();
 		}

		// リダイレクト
		$this->postComplete();
	}
	
	function postComplete()
	{
		$param = array();
		$param['mode'] = 'list';
		$param['vpn_id'] = $this->request['vpn_id'];
		$param['complete'] = "1";

		$this->oMgr->postTo('vpns_members.php', $param);
	}

	function setListData()
	{
		// 一覧データ取得
		$this->aryList = $this->oMgr->getList($this->request['vpn_id']);

		if (is_array($this->aryList))
		{
			foreach ($this->aryList AS $id => $aryData)
			{
				$this->aryList[$id]['mail_addr'] = htmlspecialchars($aryData['mail_addr']);
				$this->aryList[$id]['kanjiname'] = htmlspecialchars($aryData['kanjiname']);
				$this->aryList[$id]['kananame'] = htmlspecialchars($aryData['kananame']);
				$this->aryList[$id]['company'] = htmlspecialchars($aryData['company']);
				$this->aryList[$id]['contact'] = htmlspecialchars($aryData['contact']);
				$this->aryList[$id]['expiry_date'] = htmlspecialchars($aryData['expiry_date']);
				$this->aryList[$id]['note'] = htmlspecialchars($aryData['note']);
			}
		}
	}

	function setPrintData()
	{
		
		foreach ($_SESSION['print_vpn_user_id'] AS $index => $vpn_user_id)
		{
		
			$aryTmp = $this->oMgr->getPrintVpnMembers($_SESSION['print_vpn_id'], $vpn_user_id);

			$this->setOutputData($aryTmp, $index);
	
		}

		$this->output['print_cnt'] = count($_SESSION['print_vpn_user_id']);
	}

	/*======================================================
	 * Name         : checkInputdataForUpdate
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 一括更新用有効期限をチェックする
	=======================================================*/
	function checkInputdataForUpdate()
	{
		// 有効期限
		if (!$this->oMgr->checkEmpty($this->request['update_expiry_date']))
		{
			//
			$this->oMgr->setErr('E001',"有効期限(一括更新)");
		}
		else if (!$this->oMgr->checkDateFormat($this->request['update_expiry_date']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "有効期限(一括更新)";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDate($this->request['update_expiry_date']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',"有効期限(一括更新)");
			$has_date_err = true;
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
