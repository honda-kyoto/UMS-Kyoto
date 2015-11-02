<?php
/**********************************************************
* File         : string.class.php
* Authors      : Mie Tsutsui
* Date         : 2006.09.25
* Last Update  : 2006.10.30
* Copyright    :
***********************************************************/

mb_internal_encoding("UTF-8");

define('MAGIC_QUOTES_GPC_OFF', '0');	//GPC(Get/Post/Cookie)処理に関するmagic_quotesの設定値：OFF
define('MAGIC_QUOTES_GPC_ON', '1');		//GPC(Get/Post/Cookie)処理に関するmagic_quotesの設定値：ON

class string
{
	/*チェック関数*/

	/*======================================================
	* Name         : checkNumber
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数が数字かどうかをチェックし、
	*                数字ならば true 数字でなければ false を返す
	=======================================================*/
	public static function checkNumber($value, $len = "")
	{
		if($len != "")
		{
			if (!ereg("^[0-9]{".$len."}$", $value))
			{
				return false;
			}
		}
		else
		{
			if (!ereg("^[0-9]+$", $value))
			{
				return false;
			}
		}
		return true;

	}

	/*======================================================
	* Name         : checkAlphanumWide
	* IN           : 文字列,最大サイズ, 最小サイズ
	* OUT          : 結果
	* Discription  : 引数が半角英数字かどうかをチェックし、
	*                半角英数字ならば true 半角英数字でなければ false を返す
	=======================================================*/
	public static function checkNumberWide($value, $min, $max)
	{
		if (!ereg("^[0-9]{".$min.",".$max."}$", $value))
		{
			return false;
		}
		return true;
	}

	/*======================================================
	* Name         : chkNumberAry
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 配列をまとめてチェックする
	=======================================================*/
	public static function chkNumberAry ($data)
	{
		// 配列の場合
		if(is_array($data))
		{
			foreach ($data AS $key => $val)
			{
				// $valが配列の場合再起呼び出し
				if (is_array($val))
				{
					if (!self::chkNumberAry($val))
					{
						return false;
					}
				}
				else
				{
					// 値がないものは対象外
					if ($val != "" && !self::checkNumber($val))
					{
						return false;
					}
				}
			}
		}

		return true;
	}

	/*======================================================
	 * Name         : checkAlphabet
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数が半角英数字かどうかをチェックし、
	*                半角英数字ならば true 半角英数字でなければ false を返す
	=======================================================*/
	public static function checkAlphabet($value, $len = "")
	{
		if($len != "")
		{
			if (!ereg("^[A-Za-z]{".$len."}$", $value))
			{
				return false;
			}
		}
		else
		{
			if (!ereg("^[A-Za-z]+$", $value))
			{
				return false;
			}
		}
		return true;
	}

	/*======================================================
	* Name         : checkAlphanum
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数が半角英数字かどうかをチェックし、
	*                半角英数字ならば true 半角英数字でなければ false を返す
	=======================================================*/
	public static function checkAlphanum($value, $len = "")
	{
		if($len != "")
		{
			if (!ereg("^[0-9A-Za-z]{".$len."}$", $value))
			{
				return false;
			}
		}
		else
		{
			if (!ereg("^[0-9A-Za-z]+$", $value))
			{
				return false;
			}
		}
		return true;
	}

	/*======================================================
	* Name         : checkAlphanumWide
	* IN           : 文字列, 最大サイズ, 最小サイズ
	* OUT          : 結果
	* Discription  : 引数が半角英数字かどうかをチェックし、
	*                半角英数字ならば true 半角英数字でなければ false を返す
	=======================================================*/
	public static function checkAlphanumWide($value, $min, $max)
	{
		if (!ereg("^[0-9A-Za-z]{".$min.",".$max."}$", $value))
		{
			return false;
		}
		return true;
	}

	/*======================================================
	* Name         : checkAlphanumPlus
	* IN           : 文字列, 長さ
	* OUT          : 結果
	* Discription  : 引数が半角英数字＋「-」かどうかをチェックし、
	*                半角英数字＋「-」ならば true
	*                半角英数字＋「-」でなければ false を返す。
	=======================================================*/
	public static function checkAlphanumPlus($value, $len = "")
	{
		if($len != "")
		{
			if (!ereg("^[-0-9A-Za-z_]{".$len."}$", $value))
			{
				return false;
			}
		}
		else
		{
			if (!ereg("^[-0-9A-Za-z_]+$", $value))
			{
				return false;
			}
		}
		return true;
	}

