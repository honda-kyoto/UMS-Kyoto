<?php
/**********************************************************
* File         : users_edit.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/users_regist_common.class.php");
require_once("mgr/users_edit_mgr.class.php");

class users_edit extends users_regist_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "利用者編集";
		$this->header_file = "users_edit_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new users_edit_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		$this->setEditData();

		if ($this->request['complete'] == "1")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}

		return 1;
	}

	function runUpdate()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			return 1;
		}

		$ret = $this->oMgr->updateUserData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete();
	}

	/*======================================================
	 * Name         : runReissuePassword
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function runReissuePassword()
	{
		$ret = $this->oMgr->reissuePassword($this->request['col_name'], $this->request['list_no'], $this->request['user_id'], $this->request['passwd']);
//Debug_Trace($this->request, 987);
		echo $ret;
	}

	function runPrint()
	{
		$user_id = $_SESSION['password_user_id'];
		$col_name = $_SESSION['password_col_name'];
		$list_no = $_SESSION['password_list_no'];


		$aryUser = $this->oMgr->getUserData($user_id);

		$this->output['kanji_name'] = $aryUser['kanjisei'] . "　" . $aryUser['kanjimei'];

		if ($col_name == 'login_passwd')
		{
			$this->output['passwd'] = $aryUser['login_passwd'];
			$this->output['staffcode'] = $aryUser['login_id'];
			$this->output['idname'] = "統合ID";
		}
		else
		{
			$this->output['staffcode'] = $aryUser['staffcode'];
			$this->output['idname'] = "ログインID";
			if ($list_no == "0")
			{
				$this->output['passwd'] = $aryUser['password'];
			}
			else
			{
				$aryHis = $this->oMgr->getSubHisData($user_id);
				$this->output['passwd'] = $aryHis[$list_no]['sub_password'];
			}
		}

		$this->output['print_time'] = date("Y年m月d日H時i分");

		return 1;
	}
	
	/*======================================================
	* Name         : runPreNcvcIDPrint
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runPreNcvcIDPrint()
	{
		$user_id = $this->request['user_id'];
		
		$_SESSION['print_user_id'] = $user_id;
		
		echo "1";
	}
	
	/*======================================================
	* Name         : runNcvcIDPrint
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runNcvcIDPrint()
	{
		$this->setNcvcIDPrintData();

		$print_time = date("Y年m月d日");
		$this->output['print_time'] = $print_time;

		return 1;
	}
	
	/*======================================================
	* Name         : runPreJunhisIDPrint
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runPreJunhisIDPrint()
	{
		$user_id = $this->request['user_id'];
		$list_no = $this->request['list_no'];
		
		$_SESSION['print_user_id'] = $user_id;
		$_SESSION['print_list_no'] = $list_no;
		
		echo "1";
	}

	/*======================================================
	* Name         : runJunhisIDPrint
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runJunhisIDPrint()
	{
		$this->setIDPrintData();

		$print_time = date("Y年m月d日");
		$this->output['print_time'] = $print_time;

		return 1;
	}

	function setEditData()
	{
		// 基本情報取得(HIS連携データ込)
		$aryTmp = $this->oMgr->getUserData($this->request['user_id']);

		if (@$aryTmp['has_his_data'] != "1")
		{
			$aryTmp['his_init'] = "1";
			$aryTmp['send_date'] = date("Y/m/d");
		}
		
		if (@$aryTmp['login_id'] == "" && $aryTmp['login_passwd'] == "")
		{
			// 不明ユーザー
			$this->request['is_unknown_user'] = true;
		}

		$this->setRequestData($aryTmp);

		// サブ所属
		$aryTmp = $this->oMgr->getSubBelongData($this->request['user_id']);

		$belong_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->setRequestData($aryData, $no);
				$belong_cnt++;
			}
		}
		if ($belong_cnt == 0)
		{
			// サブ属性
			$this->request['sub_belong_chg_id'] = array();
			$this->request['sub_belong_chg_id'][1] = "";
		}

		// サブ職種
		$aryTmp = $this->oMgr->getSubJobData($this->request['user_id']);

		$job_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->setRequestData($aryData, $no);
				$job_cnt++;
			}
		}
		if ($job_cnt == 0)
		{
			$this->request['sub_job_id'] = array();
			$this->request['sub_job_id'][1] = "";
		}

		// サブ役職
		$aryTmp = $this->oMgr->getSubPostData($this->request['user_id']);

		$post_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->setRequestData($aryData, $no);
				$post_cnt++;
			}
		}
		if ($post_cnt == 0)
		{
			$this->request['sub_post_id'] = array();
			$this->request['sub_post_id'][1] = "";
		}

		// サブHIS連携データ
		$aryTmp = $this->oMgr->getSubHisData($this->request['user_id']);

		$his_cnt = 0;
		if (is_array($aryTmp))
		{
			foreach ($aryTmp AS $no => $aryData)
			{
				$this->setRequestData($aryData, $no);
				$his_cnt++;
			}
		}
		if ($his_cnt == 0)
		{
		}

		// 権限データ
		$aryTmp = $this->oMgr->getRoleData($this->request['user_id']);

		$this->request['user_type_id'] = $aryTmp['user_type_id'];
		$this->request['user_role_id'] = $aryTmp['user_role_id'];
	}
	
	function setNcvcIDPrintData()
	{
		$user_id = $_SESSION['print_user_id'];
		
		// 利用者管理マスタデータ
		$aryTmp = $this->oMgr->getPrintNcvcIdData($user_id);
		
		$this->setRequestData($aryTmp);
		
		$this->output['print_cnt'] = 1;
	}

	function setIDPrintData()
	{
		$user_id = $_SESSION['print_user_id'];
		$list_no = $_SESSION['print_list_no'];
		
		// HIS連携データ
		$aryTmp = $this->oMgr->getJunHisData($user_id, $list_no);
		
		$this->setRequestData($aryTmp);
		
		$this->output['print_cnt'] = 1;
	}
	
}

?>
