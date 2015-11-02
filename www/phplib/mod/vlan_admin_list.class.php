<?php
/**********************************************************
* File         : vlan_admin_list.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/msts_common.class.php");
require_once("mgr/vlan_admin_list_mgr.class.php");

class vlan_admin_list extends msts_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "VLAN管理 -VLAN管理者設定-";
		$this->header_file = "vlan_admin_list_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new vlan_admin_list_mgr();
	}

	function setListData()
	{
		$vlan_id = $this->request['vlan_id'];

		$aryTmp = $this->oMgr->getVlanAdminData($vlan_id);

		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $list_no => $data)
			{
				$this->output['admin_name'][$list_no] = $data['kanjisei'] . "　" . $data['kanjimei'];
				$belong_name = $this->oMgr->getBelongName(&$data);
				$this->output['belong_name'][$list_no] = $belong_name;
			}
		}

		// ヘッダ情報
		$aryParent = array();
		$this->output['vlan_name'] = $this->oMgr->getVlanAreaName($vlan_id, &$aryParent);
		$this->request['vlan_room_id'] = $aryParent['vlan_room_id'];

	}


	/*======================================================
	 * Name         : runAdd
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runAdd()
	{
		// 登録処理
		$ret = $this->oMgr->insertData($this->request);

		// エラー
		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}

	/*======================================================
	 * Name         : runRemove
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runRemove()
	{
		// 登録処理
		$ret = $this->oMgr->deleteData($this->request);

		// エラー
		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}

	function postComplete()
	{
		$param = array();
		$param['mode'] = "complete";
		$param['vlan_id'] = $this->request['vlan_id'];
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $param);
	}

}

?>
