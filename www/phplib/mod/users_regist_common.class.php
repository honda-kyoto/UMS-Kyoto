<?php
/**********************************************************
* File         : users_regist_common.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");

class users_regist_common extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

	}

	function postComplete()
	{
		$param = array();
		$param['mode'] = 'input';
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
		$user_id = @$this->request['user_id'];
		if ($user_id != "")
		{
			$aryOld = $this->oMgr->getUserData($user_id);
			$old_login_id = $aryOld['login_id'];
			$old_mail_acc = $aryOld['mail_acc'];
			unset($aryOld);
		}

		// 職員番号
		if (!$this->oMgr->checkEmpty($this->request['staff_id']))
		{
			// エラーメッセージをセット
			//$this->oMgr->setErr('E001',"職員ID");
		}
		//else if (!string::checkNumberWide($this->request['staff_id'], 1, STAFF_ID_LEN))
		//else if (!string::checkNumber($this->request['staff_id'], 6))
		else if (!string::strlen($this->request['staff_id']) > STAFF_ID_LEN)
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "職員番号";
			$param[1] = "半角".STAFF_ID_LEN."桁以内";
			$this->oMgr->setErr('E004', $param);
		}
		else if ($this->oMgr->existsStaffId($this->request['staff_id'], $user_id))
		{
			$this->oMgr->setErr('E017',"職員番号");
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

		// HIS連携する場合
		if ($this->request['his_flg'] == '1')
		{
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

		// 統合ID
		$this->request['login_id'] = string::zen2han($this->request['login_id']);
		$this->request['login_id'] = strtolower($this->request['login_id']);
		// 元データと変わってない場合チェックしない
		if ($old_login_id == $this->request['login_id'])
		{
			// 不明ユーザー（元データが空）の場合、パスワードとの相互必須チェック
			if ($old_login_id == "" && $this->request['login_passwd'] != "")
			{
				$this->oMgr->setErr('E001',"統合ID");
			}
		}
		else if (!$this->oMgr->checkEmpty($this->request['login_id']))
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
			else if ($this->request['login_id'] != "" && $this->request['is_unknown_user'])
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
			$this->oMgr->setErr('E017',"メールアカウント");;
		}
		else if ($this->oMgr->existsMlistAcc($this->request['mail_acc']))
		{
			// メーリングリストとして使用中
			$this->oMgr->setErr('E017',"メールアカウント");
		}
		else if ($this->request['mail_acc'] == $this->request['login_id'])
		{
			// 変わった場合だけ
			if ($old_mail_acc != $this->request['mail_acc'])
			{
				$param = array();
				$param[0] = "メールアカウント";
				$param[1] = "統合ID以外の文字列";
				$this->oMgr->setErr('E004',$param);
			}
		}

		// 所属
		if (!$this->oMgr->checkEmpty($this->request['belong_chg_id']))
		{
			// エラーメッセージをセット
			//$this->oMgr->setErr('E007',"所属");
		}

		// 職種
		if (!$this->oMgr->checkEmpty($this->request['job_id']))
		{
			// エラーメッセージをセット
			//$this->oMgr->setErr('E007',"職種");
		}

		// 内線
		$this->request['naisen'] = string::zen2han($this->request['naisen']);
		if (!$this->oMgr->checkEmpty($this->request['naisen']))
		{
			// 任意
		}
		else if (!string::checkNumber($this->request['naisen'], 4))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = "内線";
			$param[1] = "半角数字4桁";
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

		// HIS連携する場合
		if ($this->request['his_flg'] == '1')
		{
			$this->request['staffcode'] = string::zen2han($this->request['staffcode']);
			$this->request['kananame'] = string::han2zen($this->request['kananame']);
			$this->request['password'] = string::zen2han($this->request['password']);
			$this->request['appcode'] = string::zen2han($this->request['appcode']);
			$this->checkHisData();
		}

		// 複数HIS連携データがある場合
		if (is_array($this->request['sub_his_flg']))
		{
			foreach ($this->request['sub_his_flg'] AS $no => $val)
			{
				if ($val != "1")
				{
					continue;
				}

				$this->request['sub_staffcode'][$no] = string::zen2han($this->request['sub_staffcode'][$no]);
				$this->request['sub_kananame'][$no] = string::han2zen($this->request['sub_kananame'][$no]);
				$this->request['sub_password'][$no] = string::zen2han($this->request['sub_password'][$no]);
				$this->request['sub_appcode'][$no] = string::zen2han($this->request['sub_appcode'][$no]);
				$this->checkHisData($no);
			}
		}

		$has_date_err = false;

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

	function checkHisData($no="")
	{
		$user_id = @$this->request['user_id'];

		if ($no =="")
		{
			$name = "[電カル連携(メイン)]";
			$send_date = $this->request['send_date'];
			$staffcode = $this->request['staffcode'];
			$kanjiname = $this->request['kanjiname'];
			$kananame = $this->request['kananame'];
			$password = $this->request['password'];
			$wardcode = $this->request['wardcode'];
			$professioncode = $this->request['professioncode'];
			$gradecode = $this->request['gradecode'];
			$deptcode = $this->request['deptcode'];
			$appcode = $this->request['appcode'];
			$validstartdate = $this->request['validstartdate'];
			$validenddate = $this->request['validenddate'];
		}
		else
		{
			$name = "[電カル連携(サブ" . $no .")]";
			$send_date = $this->request['sub_send_date'][$no];
			$staffcode = $this->request['sub_staffcode'][$no];
			$kanjiname = $this->request['sub_kanjiname'][$no];
			$kananame = $this->request['sub_kananame'][$no];
			$password = $this->request['sub_password'][$no];
			$wardcode = $this->request['sub_wardcode'][$no];
			$professioncode = $this->request['sub_professioncode'][$no];
			$gradecode = $this->request['sub_gradecode'][$no];
			$deptcode = $this->request['sub_deptcode'][$no];
			$appcode = $this->request['sub_appcode'][$no];
			$validstartdate = $this->request['sub_validstartdate'][$no];
			$validenddate = $this->request['sub_validenddate'][$no];
		}

		// 送信日
		if (!$this->oMgr->checkEmpty($send_date))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',$name."有効開始日");
		}
		else if (!$this->oMgr->checkDateFormat($send_date))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = $name."有効開始日";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
		}
		else if (!$this->oMgr->checkDate($send_date))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',$name."有効開始日");
		}

		// ログインID
		if (!$this->oMgr->checkEmpty($staffcode))
		{
			$this->oMgr->setErr('E001',$name."ログインID");
		}
		else if (!string::checkNumber($staffcode, 8))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = $name."ログインID";
			$param[1] = "半角数字8桁";
			$this->oMgr->setErr('E004', $param);
		}
		else if ($this->oMgr->existsStaffcode($staffcode, $user_id))
		{
			$this->oMgr->setErr('E017',$name."ログインID");
		}

		// 漢字氏名
		if (!$this->oMgr->checkEmpty($kanjiname))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',$name."漢字氏名");
		}

		// カナ姓

		$kananame = str_replace("　", "", $kananame);
		if (!$this->oMgr->checkEmpty($kananame))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',$name."カナ氏名");
		}
		else if (!string::chackKatakana3($kananame))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = $name.'カナ氏名';
			$param[1] = 'カタカナ';
			$this->oMgr->setErr('E004', $param);
		}

		// HISパスワード

		if (!$this->oMgr->checkEmpty($password))
		{
			// 任意
		}
		else
		{
			//if (!string::checkAlphanumWide($password, 6, 10) || !ereg("[0-9]", $password) || !ereg("[a-z]", $password) || !ereg("[A-Z]", $password))
			if (!string::checkAlphanumWide($password, 4, 10))
			{
				$param = array();
				$param[0] = $name."HISパスワード";
				$param[1] = "半角英数字4～10文字";

				// エラーメッセージをセット
				$this->oMgr->setErr('E004', $param);
			}
		}

		// 部署
		if (!$this->oMgr->checkEmpty($wardcode))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',$name."部署");
		}

		// 職種
		if (!$this->oMgr->checkEmpty($professioncode))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',$name."職種");
		}

		// 役職
		if (!$this->oMgr->checkEmpty($gradecode))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',$name."役職");
		}

		// 診療科
		if (!$this->oMgr->checkEmpty($deptcode))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E007',$name."診療科");
		}

		// 予約項目コード
		if (!$this->oMgr->checkEmpty($appcode))
		{
			// 任意
		}
		else if (!string::checkAlphaNum($appcode, 5))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = $name."予約項目コード";
			$param[1] = "半角英数字5桁";
			$this->oMgr->setErr('E004', $param);
		}

		$has_validdate_err = false;

		// 有効期間
		if (!$this->oMgr->checkEmpty($validstartdate))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',$name."有効期間(開始日)");
			$has_validdate_err = true;
		}
		else if (!$this->oMgr->checkDateFormat($validstartdate))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = $name."有効期間(開始日)";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
			$has_validdate_err = true;
		}
		else if (!$this->oMgr->checkDate($validstartdate))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',$name."有効期間(開始日)");
			$has_validdate_err = true;
		}

		// 有効期間
		if (!$this->oMgr->checkEmpty($validenddate))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E001',$name."有効期間(終了日)");
			$has_validdate_err = true;
		}
		else if (!$this->oMgr->checkDateFormat($validenddate))
		{
			// エラーメッセージをセット
			$param = array();
			$param[0] = $name."有効期間(終了日)";
			$param[1] = "yyyy/mm/dd";
			$this->oMgr->setErr('E004', $param);
			$has_validdate_err = true;
		}
		else if (!$this->oMgr->checkDate($validenddate))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E013',$name."有効期間(終了日)");
			$has_validdate_err = true;
		}

		if (!$has_validdate_err)
		{
			if (!$this->oMgr->checkDateTerm($validstartdate, $validenddate))
			{
				// エラーメッセージをセット
				$param = array();
				$param[0] = $name."有効期間(開始日)";
				$param[1] = $name."有効期間(終了日)";
				$this->oMgr->setErr('E012',$param);
			}
		}

	}
}

?>
