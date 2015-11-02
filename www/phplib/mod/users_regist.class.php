<?php
/**********************************************************
* File         : users_regist.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/users_regist_mgr.class.php");

class users_regist extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "利用者登録";
		$this->header_file = "users_regist_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new users_regist_mgr();
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInput()
	{
		// 初期化
		$this->request = array();

		// サブ属性
		$this->request['sub_belong_chg_id'] = array();
		$this->request['sub_belong_chg_id'][1] = "";
		$this->request['sub_job_id'] = array();
		$this->request['sub_job_id'][1] = "";
		$this->request['sub_post_id'] = array();
		$this->request['sub_post_id'][1] = "";

		$aryTmp = $this->oMgr->getUserTypeAry();
		$this->request['user_type_id'] = key($aryTmp);

		return 1;
	}

	function runAdd()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			return 1;
		}

		$ret = $this->oMgr->insertUserData(&$this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$param = array();
		$param['mode'] = 'init';
		$param['user_id'] = $this->request['user_id'];
		$param['complete'] = "1";
		$this->oMgr->postTo('users_detail.php', $param);

	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		// 職員番号
		if (!$this->oMgr->checkEmpty($this->request['staff_id']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"個人番号");
		}
//		else if (!string::checkNumber($this->request['staff_id'], STAFF_ID_LEN))
		else if (!string::strlen($this->request['staff_id']) > STAFF_ID_LEN)
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "個人番号";
			$param[1] = "半角".STAFF_ID_LEN."桁";
			$this->oMgr->setErr('E004', $param);
		}		
		else if ($this->oMgr->existsStaffId($this->request['staff_id']))
		{
			$this->oMgr->setErr('E017',"個人番号");
		}

		// 漢字姓
		if (!$this->oMgr->checkEmpty($this->request['kanjisei']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"氏名(姓)");
		}
		// 漢字名
		if (!$this->oMgr->checkEmpty($this->request['kanjimei']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"氏名(名)");
		}

		// カナ姓
		$this->request['kanasei'] = string::han2zen($this->request['kanasei']);
		if (!$this->oMgr->checkEmpty($this->request['kanasei']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"氏名カナ(姓)");
		}
		else if (!string::chackKatakana3($this->request['kanasei']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '氏名カナ(姓)';
			$param[1] = 'カタカナ';
			$this->oMgr->setErr('E004', $param);
		}

		// カナ名
		$this->request['kanamei'] = string::han2zen($this->request['kanamei']);
		if (!$this->oMgr->checkEmpty($this->request['kanamei']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"氏名カナ(名)");
		}
		else if (!string::chackKatakana3($this->request['kanamei']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '氏名カナ(名)';
			$param[1] = 'カタカナ';
			$this->oMgr->setErr('E004', $param);
		}

		// カナ姓
		$this->request['kanasei_real'] = string::han2zen($this->request['kanasei_real']);
		if (!$this->oMgr->checkEmpty($this->request['kanasei_real']))
		{
			//
		}
		else if (!string::chackKatakana3($this->request['kanasei_real']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '戸籍氏名カナ(姓)';
			$param[1] = 'カタカナ';
			$this->oMgr->setErr('E004', $param);
		}

		// カナ名
		$this->request['kanamei_real'] = string::han2zen($this->request['kanamei_real']);
		if (!$this->oMgr->checkEmpty($this->request['kanamei_real']))
		{
			//
		}
		else if (!string::chackKatakana3($this->request['kanamei_real']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '戸籍氏名カナ(名)';
			$param[1] = 'カタカナ';
			$this->oMgr->setErr('E004', $param);
		}

		// 英字姓
		$this->request['eijisei'] = string::zen2han($this->request['eijisei']);
		$this->request['eijisei'] = strtolower($this->request['eijisei']);
		$chkEijisei = str_replace("-", "", $this->request['eijisei']);
		if (!$this->oMgr->checkEmpty($this->request['eijisei']))
		{
			// 任意
		}
		else if (!string::checkAlphabet($chkEijisei))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '氏名英字(姓)';
			$param[1] = '半角英字';
			$this->oMgr->setErr('E004', $param);
		}

		// 英字名
		$this->request['eijimei'] = string::zen2han($this->request['eijimei']);
		$this->request['eijimei'] = strtolower($this->request['eijimei']);
		$chkEijimei = str_replace("-", "", $this->request['eijimei']);
		if (!$this->oMgr->checkEmpty($this->request['eijimei']))
		{
			// 任意
		}
		else if (!string::checkAlphabet($chkEijimei))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '氏名英字(名)';
			$param[1] = '半角英字';
			$this->oMgr->setErr('E004', $param);
		}

		// 性別
		$this->request['sex'] = (string)$this->request['sex'];
		if (!$this->oMgr->checkEmpty($this->request['sex']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"性別");
		}

		// 生年月日
		if (!$this->oMgr->checkEmpty($this->request['birth_year']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"生年月日(年)");
		}
		if (!$this->oMgr->checkEmpty($this->request['birth_mon']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"生年月日(月)");
		}
		if (!$this->oMgr->checkEmpty($this->request['birth_day']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"生年月日(日)");
		}

		// 生年月日に入力がある場合
		if ($this->request['birth_year'] != "" || $this->request['birth_mon'] != "" || $this->request['birth_day'] != "")
		{
			if ($this->request['birth_year'] == "" || $this->request['birth_mon'] == "" || $this->request['birth_day'] == "")
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E006',"生年月日");
			}
			else if(!checkdate($this->request['birth_mon'], $this->request['birth_day'], $this->request['birth_year']))
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E013',"生年月日");
			}
		}

		// 所属
		if (!$this->oMgr->checkEmpty($this->request['belong_chg_id']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"所属（メイン）");
		}

		// 職種
		if (!$this->oMgr->checkEmpty($this->request['job_id']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"職種（メイン）");
		}

		// 役職
		if (!$this->oMgr->checkEmpty($this->request['post_id']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"役職（メイン）");
		}

		// 内線
		$this->request['naisen'] = string::zen2han($this->request['naisen']);
		if (!$this->oMgr->checkEmpty($this->request['naisen']))
		{
			// 任意
		}
		else if (!string::checkNumberWide($this->request['naisen'], 1, 20))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "内線";
			$param[1] = "半角数字20桁以内";
			$this->oMgr->setErr('E004', $param);
		}

		// PHS番号
		$this->request['pbno'] = string::zen2han($this->request['pbno']);
		if (!$this->oMgr->checkEmpty($this->request['pbno']))
		{
			// 任意
		}
		else if (!string::checkNumber($this->request['pbno'], 4))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "PHS番号";
			$param[1] = "半角数字4桁";
			$this->oMgr->setErr('E004', $param);
		}

		// 常勤／非常勤
		if (!$this->oMgr->checkEmpty($this->request['joukin_kbn']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',"常勤／非常勤");
		}

		// 統合ID
		$this->request['login_id'] = string::zen2han($this->request['login_id']);
		$this->request['login_id'] = strtolower($this->request['login_id']);
		// 元データと変わってない場合チェックしない
		if (!$this->oMgr->checkEmpty($this->request['login_id']))
		{
			// 任意
			if ($this->request['mail_acc'] != "")
			{
				$this->oMgr->setErr('E001',"統合ID");
			}
		}
		else if (string::strlen($this->request['login_id']) > 20)
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '統合ID';
			$param[1] = '20文字以内';
			$this->oMgr->setErr('E004', $param);
		}
		else if (!ereg("^[a-z]+\.[a-z]+\.[a-z]{2}$", $this->request['login_id']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = '統合ID';
			$param[1] = '姓．名．規定の英字2文字';
			$this->oMgr->setErr('E004', $param);
		}
		else if ($this->oMgr->existsMailAcc($this->request['login_id']))
		{
			// メールアカウントとして使用中
			$this->oMgr->setErr('E017',"統合ID");
		}
		else if ($this->oMgr->existsMlistAcc($this->request['login_id']))
		{
			// メーリングリストとして使用中
			$this->oMgr->setErr('E017',"統合ID");
		}
		else if ($this->oMgr->existsOldMail($this->request['login_id']))
		{
			// エイリアスのアカウントとして使用中
			$this->oMgr->setErr('E017',"統合ID");
		}
		else
		{
			list($sei, $mei, $rand) = explode(".", $this->request['login_id']);

			$aryRandStr = $this->oMgr->getAry('rand_tow_chars');

			if (!in_array($rand, $aryRandStr))
			{
				// エラーメッセージをセット
				$param = array();
				$param[0] = '統合ID';
				$param[1] = '姓．名．規定の英字2文字';
				$this->oMgr->setErr('E004', $param);
			}
			else if ($this->oMgr->existsLoginId($this->request['login_id'], $user_id))
			{
				$this->oMgr->setErr('E017',"統合ID");
			}
		}

		// パスワード
		$this->request['login_passwd'] = string::zen2han($this->request['login_passwd']);
		if (!$this->oMgr->checkEmpty($this->request['login_passwd']))
		{
			// 任意
			if ($this->request['login_id'] != "" && $user_id == "")
			{
				$this->oMgr->setErr('E001',"パスワード");
			}
		}
		else
		{
			$passwd = $this->request['login_passwd'];
			if (!string::checkAlphanumWide($passwd, 6, 20) || !ereg("[0-9]", $passwd) || !ereg("[a-z]", $passwd) || !ereg("[A-Z]", $passwd))
			{
				$param = array();
				$param[0] = "パスワード";
				$param[1] = "数字、英字大文字、英字小文字を各１文字以上使用し、6～20文字";

				// エラーメッセージをセット
				$this->oMgr->setErr('E004', $param);
			}
		}

		// メールアカウント
		$mailMsg = "";
		if (!$this->oMgr->checkEmpty($this->request['mail_acc']))
		{
			//
		}
		else if (!$this->oMgr->checkNcvcMailAcc($this->request['mail_acc'], "メールアカウント", &$mailMsg))
		{
			// エラーメッセージをセット
			$this->oMgr->pushError($mailMsg);
		}
		else if ($this->oMgr->existsMailAcc($this->request['mail_acc'], $user_id))
		{
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->oMgr->existsMlistAcc($this->request['mail_acc']))
		{
			// メーリングリストとして使用中
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->oMgr->existsLoginId($this->request['mail_acc']))
		{
			// 統合IDとして使用中
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->oMgr->existsOldMail($this->request['mail_acc']))
		{
			// エイリアスのアカウントとして使用中
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->request['mail_acc'] == $this->request['login_id'])
		{
			$param = array();
			$param[0] = "メールアカウント";
			$param[1] = "統合ID以外の文字列";
			$this->oMgr->setErr('E004',$param);
		}

		// ファイル転送
		if (!$this->oMgr->checkEmpty($this->request['ftrans_user_kbn']))
		{
			if ($this->request['ftrans_user_flg'] == '1')
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E007',"ファイル転送機能利用の権限");
			}
		}

		// 利用期間
		if (!$this->oMgr->checkEmpty($this->request['start_date']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',"利用期間(開始日)");
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDateFormat($this->request['start_date']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "利用期間(開始日)";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDate($this->request['start_date']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',"利用期間(開始日)");
			$has_date_err = true;
		}

		// 利用期間
		if (!$this->oMgr->checkEmpty($this->request['end_date']))
		{
			// エラーメッセージをセット
			//$this->oMgr->setErr('E001',"利用期間(終了日)");
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDateFormat($this->request['end_date']))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "利用期間(終了日)";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
			$has_date_err = true;
		}
		else if (!$this->oMgr->checkDate($this->request['end_date']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',"利用期間(終了日)");
			$has_date_err = true;
		}

		if (!$has_date_err)
		{
			if (!$this->oMgr->checkDateTerm($this->request['start_date'], $this->request['end_date']))
			{
				// エラーメッセージをセット
				$param = array();
				$param[0] = "利用期間(開始日)";
				$param[1] = "利用期間(終了日)";
				$this->oMgr->setErr('E012',$param);
			}
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
