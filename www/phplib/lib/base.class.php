<?php
/**********************************************************
* File         : base.class.php
* Authors      : Mie Tsutsui
* Date         : 2009.07.11
* Last Update  : 2009.07.11
* Copyright    :
***********************************************************/
require_once("common.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class base
{
	var $aryErrMsg = array();

	function __construct()
	{
		// エラーメッセージ初期化
		$this->aryErrMsg = array();
	}

	//--------------------------------------------
	// 定義配列から値を取得
	//--------------------------------------------
	function getValue($name, $key)
	{
		return $GLOBALS[$name][$key];
	}

	//--------------------------------------------
	// 定義配列を取得
	//--------------------------------------------
	function getAry($name)
	{
		return $GLOBALS[$name];
	}

	//--------------------------------------------
	// 日の一覧を取得
	//--------------------------------------------
	function getDayAry()
	{
		for ($i = 1 ; $i <= 31 ; $i++)
		{
			$ary[$i] = $i;
		}

		return $ary;
	}

	//--------------------------------------------
	// 月の一覧を取得
	//--------------------------------------------
	function getMonthAry()
	{
		for ($i = 1 ; $i <= 12 ; $i++)
		{
			$ary[$i] = $i;
		}

		return $ary;
	}

	//--------------------------------------------
	// 年の一覧を取得
	//--------------------------------------------
	function getYearAry()
	{
		// 現在の年
		$cur_date = getdate();

		$ary[$cur_date['year']-1] = $cur_date['year']-1;
		$ary[$cur_date['year']]   = $cur_date['year'];
		$ary[$cur_date['year']+1] = $cur_date['year']+1;

		return $ary;
	}

	//--------------------------------------------
	// 年の一覧を取得
	//--------------------------------------------
	function getYearAryF()
	{
		// 現在の年
		$cur_date = getdate();

		$ary[$cur_date['year']]   = $cur_date['year'];
		$ary[$cur_date['year']+1] = $cur_date['year']+1;
		$ary[$cur_date['year']+2] = $cur_date['year']+2;

		return $ary;
	}

	//--------------------------------------------
	// 年の一覧を取得生年月日用
	//--------------------------------------------
	function getYearAryAge()
	{
		// 現在の年
		$cur_date = getdate();

		// 最小値
		$min = $cur_date['year'] - AGE_MAX;
		$max = $cur_date['year'] - AGE_MIN;

		for ($i = $min ; $i <= $max ; $i++)
		{
			$ary[$i] = $i;
		}

		return $ary;
	}

	function clearErrMsg()
	{
		$this->aryErrMsg = array();
	}

	//-----------------------------------------------
	// getErrMsg
	//
	// 処理概要：エラーメッセージ生成
	//-----------------------------------------------
	function getErrMsg()
	{
		$rtnErrMsg = "";
		$strMsgList = "";

		// エラーメッセージHTML変換
		foreach ($this->aryErrMsg AS $strMessage)
		{
			$strMsgList .= $this->getTpl('B001', $strMessage);
		}

		$rtnErrMsg = $this->getTpl('B002', $strMsgList);

		return $rtnErrMsg;
	}

	//--------------------------------------------
	// 関数名		: checkEmpty()
	// 機能			: 空文字チェック
	//--------------------------------------------
	function checkEmpty($value)
	{
		if ($value == "")
		{
			return false;
		}

		return true;
	}

	//--------------------------------------------
	// 関数名		: checkDateFormat()
	// 機能			: 日付フォーマットチェック（基本）
	//--------------------------------------------
	function checkDateFormat($date)
	{
		if ($date != "")
		{
			$date = str_replace("/", "-", $date);
			// 形式チェック
			if (!ereg("^[0-9]{4}-[0-9]{2}-[0-9]{2}$", $date))
			{
				return false;
			}
		}

		return true;
	}


	//--------------------------------------------
	// 関数名		: checkDate()
	// 機能			: 日付チェック（基本）
	//--------------------------------------------
	function checkDate($date)
	{
		if ($date != "")
		{
			$date = str_replace("/", "-", $date);
			$ary = split("-", $date);
			if (!checkdate($ary[1], $ary[2], $ary[0]))
			{
				return false;
			}
		}

		return true;
	}

	//--------------------------------------------
	// 関数名		: checkDateTerm()
	// 機能			: 日付期間チェック（基本）
	//--------------------------------------------
	function checkDateTerm($from, $to)
	{
		$from = str_replace("/", "-", $from);
		$to   = str_replace("/", "-", $to);

		$from = str_replace("-", "", $from);
		$to   = str_replace("-", "", $to);

		// 未入力チェック
		if ($from == "" or $to == "")
		{
		}
		else if ($from > $to)
		{
			return false;
		}

		return true;
	}

	/*======================================================
	* Name         : getDbError
	* IN           : フィールド名
	* OUT          : []
	*                [] 該当なし
	* Discription  : システムエラー画面へリダイレクト
	=======================================================*/
	function getDbError()
	{
		$msg = $this->oDb->getError();
		return $msg;
	}

	//--------------------------------------------
	// 関数名		: cacheControl()
	// 機能			:
	//--------------------------------------------
	function cacheControl()
	{

		session_cache_limiter("none");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");




/*
		//session_cache_limiter("none");
		session_cache_limiter('private, must-revalidate');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		//header("Cache-Control: no-store, no-cache, must-revalidate");
		//header("Cache-Control: post-check=0, pre-check=0", false);
		//header("Pragma: no-cache");
*/
	}

	//-----------------------------------------------
	// pushError
	//
	// 処理概要：エラーメッセージをクラス変数に格納します。
	//-----------------------------------------------
	function pushError($strMessage)
	{
		array_push($this->aryErrMsg, $strMessage);
	}

	//-----------------------------------------------
	// setErr
	//
	// 処理概要：エラーメッセージをクラス変数に格納します。
	//-----------------------------------------------
	function setErr($id, $args="")
	{
		$this->pushError($this->getMsg($id, $args));
	}

	//-----------------------------------------------
	// getTpl
	//
	// 処理概要：HTMLテンプレートを返す
	//-----------------------------------------------
	function getTpl($id, $args="")
	{
		global $cmnTpl ;
		$strMsg = "" ;
		$strTpl = "";
		if (array_key_exists($id, $cmnTpl ))
		{
			$strTpl = $cmnTpl[ $id ] ;
			if (is_array($args))
			{
				foreach ($args AS $key => $val)
				{
					$repStr = "{" . $key . "}";
					$strTpl = str_replace($repStr, $val, $strTpl);
				}
			}
			else
			{
				$strTpl = str_replace("{0}", $args, $strTpl);
			}
		}
		return $strTpl ;
	}

	//-----------------------------------------------
	// getMsg
	//
	// 処理概要：エラーメッセージを返す
	//-----------------------------------------------
	function getMsg($id, $args="")
	{
		global $cmnMsg ;
		$strMsg = "" ;
		if (array_key_exists($id, $cmnMsg ))
		{
			$strMsg = $cmnMsg[ $id ] ;
			if (is_array($args))
			{
				foreach ($args AS $key => $val)
				{
					$repStr = "{" . $key . "}";
					$strMsg = str_replace($repStr, $val, $strMsg);
				}
			}
			else
			{
				$strMsg = str_replace("{0}", $args, $strMsg);
			}
		}
		return $strMsg ;
	}

	//-----------------------------------------------
	// getQuery
	//
	// 処理概要：SQLクエリを返す
	//-----------------------------------------------
	function getQuery($id, $args="")
	{
		global $cmnSql ;
		$strSql = "" ;
		if (array_key_exists($id, $cmnSql ))
		{
			$strSql = $cmnSql[ $id ] ;
			if (is_array($args))
			{
				foreach ($args AS $key => $val)
				{
					if (ereg("^[0-9]+$", $key))
					{
						$val = string::replaceSql($val);
					}
					$repStr = "{" . $key . "}";
					$strSql = str_replace($repStr, $val, $strSql);
				}
			}
			else
			{
				$strSql = str_replace("{0}", string::replaceSql($args), $strSql);
			}
		}
		return $strSql ;
	}

	function get_enc_mimeheader($str)
	{
		$str = mb_convert_encoding($str, "ISO-2022-JP","UTF-8");
		ini_set('mbstring.internal_encoding', 'ISO-2022-JP');
		$str = mb_encode_mimeheader($str, "ISO-2022-JP");
		ini_set('mbstring.internal_encoding', 'UTF-8');
		return $str;
	}

	//-----------------------------------------------
	// sendSystemMail
	//
	// 処理概要：メールを送信
	//-----------------------------------------------
	function sendSystemMail($to, $subj, $body, $from, $fname, $cc, $bcc, $envelop, $return_path="")
	{
		//文字コード設定
		mb_language("Ja") ;
		mb_internal_encoding("UTF-8");

		//ヘッダ情報として加工
		$header = "From: ";
		if ($fname != "")
		{
			//$fname = mb_encode_mimeheader($fname, "ISO-2022-JP", "B");
			$fname = $this->get_enc_mimeheader($fname);
			$header .= $fname . " <" . $from . ">";
		}
		else
		{
			$header .= $from;
		}
		if ($cc != "")
		{
			$header .= "\ncc: " . $cc;
		}
		if ($bcc != "")
		{
			$header .= "\nBcc: " . $bcc;
		}

		if ($envelop == "")
		{
			$envelop = $from;
		}
		$sender = '-f'.$envelop;

		$header .= "\nX-Mailer: PHP/" . phpversion();

		if ($return_path != "")
		{
			ini_set("sendmail_from", $return_path);
		}

		//送信
		if (defined("_TEST_"))
		{
			$this->traceLog('mail', array($to, $subj, $body, $header, $sender));
			$ret = true;
		}
		elseif (defined("_RELEASE_"))
		{
			$ret = mb_send_mail($to, $subj, $body, $header, $sender);
		}

		return $ret;
	}

	//-----------------------------------------------
	// traceLog
	//
	// 処理概要：ログ出力
	//-----------------------------------------------
	function traceLog($type, $msg)
	{
		$filename = TRACELOG_PATH . $type . "_" . date("Ymd") . ".log";

		// ヘッダ
		$time   = date("Y/m/d H:i:s");
		$script = $_SERVER['SCRIPT_FILENAME'];

		$str  = "\n-----------------------------\n";
		$str .= "TIME:$time\nSCRIPT:$script\nDETAIL:\n";

		// 配列またはオブジェクトの場合文字列に変換
		if (is_array($msg) or is_object($msg))
		{
			$str .= string::obj2str($msg);
		}
		else
		{
			$str .= $msg;
		}

		file_put_contents($filename, $str, FILE_APPEND);

		// エラー処理は行わない
	}

	/**
	 * makeSelectOptions
	 */
	function makeSelectOptions($list, $current)
	{
		$tags = "";

		// リストが配列でなければ何も生成しない
		if (!is_array($list))
		{
			return "";
		}

		// リストの内容でタグを作る
		foreach ($list as $key => $value)
		{
			$args[0] = $key;
			$args[1] = "";
			if (is_array($current))
			{
				if (in_array($key, $current))
				{
					$args[1] = "selected";
				}
			}
			else
			{
				if ($current != "" && $key == $current)
				{
					$args[1] = "selected";
				}
			}
			$args[2] = htmlspecialchars($value);

			$tags .= $this->getTpl('B003', $args);
		}

		return $tags;
	}

	/**
	 * makeCheckBoxs
	 */
	function makeCheckBoxs($name, $list, $checked, $onClick="")
	{
		$tags = "";

		// リストが配列でなければ何も生成しない
		if (!is_array($list))
		{
			return "";
		}

		// リストの内容でタグを作る
		foreach ($list as $key => $value)
		{
			$args[0] = $name;
			$args[1] = $key;
			$args[2] = "";
			if (is_array($checked) && array_key_exists($key, $checked))
			{
				$args[2] = "checked";
			}
			if ($onClick != "")
			{
				$args[2] .= ' onClick="' . $onClick . '"';
			}
			$args[3] = htmlspecialchars($value);
			$tags .= $this->getTpl('B004', $args);
		}

		return $tags;
	}

	/**
	 * makeCheckBoxs
	 */
	function makeCheckBoxTable($name, $list, $checked, $limit="", $onClick="")
	{
		$cols = "";
		$rows = "";
		$tags = "";

		// リストが配列でなければ何も生成しない
		if (!is_array($list))
		{
			return "";
		}

		if ($limit == "")
		{
			$limit = count($list);
		}

		$tdmax = $limit * 2;

		$tdcnt = 0;
		// リストの内容でタグを作る
		foreach ($list as $key => $value)
		{
			$args[0] = $name;
			$args[1] = $key;
			$args[2] = "";

			if (is_array($checked) && array_key_exists($key, $checked))
			{
				$args[2] = "checked";
			}
			if ($onClick != "")
			{
				$args[2] .= ' onClick="' . $onClick . '"';
			}
			$args[3] = htmlspecialchars($value);
			$args[4] = $name . "_" . $key;
			$cols .= $this->getTpl('B012', $args);

			$tdcnt++;

			if ($tdcnt == $limit)
			{
				// 行作成
				$rows .= $this->getTpl('B011', $cols);
				// 初期化
				$cols = "";
				$tdcnt = 0;
			}
		}

		if ($tdcnt != 0 && $cols != "")
		{
			// 残りのデータで行作成
			$cols .= $this->getTpl('B013', $tdmax-$tdcnt);
			$rows .= $this->getTpl('B011', $cols);
		}

		// テーブル作成
		$tags = $this->getTpl('B010', $rows);

		return $tags;
	}

	/**
	 * makeRadioButtonTable
	 */
	function makeRadioButtonTable($name, $list, $checked, $limit="", $onClick="")
	{
		$cols = "";
		$rows = "";
		$tags = "";

		// リストが配列でなければ何も生成しない
		if (!is_array($list))
		{
			return "";
		}

		if ($limit == "")
		{
			$limit = count($list);
		}

		$tdmax = $limit * 2;

		$tdcnt = 0;
		// リストの内容でタグを作る
		foreach ($list as $key => $value)
		{
			$args[0] = $name;
			$args[1] = $key;
			$args[2] = "";

			if ($key == $checked)
			{
				$args[2] = "checked";
			}
			if ($onClick != "")
			{
				$args[2] .= ' onClick="' . $onClick . '"';
			}
			$args[3] = htmlspecialchars($value);
			$args[4] = $name . "_" . $key;
			$cols .= $this->getTpl('B014', $args);

			$tdcnt++;

			if ($tdcnt == $limit)
			{
				// 行作成
				$rows .= $this->getTpl('B011', $cols);
				// 初期化
				$cols = "";
				$tdcnt = 0;
			}
		}

		if ($tdcnt != 0 && $cols != "")
		{
			// 残りのデータで行作成
			$cols .= $this->getTpl('B013', $tdmax-$tdcnt);
			$rows .= $this->getTpl('B011', $cols);
		}

		// テーブル作成
		$tags = $this->getTpl('B010', $rows);

		return $tags;
	}


	function checkPageExists($max, $limit, &$cur)
	{
		if ($cur == "" || $cur <= 1)
		{
			$cur = 1;
			return true;
		}

		// 現在のページの開始値
		$start = (($cur - 1) * $limit) + 1;

		// 最大値と比較
		if ($start > $max)
		{
			// 減算
			$cur--;
			// 再チェック
			$this->checkPageExists($max, $limit, &$cur);
		}
		return true;
	}

	/**
	 * makeListNavi
	 */
	function makeListNavi($max, $limit, $cur)
	{
		$tags = "";
		$list = "";

		// 検索結果が０件の場合はリンクを作成しない
		if ($max == 0)
		{
			return $tags;
		}

		$strOffset = $limit * ($cur - 1);

		// 件数表示部分算出
		$strDispStart = $strOffset + 1;					// 表示開始数
		$strDispEnd   = $strOffset + $limit;			// 表示終了数
		if ($strDispEnd > $max)
		{
			$strDispEnd = $max;
		}

		// 最大ページ数算出
		if ($max % $limit > 0)
		{
			$strMaxPage = ceil($max / $limit);
		}
		else
		{
			$strMaxPage = $max / $limit;
		}

		// 先頭ページでない時、前リンク作成
		if ($cur != 1)
		{
			$args[0] = $cur - 1;
			$args[1] = $this->getTpl('B005');
			$list .= $this->getTpl('B007', $args);
		}

		$start = 1;
		$end = $strMaxPage;
		// ページ数が規定値を超えている場合
		if (defined("LIST_NAVI_MAX"))
		{
			if ($strMaxPage > LIST_NAVI_MAX)
			{
				// 現在のページのグループを作成
				if ($cur - (int)LIST_NAVI_MAX/2 > 0)
				{
					$start = $cur - (int)LIST_NAVI_MAX/2;
				}
				$end = $start + LIST_NAVI_MAX - 1;

				// 終了が最大を超えた場合
				if ($end > $strMaxPage)
				{
					// 開始位置を戻す
					$start = $strMaxPage - LIST_NAVI_MAX + 1;
					$end = $strMaxPage;
				}
			}
		}

		// スタートが1でない場合
		if ($start != 1)
		{
			$args[0] = 1;
			$args[1] = 1;
			$list .= $this->getTpl('B007', $args);
			$list .= "　...　";
		}

		// ページリンク作成
		for ($i = $start ; $i <= $end ; $i++)
		{
			$args[0] = $i;
			$args[1] = $i;

			if ($i == $cur)
			{
				$tpl_id = 'B008';
			}
			else
			{
				$tpl_id = 'B007';
			}


			$list .= $this->getTpl($tpl_id, $args);

		}

		// エンドが最終ページでない場合
		if ($end != $strMaxPage)
		{
			$list .= "　...　";
			$args[0] = $strMaxPage;
			$args[1] = $strMaxPage;
			$list .= $this->getTpl('B007', $args);
		}

		// 最終ページでない時、後リンク作成
		if ($cur != $strMaxPage)
		{
			$args[0] = $cur + 1;
			$args[1] = $this->getTpl('B006');
			$list .= $this->getTpl('B007', $args);
		}

		$args[0] = $max;
		$args[1] = $strDispStart;
		$args[2] = $strDispEnd;
		$args[3] = $list;

		$tags = $this->getTpl('B009', $args);

		return $tags;
	}

	/**
	 * getDateMD
	 */
	function getDateMD($date)
	{
		$ary = split("-", $date);
		$ret = (int)$ary[1] . "/" . (int)$ary[2];

		return $ret;
	}

	/**
	 * getTimeDeco
	 */
	function getTimeDeco($datetime)
	{
		list($date, $time) = split(" ", $datetime);
		$aryD = split("-", $date);
		$aryT = split(":", $time);

		$ret = $aryD[0] . "/" . (int)$aryD[1] . "/" . (int)$aryD[2];
		$dow = date("w", mktime(0,0,0,(int)$aryD[1], (int)$aryD[2], $aryD[0]));
		$dow_text = $this->getValue('dow_text', $dow);

		$ret .= "(" . $dow_text . ")";
		$ret .= $aryT[0] . ":" . (int)$aryT[1];
		return $ret;
	}

	/**
	 * getDateDecoYmd
	 */
	function getDateDecoYmd($date, $ny=false)
	{
		$ary = split("-", $date);
		if ($ny)
		{
			$ret = (int)$ary[1] . "/" . (int)$ary[2];
		}
		else
		{
			$ret = $ary[0] . "/" . (int)$ary[1] . "/" . (int)$ary[2];
		}
		$dow = date("w", mktime(0,0,0,(int)$ary[1], (int)$ary[2], $ary[0]));
		$dow_text = $this->getValue('dow_text', $dow);

		$ret .= "(" . $dow_text . ")";

		return $ret;
	}

	/**
	 * getDateDecoYmdHis
	 */
	function getDateDecoYmdHis($datetime, $ny=false, $ns=true)
	{
		$ary = split(" ", $datetime);

		$date = $this->getDateDecoYmd($ary[0],$ny);

		$aryTime = split(":", $ary[1]);
		if ($ns)
		{
			$time = $aryTime[0] . ":" . $aryTime[1];
		}
		else
		{
			$time = $aryTime[0] . ":" . $aryTime[1] . ":" . $aryTime[2];
		}

		$ret = $date . " " . $time;

		return $ret;
	}

	function computeDate($date, $addDays, $minus_flg=false)
	{
		if (!$minus_flg AND $addDays < 0)
		{
			$addDays = 0;
		}
		$ary = split("-", $date);
		$rettime = mktime(0, 0, 0, (int)$ary[1], (int)$ary[2] + $addDays, (int)$ary[0]);
		$ret = date("Y-m-d", $rettime);

		return $ret;
	}

	function compareDate($date1, $date2)
	{
		$ary1 = split("-", $date1);
		$ary2 = split("-", $date2);
		$dt1 = mktime(0, 0, 0, (int)$ary1[1], (int)$ary1[2], (int)$ary1[0]);
		$dt2 = mktime(0, 0, 0, (int)$ary2[1], (int)$ary2[2], (int)$ary2[0]);
		$diff = $dt1 - $dt2;
		$diffDay = $diff / 86400;

		return $diffDay;
	}

	function getNumDeco($val, $dec)
	{
		$val = number_format($val, $dec);
		for ($i = 0 ; $i < $dec ; $i++)
		{
			$val = ereg_replace("0$", "", $val);
		}
		$val = ereg_replace("\.$", "", $val);

		return $val;
	}

	//-----------------------------------------------
	// dataDl
	//
	// 処理概要：ダウンロード処理
	//-----------------------------------------------
	function fileDl($file, $path)
	{
		// SJISに変換
		$file = mb_convert_encoding($file, "sjis-win", "UTF-8");

		$size = filesize($path);

		header ("Cache-Control: public");
		header ("Pragma: public");
		header ("Content-Disposition: attachment; filename=" . $file);
		header ("Content-type: application/octet-stream");
		header ("Content-Transfer-Encoding: binary");
		header ("Content-length: " . $size);
		readfile($path);
		exit;
	}

	//-----------------------------------------------
	// strDl
	//
	// 処理概要：ダウンロード処理
	//-----------------------------------------------
	function strDl($file, $str)
	{
		$size = strlen($str);

		// SJISに変換
		$file = mb_convert_encoding($file, "sjis-win", "UTF-8");
		$str  = mb_convert_encoding($str, "sjis-win", "UTF-8");

		header ("Cache-Control: public");
		header ("Pragma: public");
		header ("Content-Disposition: attachment; filename=" . $file);
		header ("Content-type: application/octet-stream");
		header ("Content-Transfer-Encoding: binary");
		header ("Content-length: " . $size);
		print $str;
		exit;
	}

	//-----------------------------------------------
	// dataDl
	//
	// 処理概要：ダウンロード処理
	//-----------------------------------------------
	function dataDl($file, $ary, $header="")
	{
		$str = "";

		// 文字列作成
		if (is_array($header))
		{
			$str .= join("\r\n", $header);
			$str .= "\r\n";
		}
		else if ($header != "")
		{
			$str = $header . "\r\n";
		}

		if (is_array($ary) and sizeof($ary) > 0)
		{
			$str .= join("\r\n", $ary);
			$str .= "\r\n";
		}

		$size = strlen($str);

		// SJISに変換
		$file = mb_convert_encoding($file, "sjis-win", "UTF-8");
		$str  = mb_convert_encoding($str, "sjis-win", "UTF-8");

		header ("Cache-Control: public");
		header ("Pragma: public");
		header ("Content-Disposition: attachment; filename=" . $file);
		header ("Content-type: application/octet-stream");
		header ("Content-Transfer-Encoding: binary");
		header ("Content-length: " . $size);
		print $str;
		exit;
	}

	//-----------------------------------------------
	// aryToCsv
	//
	// 処理概要：ダウンロード処理
	//-----------------------------------------------
	function aryToCsv($ary)
	{
		$aryRet = "";

		if (is_array($ary) and sizeof($ary) > 0)
		{
			// 改行を取り除く
			$ary = string::nr2null($ary);

			foreach ($ary AS $key => $aryVal)
			{
				$dev = "";
				foreach ($aryVal AS $val)
				{
					$aryRet[$key] .= $dev;
					$aryRet[$key] .= '"' . $val . '"';
					$dev = ",";
				}
			}
		}

		return $aryRet;
	}

	/*
	 * リダイレクトでPOST送信
	 */
	function do_post_request($url, $data, $optional_headers = null)
	{
		$params = array('http' => array('method' => 'POST', 'content' => $data));
		if ($optional_headers !== null)
		{
			$params['http']['header'] = $optional_headers;
		}
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		if (!$fp)
		{
			//throw new Exception("Problem with $url, $php_errormsg");
		}
		$response = @stream_get_contents($fp);
		if ($response === false) {
			//throw new Exception("Problem reading data from $url,$php_errormsg");
		}
		return $response;
	}

	/*
	 * ls_array_marge
	 * $ary1 に　$ary2　を上書き
	 */
	function ls_array_marge($ary1, $ary2)
	{
		if (is_array($ary2))
		{
			foreach ($ary2 AS $key => $value)
			{
				/* 該当キー値が$ary1にある場合　かつ　$valueが配列の場合は再帰処理 */
				if (isset($ary1[$key]) && is_array($value))
				{
					$ary1[$key] = self::ls_array_marge($ary1[$key], $value);
				}
				else
				{
					$ary1[$key] = $value;
				}
			}
		}

		return $ary1;
	}
}
?>
