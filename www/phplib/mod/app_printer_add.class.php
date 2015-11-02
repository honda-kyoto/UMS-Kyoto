<?php
/**********************************************************
* File         : app_printer_add.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/app_printer_add_mgr.class.php");

class app_printer_add extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "端末→プリンタ割り付け";
		$this->header_file = "app_printer_add_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new app_printer_add_mgr();
	}

	/*======================================================
	 * Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		return 1;
	}

	/*======================================================
	 * Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runSelect()
	{
		$this->setEditData();

		return 1;
	}

	/*======================================================
	 * Name         : runComplete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runComplete()
	{
		// 完了メッセージをセット
		$this->oMgr->setErr('C002');
		$this->errMsg = $this->oMgr->getErrMsg();

		$this->setEditData();

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
		$ret = $this->checkInputdata();

		// エラー
		if (!$ret)
		{
			return 1;
		}

		// 登録処理
		$ret = $this->oMgr->updateData($this->request);

		// エラー
		if (!$ret)
		{
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}


	function setEditData()
	{
		if ($this->request['terminal_id'] != "")
		{
			$aryTmp = $this->oMgr->getTerminalDeviceList($this->request['terminal_id']);

			$arySel = @$this->request['device_id'];
			$this->request['device_id'] = array();
			$this->request['device_id'] = $aryTmp;

			if (is_array($aryTmp))
			{
				if (is_array($arySel))
				{
					foreach ($arySel AS $dev_id)
					{
						if (!in_array($dev_id, $this->request['device_id']))
						{
							$this->request['device_id'][] = $dev_id;
						}

					}
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
		if ($this->request['terminal_id'] == "")
		{
			$this->oMgr->setErr('E007', '端末');
		}

		if ($this->request['device_id'] == "")
		{
			//$this->oMgr->setErr('E007', 'プリンタ');
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
		$param['tm_vlan_ridge_id'] = $this->request['tm_vlan_ridge_id'];
		$param['tm_vlan_floor_id'] = $this->request['tm_vlan_floor_id'];
		$param['tm_vlan_room_id'] = $this->request['tm_vlan_room_id'];
		$param['unallocated_only'] = $this->request['unallocated_only'];
		$param['terminal_id'] = $this->request['terminal_id'];
		$param['dv_vlan_ridge_id'] = $this->request['dv_vlan_ridge_id'];
		$param['dv_vlan_floor_id'] = $this->request['dv_vlan_floor_id'];
		$param['dv_vlan_room_id'] = $this->request['dv_vlan_room_id'];
		$param['mode'] = "complete";
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $param);
	}

	function getTerminalList()
	{
		return $this->oMgr->makeTerminalList($this->request);
	}

	function getDeviceList()
	{
		return $this->oMgr->makeDeviceList($this->request);
	}

	function getDeviceSelectedList()
	{
		return $this->oMgr->makeDeviceSelectedList($this->request);
	}
}

?>