	/*======================================================
	* Name         : checkAlphanumWide
	* IN           : 文字列, 最大サイズ, 最小サイズ
	* OUT          : 結果
	* Discription  : 引数が半角英数字かどうかをチェックし、
	*                半角英数字ならば true 半角英数字でなければ false を返す
	=======================================================*/
	public static function checkAlphanumPlusWide($value, $min, $max)
	{
		if (!ereg("^[-0-9A-Za-z_]{".$min.",".$max."}$", $value))
		{
			return false;
		}
		return true;
	}

	/*======================================================
	* Name         : checkIpAddr
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数がIPアドレスのフォーマットとして
	*                妥当ならばtrue をそれ以外は false を返す
	=======================================================*/
	public static function checkIpAddr($value)
	{
		if (ereg("^([0-9]{1,3}\.){3}[0-9]{1,3}$", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*======================================================
	* Name         : checkMacAddr
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数がIPアドレスのフォーマットとして
	*                妥当ならばtrue をそれ以外は false を返す
	=======================================================*/
	public static function checkMacAddr($value)
	{
		if (ereg("^([0-9A-Za-z]{2}[-|:]?){5}[0-9A-Za-z]{2}$", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*======================================================
	* Name         : checkMailAddr
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数がメールアドレスのフォーマットとして
	*                妥当ならばtrue をそれ以外は false を返す
	=======================================================*/
	public static function checkMailAddr($value)
	{
		// 特殊処理
		/*
		 * 携帯メールアドレスにダブルクォートを
		 * 使用している人が存在したため、その対応。
		 */
		$value = str_replace ("\"", "", $value);
		if (ereg("^([-0-9A-Za-z_./])+@[-0-9A-Za-z_]+(\.[-0-9A-Za-z_]+)+$", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function checkMimeType($value)
	{
		if (ereg("^[-0-9A-Za-z]+/[-0-9A-Za-z_.]+$", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function chackKatakana3( $value )
	{
		mb_regex_encoding("UTF-8");
		if ($value == "")
		{
			return true;
		}

		if (preg_match("/^[ァ-ヶー]+$/u", $value)) {
			return true;
		}

		return false;
	}

	/*======================================================
	* Name         : checkKatakana2
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数がカタカナかどうかをチェックし、
	*                カタカナならば true カタカナでなければ false を返す
	=======================================================*/
	public static function checkKatakana2( $value )
	{
		$isValid = (preg_match("/^[ァ-ヾ]+$/u",$value )) ? FALSE : TRUE ;
		return $isValid ;
	}

	/*======================================================
	* Name         : checkKatakana1
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数がカタカナかどうかをチェックし、
	*                カタカナならば true カタカナでなければ false を返す
	=======================================================*/
	public static function checkKatakana1( $value )
	{
		$isValid = ( mb_ereg_match( '[^ヲ-゜]', trim($value) ) ) ? FALSE : TRUE ;
		return $isValid ;
	}

	/*======================================================
	* Name         : chkJIS3over
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 第３水準以上、または外字はエラー
	=======================================================*/
	function chkJIS3over($target, &$rtn)
	{
		$rtn = "";


		for($idx = 0; $idx < mb_strlen($target, 'utf-8'); $idx++)
		{
			$str0 = mb_substr($target, $idx, 1, 'utf-8');
			// 1文字をSJISにする。
			$str = mb_convert_encoding($str0, "sjis-win", 'utf-8');
			Debug_Trace($str, 9876) ;
			if ((strlen(bin2hex($str)) / 2) == 1)
			{ // 1バイト文字
				$c = ord($str{0});
				if ($str == "?" && $str0 != "?")
				{
					$rtn .= $str0;
				}
			}
			else
			{
				$c = ord($str{0}); // 先頭1バイト
				$c2 = ord($str{1}); // 2バイト目
				$c3 = ($c * 0x100 + $c2); // 2バイト分の数値にする。
				Debug_Trace($c3, 9876) ;

				if ((($c3 >= 0x8140) && ($c3 <= 0x853D)) || // 2バイト文字
						(($c3 >= 0x889F) && ($c3 <= 0x9872)) || // 第一水準
						(($c3 >= 0x989F) && ($c3 <= 0x9FFC)) || // 第二水準
						(($c3 >= 0xE040) && ($c3 <= 0xEAA4)) || // 第二水準
						(($c3 >= 0xFA40) && ($c3 <= 0xFC4B)))   // IBM拡張文字
				{

				}
				else
				{
					$rtn .= $str0;
					//echo "機種依存文字など" . "\n";
				}
			}
		}


		/*
		for($idx = 0; $idx < mb_strlen($target, 'utf-8'); $idx++)
		{
			$str = mb_substr($target, $idx, 1, 'utf-8');

			// iso-2022-jpに一旦文字コード変換後戻したものが下の文字列と同じならOK
			$str0 = mb_convert_encoding($str, "sjis-win", 'utf-8');
			$str1 = mb_convert_encoding($str0, 'utf-8', "sjis-win");

			if ($str != $str1)
			{
				$rtn .= $str;
			}
		}
		*/

		if ($rtn == "")
		{
			return true;
		}

		return false;
	}


	/*======================================================
	* Name         : obj2str
	* IN           : オブジェクト
	* OUT          : 結果
	* Discription  : 展開して文字列として返す
	=======================================================*/
	public static function obj2str( $obj )
	{
		ob_start() ;
		var_dump( $obj ) ;
		$result = ob_get_contents() ;
		ob_end_clean() ;
		return $result ;
	}

	/*======================================================
	* Name         : checkURIHeader
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数がhttp://かhttps://で始まる文字列であればtrue
	*                それ以外は false を返す。
	=======================================================*/
	public static function checkURIHeader($value)
	{
		if (ereg("^https?://", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*======================================================
	* Name         : checkURIHeader2
	* IN           : 文字列
	* OUT          : 結果
	* Discription  : 引数がhttp://かhttps://で始まる文字列であればtrue
	*                それ以外は false を返す。
	*                スラッシュで終わらなければならないものをチェック
	=======================================================*/
	public static function checkURIHeader2($value)
	{
		if (ereg("^https?://.+/$", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*変換関数*/

	public static function strlen($str, $encode='UTF-8')
	{
		$len = 0;
		$count = mb_strwidth($str, $encode);
		for ($i=0; $i<$count; $i++)
		{
			$s = substr($str, $i, 1);
			$l = strlen(bin2hex($s)) / 2;
			if ($l == 1)
			{
				$len++;
			}
			else
			{
				$len = $len + 2;
			}
		}

		$count = mb_strlen($str);
		for ($i=0; $i<$count; $i++)
		{
			$s = mb_substr($str, $i, 1);
			if ($s == '○')
			{
				$len++;
			}
		}
		return $len;
	}

	public static function mb_str_pad($str, $len, $pad=" ")
	{
		$str_len = self::strlen($str);

		if ($str_len > $len)
		{
			return mb_substr($str, 0, $len);
		}

		while ($str_len < $len)
		{
			$str .= $pad;
			$str_len = self::strlen($str);
		}

		return $str;
	}

	/*======================================================
	* Name         : han2zen
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : 引数の文字列の全ての半角文字を全角に変換
	=======================================================*/
	public static function han2zen($value)
	{
		$value = mb_convert_kana($value, "AKV", mb_internal_encoding());
		return $value;
	}

	/*======================================================
	* Name         : hira2kana
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : 引数の文字列の仮名を全て全角カタカナにする
	=======================================================*/
	public static function hira2kana($value)
	{
		$value = mb_convert_kana($value, "KVC", mb_internal_encoding());
		return $value;
	}


	/*======================================================
	* Name         : hankana2zen
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : 引数の文字列の半角カナを全角に変換
	=======================================================*/
	public static function hankana2zen($value)
	{
		$value = mb_convert_kana($value, "KV", mb_internal_encoding());
		return $value;
	}


	/*======================================================
	* Name         : zen2han
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : 全角カタカナと英数字を半角に変換する
	=======================================================*/
	public static function zen2han($value)
	{
		$value = mb_convert_kana($value, "ak", mb_internal_encoding());
		return $value;
	}

	/*======================================================
	* Name         : removeEscape
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : addslashesでクォートされた文字列のクォート部分を取り除く
	=======================================================*/
	public static function removeEscape ($data, $zen2han=array())
	{
		//GPC(Get/Post/Cookie)処理に関するmagic_quotesの設定値：ON
		if (get_magic_quotes_gpc() == MAGIC_QUOTES_GPC_ON)
		{
			// 配列の場合
			if(is_array($data))
			{
				foreach ($data AS $key => $val)
				{
					// $valが配列の場合再起呼び出し
					if (is_array($val))
					{
						$keys = array();
						if (in_array($key, $zen2han))
						{
							$keys = array_keys($data[$key]);
						}
						$data[$key] = self::removeEscape ($val, $keys);
					}
					else
					{
						$data[$key] = stripslashes($data[$key]);	// addslashesでクォートされた文字列のクォート部分を取り除く
						if (in_array($key, $zen2han))
						{
							$data[$key] = self::zen2han($data[$key]);
						}
					}
				}
			}
			// 変数の場合
			else
			{
				$data = stripslashes($data);	// addslashesでクォートされた文字列のクォート部分を取り除く
			}
		}
		return $data;
	}

	/*======================================================
	* Name         : replaceSql
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : 渡された値をSQL用に置換する
	=======================================================*/
	public static function replaceSql($data)
	{
		// 配列の場合
		if (is_array($data))
		{
			foreach ($data AS $key => $val)
			{
				// $valが配列の場合再起呼び出し
				if (is_array($val))
				{
					self::replaceSql($val);
				}
				else
				{
					$data[$key] = pg_escape_string($data[$key]);
				}
			}
		}
		// 変数の場合
		else
		{
			$data = pg_escape_string($data);
		}
		return $data;
	}

	/*======================================================
	* Name         : nr2null
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : 引数の改行コードを取り除く
	=======================================================*/
	public static function nr2null($data)
	{
		// 配列の場合
		if (is_array($data))
		{
			foreach ($data AS $key => $val)
			{
				// $valが配列の場合再起呼び出し
				if (is_array($val))
				{
					self::nr2null($val);
				}
				else
				{
					$data[$key] = ereg_replace("\r\n", "", $val);
					$data[$key] = ereg_replace("\r"  , "", $val);
					$data[$key] = ereg_replace("\n"  , "", $val);
				}
			}
		}
		// 変数の場合
		else
		{
			$data = ereg_replace("\r\n", "", $data);
			$data = ereg_replace("\r"  , "", $data);
			$data = ereg_replace("\n"  , "", $data);
		}
		return $data;
	}

	/*======================================================
	* Name         : nr2strnl
	* IN           : 文字列
	* OUT          : 文字列
	* Discription  : 引数の改行コードを文字にする
	=======================================================*/
	public static function nr2strnl($data)
	{
		// 配列の場合
		if (is_array($data))
		{
			foreach ($data AS $key => $val)
			{
				// $valが配列の場合再起呼び出し
				if (is_array($val))
				{
					self::nr2strnl($val);
				}
				else
				{
					$data[$key] = ereg_replace("\r\n", "\\r\\n", $val);
					$data[$key] = ereg_replace("\r"  , "\\r", $val);
					$data[$key] = ereg_replace("\n"  , "\\n", $val);
				}
			}
		}
		// 変数の場合
		else
		{
			$data = ereg_replace("\r\n", "\\r\\n", $data);
			$data = ereg_replace("\r"  , "\\r", $data);
			$data = ereg_replace("\n"  , "\\n", $data);
		}
		return $data;
	}

	public static function mb_trim($string)
 	{
		mb_regex_encoding("UTF-8"); // 本当はmb_trim呼び出し以前に1回実行すれば十分
		$whitespace = '[\0\s]';
		$ret = mb_ereg_replace(sprintf('(^%s+|%s+$)', $whitespace, $whitespace),
                        '', $string);
		return $ret;
	}

	public static function lines2TrimAll($data)
	{
		$lines = split("\n", $data);

		foreach ($lines AS $line)
		{
			$ret[] = self::trimAll($line);
		}

		$str = join("\n", $ret);

		return $str;
	}

	public static function trimAll($data)
	{
		$data = self::ltrimAll($data);
		$data = self::rtrimAll($data);

		return $data;
	}

	public static function ltrimAll($data)
	{
		$str = ltrim($data);

		if (strlen($str) != strlen($data))
		{
			$str = self::ltrimAll($str);
		}

		return $str;
	}

	public static function rtrimAll($data)
	{
		$str = rtrim($data);

		if (strlen($str) != strlen($data))
		{
			$str = self::rtrimAll($str);
		}

		return $str;
	}

	/*生成関数*/

	/*
	 * Name			: basename
	 * IN			: file_path [, suffix]
	 * OUT 			: basename
	 * Discription	: PHP5のバグで日本語名ファイルにbasenameが正しく適用されないため自作
	 */
	function basename($file_path, $suffix="")
	{
		$aryFname = explode('/',$file_path);
		$aryDname = explode('/',dirname( $file_path ));
		$diff = array_diff( $aryFname ,$aryDname);

		//ファイル名の摘出
		$basename = implode($diff);

		if ($suffix != "")
		{
			$basename = str_replace($suffix, '', $basename);
		}

		return $basename;
	}

	/*======================================================
	* Name         : getRandStr
	* IN           : 文字数
	* OUT          : 文字列
	* Discription  : 指定文字数のランダムな文字列を生成
	=======================================================*/
	public static function getRandStr($len)
	{

		// 文字列の配列を作成
		$pwelemstr = "abcdefghkmnpqrstuvwxyz12345679";
		$pwelem = preg_split("//", $pwelemstr, 0, PREG_SPLIT_NO_EMPTY);

		$str = "";
		for ($i = 0 ; $i < $len ; $i++)
		{
		  // ランダム文字列を生成
		  $str .= $pwelem[array_rand($pwelem, 1)];
		}

	  return $str;
	}


	/*
	* エクセルから生成されたCSVファイルを
	* 配列に取得する
	*/
	public static function fgetExcelCSV(&$fp , $delimiter = ',' , $enclosure = '"')
	{
		// ファイル全体を配列で取得
		$line = fgets($fp);

		if($line === false)
		{
			return false;
		}

		// 文字列をUTF-8に変換
		$line = mb_convert_encoding($line, "UTF-8", "SJIS");

		// 文字列を文字要素に分割
		$bytes = preg_split('//' , trim($line), -1, PREG_SPLIT_NO_EMPTY);

		// 前後1文字ずつ取り除く
		//array_shift($bytes);
		//array_pop($bytes);

		$cols = array();
		$col = '';
		$isInQuote = false;
		while($bytes)
		{
			// 1文字取り出す
			$byte = array_shift($bytes);

			// 囲み文字の中の場合
			if($isInQuote)
			{
				//　囲み文字の終点か？
				if($byte == $enclosure)
				{
					//　次の文字も囲み文字ならエスケープされたものである
					if($bytes[0] == $enclosure)
					{
						// 文字列の続きとして取り込み次の文字を消す
						$col .= $byte;
						array_shift($bytes);
					}
					else
					{
						// 終点
						$isInQuote = false;
					}
				}
				else
				{
					// 文字列の続きを追加
					$col .= $byte;
				}
			}
			// 囲み文字の中から出ている場合
			else
			{
				// 分割文字か？
				if($byte == $delimiter)
				{
					// 配列に格納して次へ
					$cols[] = $col;
					$col = '';
				}
				// 囲み文字で文字列が初期化されている場合、囲み文字の中フラグON
				elseif($byte == $enclosure && $col == '')
				{
					$isInQuote = true;
				}
				// 囲み文字で囲まれていない文字列→最初に分割文字が出たところまで続く
				else
				{
					$col .= $byte;
				}
			}

			// 文字の最後まで来たときに囲み文字の中フラグが立ったまま→文字列の途中で改行している
			while(!$bytes && $isInQuote)
			{
				// 続きに改行を入れてもう一行取ってくる
				$col .= "\n";
				$line = fgets($fp);

				// 文字列をUTF-8に変換
				$line = mb_convert_encoding($line, "UTF-8", "SJIS");

				if($line === false)
				{
					$isInQuote = false;
				}
				else
				{
					$bytes = preg_split('//' , trim($line));
					//array_shift($bytes);
					//array_pop($bytes);
				}
			}
		}
		$cols[] = $col;
		return $cols;
	}

}

?>
