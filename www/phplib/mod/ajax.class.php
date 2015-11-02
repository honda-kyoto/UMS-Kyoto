<?php
/**********************************************************
* File         : ajax.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.29
* Last Update  : 2013.01.29
* Copyright    :
***********************************************************/

require_once("mgr/ajax_mgr.class.php");

class ajax
{
	var $post;
	var $mode;

	//============================================
	// コンストラクタ
	//============================================
	function __construct($mode, $post)
	{
		// 基本処理オブジェクト生成
		$this->oMgr = new ajax_mgr();

		$this->mode = $mode;

		$this->post = $post;
	}


	/*======================================================
	* Name         : run
	* IN           :
	* OUT          : 画面番号
	* Discription  : メインの関数
	=======================================================*/
	function run()
	{
		// ログインチェック
		if (!$this->oMgr->loginCheck())
		{
			exit;
		}

		$myMode = ucfirst($this->mode);
		return $this->{"run$myMode"}();
	}

	/*======================================================
	 * Name         : runVlanRidgeChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runVlanRidgeChange()
	{
		$flg = true;
		if (@$this->post['vdi_mode'] == "1")
		{
			$flg = false;
		}

		$ret = $this->oMgr->makeVlanRidgeChangeJs($this->post['vlan_ridge_id'], @$this->post['prefix'], $flg);

		echo $ret;
	}

	/*======================================================
	 * Name         : runVlanRidgeChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runVlanFloorChange()
	{
		$flg = true;
		if (@$this->post['vdi_mode'] == "1")
		{
			$flg = false;
		}

		$ret = $this->oMgr->makeVlanFloorChangeJs($this->post['vlan_floor_id'], @$this->post['prefix'], $flg);

		echo $ret;
	}

	/*======================================================
	 * Name         : runVlanRoomChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runVlanRoomChange()
	{
		$ret = $this->oMgr->makeVlanRoomChangeJs($this->post['vlan_room_id'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	* Name         : runBelongClassChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runBelongClassChange()
	{
		$ret = $this->oMgr->makeBelongClassChangeJs($this->post['belong_class_id'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runBelongDivChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runBelongDivChange()
	{
		$ret = $this->oMgr->makeBelongDivChangeJs($this->post['belong_div_id'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runBelongDepChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runBelongDepChange()
	{
		$ret = $this->oMgr->makeBelongDepChangeJs($this->post['belong_dep_id'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runBelongSecChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runBelongSecChange()
	{
		$ret = $this->oMgr->makeBelongSecChangeJs($this->post['belong_sec_id'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runAddBelongList
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runAddBelongList()
	{
		$ret = $this->oMgr->makeAddBelongListJs($this->post['key'], $this->post['tbody_id'], @$this->post['pattern']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runAddJobList
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runAddJobList()
	{
		$ret = $this->oMgr->makeAddJobListJs($this->post['key'], $this->post['tbody_id']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runAddPostList
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runAddPostList()
	{
		$ret = $this->oMgr->makeAddPostListJs($this->post['key'], $this->post['tbody_id']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runMakeLoginId
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runMakeLoginId()
	{
		$ret = $this->oMgr->makeLoginId($this->post['eijisei'], $this->post['eijimei']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runMakePassword
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runMakePassword()
	{
		$ret = $this->oMgr->createPassword();

		echo $ret;
	}

	/*======================================================
	 * Name         : runExistsLoginId
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runExistsLoginId()
	{
		$ret = $this->oMgr->existsLoginId($this->post['login_id'], @$this->post['user_id']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runExistsPbno
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runExistsPbno()
	{
		$ret = $this->oMgr->existsPbno($this->post['pbno'], @$this->post['user_id']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runWardstatusChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runWardstatusChange()
	{
		$ret = $this->oMgr->makeWardstatusChangeJs($this->post['wardstatus'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runProfessionstatusChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runProfessionstatusChange()
	{
		$ret = $this->oMgr->makeProfessionstatusChangeJs($this->post['professionstatus'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runDeptstatusChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runDeptstatusChange()
	{
		$ret = $this->oMgr->makeDeptstatusChangeJs($this->post['deptstatus'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runDeptcodeChange
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runDeptcodeChange()
	{
		$ret = $this->oMgr->makeDeptcodeChangeJs($this->post['deptcode'], @$this->post['prefix']);

		echo $ret;
	}

	/*======================================================
	 * Name         : runAddHisDataList
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runAddHisDataList()
	{
		$ret = $this->oMgr->makeAddHisDataListJs($this->post['key'], $this->post['tbody_id'], $this->post['kanjiname'], $this->post['kananame']);

		echo $ret;
	}


}

?>
