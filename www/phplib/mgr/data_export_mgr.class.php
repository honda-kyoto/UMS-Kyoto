<?php
/**********************************************************
* File         : data_export_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/data_export_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class data_export_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getTargetList()
	{
		$args = array();
		if ($this->hasAdminActType('data_export.php'))
		{
			$args['COND'] = "";
		}
		else
		{
			$user_id = $this->getSessionData('LOGIN_USER_ID');
			$args['COND'] = "WHERE EXISTS (SELECT * FROM user_role_tbl WHERE user_role_mst.user_role_id = role_id AND del_flg = '0' AND user_id = " . string::replaceSql($user_id) . ")";
		}

		$sql = $this->getQuery('GET_TARGET_LIST', $args);

		$aryRet = $this->oDb->getAssoc2Ary($sql);

		return $aryRet;
	}

	function getTargetData($role_id)
	{
		$sql = $this->getQuery('GET_TARGET_DATA', $role_id);

		$aryRet = $this->oDb->getRow($sql);

		$mode = $aryRet['mode_name'];
		$title = $aryRet['user_role_name'];

		return array($mode, $title);
	}

	function downloadData($role_id, $file)
	{
		list($mode, $title) = $this->getTargetData($role_id);

		$extension = pathinfo($file, PATHINFO_EXTENSION);

		$file_path = EXPTEMP_PATH . $file;

		$strUser = file_get_contents($file_path);

		unlink ($file_path);

		$filename = $title . "用データ_" . date("YmdHis") . "." . $extension;
		$this->strDl($filename, $strUser);
	}

	function outputData($role_id)
	{
		list($mode, $title) = $this->getTargetData($role_id);

		if ($mode == "")
		{
			exit;
		}

		$funcMode = ucfirst($mode);
		$funcName = "output" . $funcMode . "Data";

		$file = $this->{$funcName}($mode);

		echo $file;
	}

	function outputAttend8Data($mode)
	{
		return $this->outputAttendData($mode, 8);
	}

	function outputAttendData($mode, $code_len=6)
	{
		$sql = $this->getQuery('GET_ATTEND_OUTPUT_DATA');

		$aryRet = $this->oDb->getAll($sql);

		$strUser = "";
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $data)
			{
				// 電カル連携情報	ログインID（5ケタ（前１ケタ、後ろ２桁削る））
				$staffcode = substr($data['staffcode'], 1, 5);
				$strUser .= '"' . $staffcode . '"';

				// 基本情報	氏名　姓
				$strUser .= ',"' . $data['kanjisei'] . '"';

				// 基本情報	　　　　名
				$strUser .= ',"' . $data['kanjimei'] . '"';

				// 基本情報	カナ　姓
				$strUser .= ',"' . $data['kanasei'] . '"';

				// 基本情報	　　　名
				$strUser .= ',"' . $data['kanamei'] . '"';

				// 基本情報	氏名英字　姓
				$strUser .= ',"' . $data['eijisei'] . '"';

				// 基本情報	　　　　　　　名
				$strUser .= ',"' . $data['eijimei'] . '"';

				// 基本情報	旧姓
				$strUser .= ',"' . $data['kyusei'] . '"';

				// 基本情報	組織メイン　①
				$strUser .= ',"' . $data['belong_class_name'] . '"';

				// 基本情報	組織メイン　②
				$strUser .= ',"' . $data['belong_div_name'] . '"';

				// 基本情報	組織メイン　③
				$strUser .= ',"' . $data['belong_dep_name'] . '"';

				// 基本情報	組織メイン　④
				$strUser .= ',"' . $data['belong_sec_name'] . '"';

				// 基本情報	組織メイン　⑤
				$strUser .= ',"' . $data['belong_chg_name'] . '"';

				// 空白	空白
				$strUser .= ',""';

				// 基本情報	役職メイン
				$strUser .= ',"' . $data['post_name'] . '"';

				// 空白	空白
				$strUser .= ',""';

				// 基本情報	職種
				$strUser .= ',"' . $data['job_name'] . '"';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 基本情報	統合ID
				$strUser .= ',"' . $data['login_id'] . '"';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 電カル連携情報	ログインID（6ケタor8ケタ）
				$card_no = substr($data['staffcode'], 0, $code_len);
				$strUser .= ',"' . $card_no . '"';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				// 空白	空白
				$strUser .= ',""';

				$strUser .= "\r\n";
			}
		}

		$file = $mode . microtime(true) . ".csv";
		$file_path = EXPTEMP_PATH . $file;

		file_put_contents($file_path, $strUser);

		return $file;

		/*
		$filename = $title . "用データ_" . date("YmdHis") . ".csv";
		$this->strDl($filename, $strUser);
		*/
	}

	function outputInfectionData($mode)
	{
		return $this->outputLibraryData($mode, true);
	}

	function outputLibraryData($mode, $is_inf_format=false)
	{
		$tel_len = 12;
		$pbno_len = 3;
		if ($is_inf_format)
		{
			$tel_len = 13;
			$pbno_len = 4;
		}

		$sql = $this->getQuery('GET_LIBRARY_OUTPUT_DATA');

		$aryRet = $this->oDb->getAll($sql);

		$strUser = "";
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $data)
			{
				// 固定
				$strUser .= "1601";

				// 職員コード
				$strUser .= str_pad($data['staffcode'], 10);

				// 新旧フラグ固定
				$strUser .= "0";

				// 予備
				$strUser .= str_pad("", 15);

				// 所属部署コード
				$strUser .= str_pad($data['wardcode'], 5);

				// 職種コード
				$strUser .= str_pad($data['professioncode'], 3);

				// パスワード
				$password = $data['password'];
				$password = $this->passwordDecrypt($password);
				if (strlen($password) > 5)
				{
					$password = substr($password, 0, 5);
				}
				$strUser .= str_pad($password, 5);

				// 職員カナ名称
				$kananame = str_replace("　", " ", $data['kananame']);
				$kananame = string::zen2han($kananame);
				$strUser .= string::mb_str_pad($kananame, 20);

				// 職員漢字名称
				$strUser .= string::mb_str_pad($data['kanjiname'], 20, " ");

				// 発行番号
				$strUser .= str_pad("", 4);

				// 給与職員番号
				$strUser .= str_pad("", 4);

				// 所属科コード
				$strUser .= str_pad($data['deptcode'], 2);

				// 役職コード
				$strUser .= str_pad($data['gradecode'], 2);

				// 棒給表ｺｰﾄﾞ
				$strUser .= str_pad("", 2);

				// 所属科コード
				$strUser .= str_pad($data['deptcode'], 2);

				// 予約項目コード
				$strUser .= str_pad($data['appcode'], 5);

				// 予備
				$strUser .= str_pad("", 19);

				// ローマ字氏名
				$eijiname = $data['eijiname'];
				if (strlen($eijiname) > 25)
				{
					list ($sei, $mei) = explode(".", $eijiname);
					$sei = substr($sei, 0, 1);
					$eijiname = $sei . "." . $mei;
				}
				$strUser .= str_pad($eijiname, 25);

				// 住所
				$strUser .= string::mb_str_pad("", 60, "　");

				// 備考
				$note = string::han2zen($date['note']);
				$note = string::nr2null($note);
				if (string::strlen($note) > 60)
				{
					$note = mb_substr($note, 0, 60, 'UTF-8');
				}
				$strUser .= string::mb_str_pad($note, 60, "　");

				// 電話番号
				$strUser .= str_pad("", $tel_len);

				// 生年月日
				$strUser .= str_pad($data['birthday'], 8);

				// 性別
				$sex = "";
				if ($data['sex'] == "0")
				{
					$sex = "M";
				}
				else if ($data['sex'] == "1")
				{
					$sex = "F";
				}
				$strUser .= str_pad($sex, 1);

				// 終了区分
				$strUser .= "0";

				// PHS番号
				$pbno_start = 4 - $pbno_len;
				$pbno = substr($data['pbno'], $pbno_start, $pbno_len);
				$strUser .= str_pad($pbno, $pbno_len);

				// 内線
				$strUser .= substr(str_pad($data['naisen'], 4), 0, 4);

				// 有効開始日
				$strUser .= str_pad($data['validstartdate'], 8);

				// 有効終了日
				$strUser .= str_pad($data['validenddate'], 8);

				// 予備
				$strUser .= str_pad("", 10);

				// 更新日
				$strUser .= str_pad($data['send_date'], 8);

				// 更新端末
				$strUser .= str_pad("", 4);

				// 更新者
				$strUser .= str_pad($data['update_staffcode'], 10);

				// 部署名
				$strUser .= string::mb_str_pad($data['wardname'], 40, "　");

				// 所属名
				$strUser .= string::mb_str_pad($data['deptname'], 20, "　");

				// 役職名
				$strUser .= string::mb_str_pad($data['gradename'], 20, "　");

				// 棒給表名
				$strUser .= string::mb_str_pad("", 10, "　");

				// 予備
				$strUser .= str_pad("", 5);

				// 扉許可情報
				$strUser .= "111111111111111111111111111111111111111111111111111111111111";

				$strUser .= "\r\n";

			}
		}

		$file = $mode . microtime(true) . ".txt";
		$file_path = EXPTEMP_PATH . $file;

		file_put_contents($file_path, $strUser);

		return $file;

		/*
		$filename = $title . "用データ_" . date("YmdHis") . ".txt";

		$this->strDl($filename, $strUser);
		*/
	}

	function outputOperatorData($mode, $code_len=6)
	{
		$sql = $this->getQuery('GET_OPERATOR_OUTPUT_DATA');

		$aryRet = $this->oDb->getAll($sql);

		$strUser = "";
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $data)
			{
				// 基本情報	ログインID
				$strUser .= '"' . $data['login_id'] . '"';

				// 基本情報	職員ID
				$strUser .= ',"' . $data['staff_id'] . '"';

				// 基本情報	職員コード
				$strUser .= ',"' . $data['staffcode'] . '"';

				// 基本情報	氏名
				$strUser .= ',"' . $data['kanjiname'] . '"';

				// 基本情報	カナ
				$strUser .= ',"' . $data['kananame'] . '"';

				// 基本情報	旧姓
				$strUser .= ',"' . $data['kyusei'] . '"';

				// 基本情報	性別
				$strUser .= ',"' . $data['sex'] . '"';

				// 基本情報	生年月日
				$strUser .= ',"' . $data['birthday'] . '"';

				// 基本情報	常勤区分
				$strUser .= ',"' . $data['joukin_kbn'] . '"';

				// 基本情報	メールアドレス
				$strUser .= ',"' . $data['mail_addr'] . '"';

				// 基本情報	役職名
				$strUser .= ',"' . $data['post_name'] . '"';

				// 基本情報	職種名
				$strUser .= ',"' . $data['job_name'] . '"';

				// 基本情報	所属名
				$strUser .= ',"' . $data['belong_name'] . '"';

				// 基本情報	内線
				$strUser .= ',"' . $data['naisen'] . '"';

				// 基本情報	PHS
				$strUser .= ',"' . $data['pbno'] . '"';

				$strUser .= "\r\n";
			}
		}

		$file = $mode . microtime(true) . ".csv";
		$file_path = EXPTEMP_PATH . $file;

		file_put_contents($file_path, $strUser);

		return $file;

	}


	function outputElearningData($mode)
	{
		$sql = $this->getQuery('GET_ELEARNING_OUTPUT_DATA');

		$aryRet = $this->oDb->getAll($sql);

		$strUser = "";
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $data)
			{
				// 統合ＩＤ
				$strUser .= '"' . $data['login_id'] . '"';

				// カナ氏名
				$strUser .= ',"' . $data['kanasei'] . "　" . $data['kanamei'] . '"';

				// 漢字氏名
				$strUser .= ',"' . $data['kanjisei'] . "　" . $data['kanjimei'] . '"';

				// 所属１
				$strUser .= ',"' . $data['belong_class_name'] . '"';

				// 所属２
				$strUser .= ',"' . $data['belong_div_name'] . '"';

				// 所属３
				$strUser .= ',"' . $data['belong_dep_name'] . '"';

				// 所属４
				$strUser .= ',"' . $data['belong_sec_name'] . '"';

				// 所属５
				$strUser .= ',"' . $data['belong_chg_name'] . '"';

				// 職種
				$strUser .= ',"' . $data['job_name'] . '"';

				// 役職
				$strUser .= ',"' . $data['post_name'] . '"';

				// メールアドレス
				$strUser .= ',"' . $data['mail_acc'] . USER_MAIL_DOMAIN . '"';

				// 有効開始日
				$strUser .= ',"' . $data['start_date'] . '"';

				// 有効終了日
				$strUser .= ',"' . $data['end_date'] . '"';

				$strUser .= "\r\n";
			}
		}

		$file = $mode . microtime(true) . ".csv";
		$file_path = EXPTEMP_PATH . $file;

		file_put_contents($file_path, $strUser);

		return $file;

	}


	function outputCardjoukinData($mode)
	{
		return $this->outputCardData($mode, 'joukin');
	}

	function outputCardhijoukinData($mode)
	{
		return $this->outputCardData($mode, 'hijoukin');
	}

	function outputCardData($mode, $kbn)
	{
		$args = array();
		if ($kbn == 'joukin')
		{
			$args['JOUKIN_KBN'] = " AND joukin_kbn in ('" . JOUKIN_KBN_FULLTIME . "','" . JOUKIN_KBN_PARTTIME . "')";
		}
		else
		{
			$args['JOUKIN_KBN'] = " AND joukin_kbn = '" . JOUKIN_KBN_OTHER . "'";
		}

		$sql = $this->getQuery('GET_USER_CARD_OUTPUT_DATA', $args);

		$aryRet = $this->oDb->getAll($sql);

		$arySex = $this->getAry('sex');

		$strUser = "";
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $data)
			{
				// 識別コード(職員番号)
				$strUser .= '"' . $data['ident_code'] . '"';

				// 漢字氏名
				$strUser .= ',"' . $data['kanjisei'] . "　" . $data['kanjimei'] . '"';

				// 英字氏名
				$eijiname = substr($data['eijimei'], 0, 1) . "." . $data['eijisei'];
				$eijiname = strtoupper($eijiname);
				$strUser .= ',"' . $eijiname . '"';

				// 生年月日
				$strUser .= ',"' . $data['birthday'] . '"';

				// 性別
				$sex = str_replace("性", "", $arySex[$data['sex']]);
				$strUser .= ',"' . $sex . '"';

				// 券種
				//$strUser .= ',"' . "" . '"';

				// 証明文書
				//$strUser .= ',"' . "" . '"';

				// 発行年月日
				$strUser .= ',"' . $data['first_issue_date'] . '"';

				// 発行回数
				$strUser .= ',"' . sprintf("%02d", $data['issue_cnt']) . '"';

				// 部署名
				$strUser .= ',"' . $data['belong_name'] . '"';

				// 職種
				$strUser .= ',"' . $data['job_name'] . '"';

				// 役職
				$strUser .= ',"' . $data['post_name'] . '"';

				// カナ氏名（半角）
				$kananame = $data['kanasei'] . $data['kanamei'];
				$kananame = string::zen2han($kananame);
				$strUser .= ',"' . $kananame . '"';

				// 有効期限
				$strUser .= ',"' . $data['end_date'] . '"';

				// 役職印刷名
				//$strUser .= ',"' . $data['post_name'] . '"';

				if ($kbn == 'joukin')
				{
					// 識別コード(バーコード)
					$strUser .= ',"' . $data['ident_code'] . '"';
				}

				$strUser .= "\r\n";
			}
		}

		$file = $mode . microtime(true) . ".csv";
		$file_path = EXPTEMP_PATH . $file;

		file_put_contents($file_path, $strUser);

		return $file;

	}

	function outputKakenhiData($mode)
	{
		$sql = $this->getQuery('GET_USER_KAKENHI_OUTPUT_DATA');

		$aryRet = $this->oDb->getAll($sql);

		$arySex = $this->getAry('sex');

		$strUser = "";
		if (is_array($aryRet))
		{
			foreach ($aryRet AS $data)
			{
				// 統合ID
				$strUser .= '"' . $data['login_id'] . '"';

				// 漢字氏名
				$strUser .= ',"' . $data['kanjisei'] . "　" . $data['kanjimei'] . '"';

				// カナ氏名
				$strUser .= ',"' . $data['kanasei'] . "　" . $data['kanamei'] . '"';

				// 性別
				$sex = str_replace("性", "", $arySex[$data['sex']]);
				$strUser .= ',"' . $sex . '"';

				// 所属１
				$strUser .= ',"' . $data['belong_class_name'] . '"';

				// 所属２
				$strUser .= ',"' . $data['belong_div_name'] . '"';

				// 所属３
				$strUser .= ',"' . $data['belong_dep_name'] . '"';

				// 所属４
				$strUser .= ',"' . $data['belong_sec_name'] . '"';

				// 所属５
				$strUser .= ',"' . $data['belong_chg_name'] . '"';

				// 職種
				$strUser .= ',"' . $data['job_name'] . '"';

				// 役職
				$strUser .= ',"' . $data['post_name'] . '"';

				// 有効期限
				$strUser .= ',"' . $data['start_date'] . '"';
				$strUser .= ',"' . $data['end_date'] . '"';

				$strUser .= "\r\n";
			}
		}

		$file = $mode . microtime(true) . ".csv";
		$file_path = EXPTEMP_PATH . $file;

		file_put_contents($file_path, $strUser);

		return $file;
	}

}

?>
