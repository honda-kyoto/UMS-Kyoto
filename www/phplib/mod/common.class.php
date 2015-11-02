<?php
/**********************************************************
* File         : common.class.php
* Authors      : mie tsutsui
* Date         : 2011.07.26
* Last Update  : 2011.07.26
* Copyright    :
***********************************************************/

class common
{
	var $oMgr;
	var $mode;
	var $request;
	var $output;
	var $errMsg;		// エラーメッセージ

	var $list_max;
	var $cur_page;
	var $list_cnt;

	var $page_title;
	var $form_option = "";

	var $in_mst_menu = false;

	var $menu_script_name = "";
	var $script_dir = "view_h";

	//============================================
	// コンストラクタ
	//============================================
	function __construct($request, $zen2han=array())
	{
		// モードを取得
		$this->mode = $request['mode'];

		// リクエストデータを取得
		$this->request = string::removeEscape($request);

		// メニューマスタに登録されているファイル名
		// メニューから直接アクセスしない画面でこの変数を使用する場合は適宜書き換えが必要！！
		$this->menu_script_name = basename($_SERVER['SCRIPT_NAME']);
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
			$this->oMgr->logout();
		}

		$myMode = ucfirst($this->mode);
		return $this->{"run$myMode"}();
	}

	/*======================================================
	* Name         : runReturn
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runReturn()
	{
		return 1;
	}


	/*======================================================
	* Name         : getRequestData
	* IN           : フィールド名
	* OUT          : HTMLサニタイズした指定された名前のリクエストデータ
	* Discription  : 指定された名前のリクエストデータをHTMLサニタイズして返す
	=======================================================*/
	function getRequestData($name, $key="")
	{
		$ret = "";

		if ($key != "")
		{
			$ret = @$this->request[$name][$key];
		}
		else
		{
			$ret = @$this->request[$name];
		}

		return htmlspecialchars($ret);
	}

	/*======================================================
	* Name         : setOutputData
	* IN           : 配列
	* OUT          : 単純な1次元配列を出力データにセット
	* Discription  :
	=======================================================*/
	function setOutputData($ary, $key="")
	{
		$key = (string)$key;
		if (is_array($ary))
		{
			foreach ($ary AS $name => $val)
			{
				if ($key != "")
				{
					$this->output[$name][$key] = $val;
				}
				else
				{
					$this->output[$name] = $val;
				}
			}
		}
	}
	/*======================================================
	* Name         : setRequestData
	* IN           : 配列
	* OUT          : 単純な1次元配列を出力データにセット
	* Discription  :
	=======================================================*/
	function setRequestData($ary, $key="")
	{
		$key = (string)$key;
		if (is_array($ary))
		{
			foreach ($ary AS $name => $val)
			{
				if ($key != "")
				{
					$this->request[$name][$key] = $val;
				}
				else
				{
					$this->request[$name] = $val;
				}
			}
		}
	}

	/*======================================================
	* Name         : getOutputData
	* IN           : フィールド名
	* OUT          : HTMLサニタイズした指定された名前の出力データ
	* Discription  : 指定された名前の出力データをHTMLサニタイズして返す
	=======================================================*/
	function getOutputData($name, $key="")
	{
		$key = (string)$key;
		$ret = "";

		if ($key != "")
		{
			$ret = @$this->output[$name][$key];
		}
		else
		{
			$ret = @$this->output[$name];
		}

		return htmlspecialchars($ret);
	}

	/*======================================================
	* Name         : getLoginData
	* IN           : フィールド名
	* OUT          : HTMLサニタイズした指定された名前のリクエストデータ
	* Discription  : 指定された名前のリクエストデータをHTMLサニタイズして返す
	=======================================================*/
	function getLoginData($name)
	{
		return $this->oMgr->getSessionData($name);
	}

	/*======================================================
	* Name         : getRequestData
	* IN           : フィールド名
	* OUT          : HTMLサニタイズした指定された名前のリクエストデータ
	* Discription  : 指定された名前のリクエストデータをHTMLサニタイズせずに返す
	=======================================================*/
	function getRequestDataNS($name)
	{
		$ret = "";

		$ret = @$this->request[$name];

		return $ret;
	}

	/*======================================================
	* Name         : getSelectList
	* IN           : フィールド名
	* OUT          : SELECTタグのOPTIONリスト
	* Discription  : SELECTタグのOPTIONリストを生成して返す
	=======================================================*/
	function getSelectList($name, $key="", $args="")
	{
		$key = (string)$key;
		if ($key != "")
		{
			return $this->oMgr->makeSelectList($name, @$this->request[$name][$key], $args);
		}
		else
		{
			return $this->oMgr->makeSelectList($name, @$this->request[$name], $args);
		}
	}

	/*
	 * getCheckBoxList
	 */
	function getCheckBoxList($name, $limit="", $onClick="")
	{
		return $this->oMgr->makeCheckBoxList($name, @$this->request[$name], $limit, $onClick);
	}

	/*
	 * getRadioButtonList
	 */
	function getRadioButtonList($name, $limit="", $onClick="")
	{
		return $this->oMgr->makeRadioButtonList($name, @$this->request[$name], $limit, $onClick);
	}

	/*======================================================
	* Name         : getCheckData
	* IN           : フィールド名
	* OUT          :
	* Discription  : チェックボックス・ラジオボタンの選択状態を返す
	=======================================================*/
	function getCheckData($name, $value, $key="")
	{
		$key = (string)$key;
		if ($key != "")
		{
			if (@$this->request[$name][$key] != "" && @$this->request[$name][$key] == $value)
			{
				return " checked";
			}
			else if (@$this->request[$name][$key] == "" && $value == "")
			{
				return " checked";
			}
		}
		else
		{
			if (@$this->request[$name] != "" && @$this->request[$name] == $value)
			{
				return " checked";
			}
			else if (@$this->request[$name] == "" && $value == "")
			{
				return " checked";
			}
		}
		return "";
	}

	/*======================================================
	* Name         : getListNavi
	* IN           :
	* OUT          :
	* Discription  :
	=======================================================*/
	function getListNavi()
	{
		$ret = "";

		$ret = $this->oMgr->makeListNavi($this->list_cnt, $this->list_max, $this->cur_page);

		return $ret;
	}

	/*
	 * getOrderData
	 */
	function getOrderData($type, $name)
	{
		// ソート用設定配列を取得
		$arySet = $this->oMgr->getAry('sort_data');

		$cur = 0;
		// 現在のオーダーキーかつ昇順の場合
		if ($name == $this->oMgr->loadOrderKey() && $this->oMgr->loadOrderDesc() == '')
		{
			$cur = 1;
		}

		return htmlspecialchars($arySet[$type][$cur]);
	}

	/*======================================================
	* Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		// エラーなし
		if (sizeof($this->oMgr->aryErrMsg) == 0)
		{
			return true;
		}

		return false;
	}

	function isNormalUser()
	{
		//return $this->oMgr->isNormalUser();

		if ($this->oMgr->hasAdminActType($this->menu_script_name))
		{
			return false;
		}

		return true;
	}

	function isUserCtrlUser()
	{
		return $this->oMgr->isUserCtrlUser();
	}

	function isAdminUser()
	{
		//return $this->oMgr->isAdminUser();

		if ($this->oMgr->hasAdminActType($this->menu_script_name))
		{
			return true;
		}

		return false;

	}

	function isVlanAdminUser()
	{
		return $this->oMgr->isVlanAdminUser();
	}

	function getUserName($user_id)
	{
		if ($user_id == "")
		{
			return "";
		}
		return $this->oMgr->getUserName($user_id);
	}

	function setCommonEntryData()
	{

		// 申請ステータス
		if ($this->output['entry_kbn'] == "" && $this->output['entry_status'] == "")
		{
			// これは実データ
			$this->output['is_pending_data'] = false;
		}
		else
		{
			$kbn_status = $this->output['entry_kbn'] . "_" . $this->output['entry_status'];

			$entry_kbn_status = $this->oMgr->getValue('entry_kbn_status', $kbn_status);
			$this->output['entry_kbn_status'] = htmlspecialchars($entry_kbn_status);
			$this->output['is_pending_data'] = true;

			$this->output['entry_kbn_name'] = htmlspecialchars($this->oMgr->getValue('entry_kbn', $this->output['entry_kbn']));
			$this->output['entry_status_name'] = htmlspecialchars($this->oMgr->getValue('entry_status', $this->output['entry_status']));
		}

		if ($this->output['entry_id'] != "")
		{
			$this->output['entry_user_name'] = $this->oMgr->getUserName($this->output['entry_id']);
			$aryUser = $this->oMgr->getUserData($this->output['entry_id']);
			$ary['belong_chg_id'] = $aryUser['belong_chg_id'];

			$this->output['entry_user_belong_name'] = $this->oMgr->getBelongName(&$ary);

			if ($this->output['entry_kbn'] == ENTRY_STATUS_AGREE)
			{
				$this->output['agree_user_name'] = $this->oMgr->getUserName($this->output['agree_id']);
			}
		}


	}

	function getViewDirName()
	{
		$dir = "view";
		if (defined("ORIGINAL_NAME"))
		{
			$dir .= "_" . ORIGINAL_NAME;
		}

		return $dir;
	}

	function getScriptDirName()
	{
		$dir = "view_h";
		if (defined("ORIGINAL_NAME"))
		{
			$dir .= "_" . ORIGINAL_NAME;
		}

		return $dir;
	}

	function getViewFileName($path)
	{
		$dir = "view";
		if (defined("ORIGINAL_NAME"))
		{
			$dir .= "_" . ORIGINAL_NAME;

			try {
			ob_start();
			$mgr = $this;
			if (!@include ($dir . $path))
			{
				$dir = "view";
			}
			$mgr = null;
			ob_end_clean();
			}
			catch (Exception $e)
			{
				///
			}
		}

		$file_path = $dir . $path;

		return $file_path;
	}

	function getScriptFileName($path)
	{
		$dir = "view_h";
		if (defined("ORIGINAL_NAME"))
		{
			$dir .= "_" . ORIGINAL_NAME;

			try {
				ob_start();
				$mgr = $this;
				if (!@include ($dir . $path))
				{
					$dir = "view_h";
				}
				$mgr = null;
				ob_end_clean();
			}
			catch (Exception $e)
			{
				///
			}
		}

		$file_path = $dir . $path;

		return $file_path;
	}

}

?>
