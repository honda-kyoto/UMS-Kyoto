<?php
/**********************************************************
* File         : user_card_issue_list_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/user_card_issue_list_sql.inc.php");

define ("DEFAULT_ORDER", "update_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class user_card_issue_list_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function saveSearchData($request)
	{
		$_SESSION['K_CD_SCH']['issue_from'] = @$request['issue_from'];
		$_SESSION['K_CD_SCH']['issue_to'] = @$request['issue_to'];
		$_SESSION['K_CD_SCH']['key_number'] = @$request['key_number'];
		$_SESSION['K_CD_SCH']['search_option'] = @$request['search_option'];
		$_SESSION['K_CD_SCH']['data_type'] = @$request['data_type'];

		$_SESSION['K_CD_SCH']['list_max'] = @$request['list_max'];
		$_SESSION['K_CD_SCH']['order']    = "";
		$_SESSION['K_CD_SCH']['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION['K_CD_SCH']['order'])
		{
			if (@$_SESSION['K_CD_SCH']['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION['K_CD_SCH']['order'] = $request['order'];
		$_SESSION['K_CD_SCH']['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION['K_CD_SCH']['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION['K_CD_SCH']['desc'];
	}

	function savePage($page)
	{
		$_SESSION['K_CD_SCH']['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION['K_CD_SCH']['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['issue_from'] = @$_SESSION['K_CD_SCH']['issue_from'];
		$request['issue_to'] = @$_SESSION['K_CD_SCH']['issue_to'];
		$request['key_number'] = @$_SESSION['K_CD_SCH']['key_number'];
		$request['search_option'] = @$_SESSION['K_CD_SCH']['search_option'];
		$request['data_type'] = @$_SESSION['K_CD_SCH']['data_type'];

		$request['list_max'] = @$_SESSION['K_CD_SCH']['list_max'];
		$request['order'] = @$_SESSION['K_CD_SCH']['order']   ;
		$request['desc'] = @$_SESSION['K_CD_SCH']['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION['K_CD_SCH']['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";

		$aryCond = array();

		// 更新日：自
		if (@$request['issue_from'] != "")
		{
			$aryCond[] = "KUC.update_time::date >= '" . string::replaceSql($request['issue_from']) . "'";
		}

		// 更新日：至
		if (@$request['issue_to'] != "")
		{
			$aryCond[] = "KUC.update_time::date <= '" . string::replaceSql($request['issue_to']) . "'";
		}

		// キー番号
		$request['key_number'] = string::zen2han($request['key_number']);
		if (@$request['key_number'] != "")
		{
			$aryCond[] = "KUC.key_number LIKE '%" . string::replaceSql($request['key_number']) . "%'";
		}

		// option
		if (@$request['search_option'] == "1")
		{
			$aryCond[] = "KUC.make_time = KUC.update_time";
		}
		else if (@$request['search_option'] == "2")
		{
			$aryCond[] = "KUC.make_time != KUC.update_time";
		}

		// type
		if (@$request['data_type'] == "1")
		{
			$aryCond[] = "KUC.list_no = 0";
		}

		if (count($aryCond) > 0)
		{
			$args['COND'] = " WHERE " . join(" AND ", $aryCond);
		}

		return $args;
	}

	function getCount($request)
	{
		$args = $this->getSearchArgs($request);

		$sql = $this->getQuery('GETCOUNT', $args);

		$count = $this->oDb->getOne($sql);

		return $count;
	}

	/*
	 * getList
	*/
	function getList($request, $limit)
	{
		$args = $this->getSearchArgs($request);

		if ($request['order'] != "")
		{
			switch($request['order'])
			{
				case "kanji_name":
					$args['ORDER'] = "UM.kanjisei || UM.kanjimei";
					break;
// 				case "kana_name":
// 					$args['ORDER'] = "UM.kanasei || UM.kanamei";
// 					break;
				case "belong_name":
					$args['ORDER'] = "SRT.sort_belong_name";
					break;
				case "data_type":
					$args['ORDER'] = "KUC.user_id || '_' || KUC.list_no";
					break;
				default:
					$args['ORDER'] = $request['order'];
					break;
			}

			$args['DESC'] = $request['desc'];
		}
		else
		{
			$args['ORDER'] = DEFAULT_ORDER;
			$args['DESC'] = DEFAULT_DESC;
		}

		if ($limit == "")
		{
			$limit = DEFAULT_LIST_MAX;
		}

		$offset = ($request['page'] - 1) * $limit;

		$args['LIMIT'] = $limit;
		$args['OFFSET'] = $offset;

		$sql = $this->getQuery('GETLIST', $args);

		$aryRet = $this->oDb->getAll($sql);

		return $aryRet;
	}

	function outputCardListData($checked_id)
	{
		if (is_array($checked_id))
		{
			//
			// CSV作成
			//

			$aryCsvBase = array();
			//
			$aryCsvBase[] = array('header' => '0', 'val' => '{command}',			'title' => 'コマンド');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '1', 'val' => '{key_number}',		'title' => '個人番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '属性');
			$aryCsvBase[] = array('header' => '1', 'val' => '{kanjiname}',		'title' => '氏名');
			$aryCsvBase[] = array('header' => '1', 'val' => '{kananame}',			'title' => 'ﾌﾘｶﾞﾅ');
			$aryCsvBase[] = array('header' => '1', 'val' => '{sexplus}',			'title' => '性別');
			$aryCsvBase[] = array('header' => '1', 'val' => '{birthday}',			'title' => '生年月日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => 'TEL番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '端末操作権限');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '通行連動番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '分類番号');
			$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_1}','title' => '所属１所属番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属１有効期限開始日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属１有効期限終了日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_2}','title' => '所属２所属番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属２有効期限開始日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属２有効期限終了日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_3}','title' => '所属３所属番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属３有効期限開始日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属３有効期限終了日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '1', 'val' => '{belong_info_4}','title' => '所属４所属番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属４有効期限開始日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '所属４有効期限終了日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '1', 'val' => '{card_type}',		'title' => '認証端末１認証端末種別');
			$aryCsvBase[] = array('header' => '1', 'val' => '{uid}',					'title' => '認証端末１認証ＩＤ番号');
			$aryCsvBase[] = array('header' => '1', 'val' => '{start_date}',		'title' => '認証端末１有効期限開始日');
			$aryCsvBase[] = array('header' => '1', 'val' => '{end_date}',			'title' => '認証端末１有効期限終了日');
			$aryCsvBase[] = array('header' => '1', 'val' => '{suspend_flg}',	'title' => '認証端末１失効フラグ');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末１発行回数');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末１暗証番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２認証端末種別');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２認証ＩＤ番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２有効期限開始日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２有効期限終了日');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２失効フラグ');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２発行回数');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '認証端末２暗証番号');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => 'ダミー');
			$aryCsvBase[] = array('header' => '1', 'val' => '{sec_name}',			'title' => '文字情報01');
			$aryCsvBase[] = array('header' => '1', 'val' => '{chg_name}',			'title' => '文字情報02');
			$aryCsvBase[] = array('header' => '1', 'val' => '{post_name}',		'title' => '文字情報03');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報04');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報05');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報06');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報07');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報08');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報09');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '文字情報10');
			$aryCsvBase[] = array('header' => '0', 'val' => '',								'title' => '写真ファイル名');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => '予備１');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => '予備２');
			$aryCsvBase[] = array('header' => '0', 'val' => '',							'title' => '予備３');

			$title = "";
			$header = "";
			$body = "";
			$sep = "";
			foreach ($aryCsvBase AS $aryBase)
			{
				$title .= $sep . '"' . $aryBase['title'] . '"';
				$header .= $sep . '"' . $aryBase['header'] . '"';
				$body .= $sep . '"' . $aryBase['val'] . '"';
				$sep = ",";
			}

			$aryLine = array();

			foreach ($checked_id AS $key)
			{
				list($user_id, $list_no) = explode("_", $key);

				$args = array();
				$args[0] = $user_id;
				$args[1] = $list_no;
				$sql = $this->getQuery("GET_OUTPUT_DATA", $args);

				$request = $this->oDb->getRow($sql);


				// 削除の場合
				if ($request['del_flg'] == "1")
				{
					$command = "D";	// 削除

					$kanjiname = "";
					$kananame = "";
					$sexplus = "";
					$birthday = "";
					$belong_info_1 = "";
					$belong_info_2 = "";
					$belong_info_3 = "";
					$belong_info_4 = "";
					$card_type = "";
					$uid = "";
					$start_date = "";
					$end_date = "";
					$suspend_flg = "";
					$sec_name = "";
					$chg_name = "";
					$post_name = "";
				}
				else
				{
					// 更新の場合
					$command = "A";	// 削除

					// 利用者マスタから基本情報を取得
					$aryUser = $this->getUserData($user_id);

					$kanjiname = $aryUser['kanjisei'] . "　" . $aryUser['kanjimei'];
					$kananame = $aryUser['kanasei'] . "　" . $aryUser['kanamei'];
					$sexplus = $aryUser['sex'] + 1;
					$birthday = "";
					if ($aryUser['birth_year'] != "")
					{
						$ret['birth_year'] = $by;
						$ret['birth_mon'] = (int)$bm;
						$ret['birth_day'] = (int)$bd;
						$birthday = sprintf("%04d/%02d/%02d", $aryUser['birth_year'], $aryUser['birth_mon'], $aryUser['birth_day']);
					}
					$belong_info_1 = $request['belong_info_1'];
					$belong_info_2 = $request['belong_info_2'];
					$belong_info_3 = $request['belong_info_3'];
					$belong_info_4 = $request['belong_info_4'];
					// ハンズフリータグ：70、カード：50
					$card_type = ($request['card_type'] == '1' ? "50" : "70");
					$uid = $request['uid'];
					$start_date = $request['start_date'];
					$end_date = $request['end_date'];
					$suspend_flg = ($request['suspend_flg'] == '1' ? '1' : '0');

					$arySec = $this->getBelongSecAry($aryUser['belong_dep_id']);
					$aryChg = $this->getBelongChgAry($aryUser['belong_sec_id']);
					$aryPost = $this->getPostAry();
					$sec_name = $arySec[$aryUser['belong_sec_id']];
					$chg_name = $aryChg[$aryUser['belong_chg_id']];
					$post_name = $aryPost[$aryUser['post_id']];
				}

				$request['key_number'] = str_pad($request['key_number'], 16, "0", STR_PAD_LEFT);

				// キー番号は０埋め16桁
				$key_number = $request['key_number'];

				// 置換
				$line = str_replace("{command}", $command, $body);
				$line = str_replace("{key_number}", $key_number, $line);
				$line = str_replace("{kanjiname}", $kanjiname, $line);
				$line = str_replace("{kananame}", $kananame, $line);
				$line = str_replace("{sexplus}", $sexplus, $line);
				$line = str_replace("{birthday}", $birthday, $line);
				$line = str_replace("{belong_info_1}", $belong_info_1, $line);
				$line = str_replace("{belong_info_2}", $belong_info_2, $line);
				$line = str_replace("{belong_info_3}", $belong_info_3, $line);
				$line = str_replace("{belong_info_4}", $belong_info_4, $line);
				$line = str_replace("{card_type}", $card_type, $line);
				$line = str_replace("{uid}", $uid, $line);
				$line = str_replace("{start_date}", $start_date, $line);
				$line = str_replace("{end_date}", $end_date, $line);
				$line = str_replace("{suspend_flg}", $suspend_flg, $line);
				$line = str_replace("{sec_name}", $sec_name, $line);
				$line = str_replace("{chg_name}", $chg_name, $line);
				$line = str_replace("{post_name}", $post_name, $line);

				$aryLine[] = $line;


			}
		}

		$strCsv = $title . "\n" . $header . "\n" . implode("\n", $aryLine) . "\n";

		/*
		// 共有ディレクトリのKOJINEND.txtを確認する。
		$lock_file = CARD_KOJIN_DIR . "/load/KOJINEND.txt";
		if (file_exists($lock_file))
		{
			$this->pushError("インターロックファイルによりロック中のため処理できません。");
			return false;
		}

		$work_time = date("YmdHis");

		// 共有ディレクトリのKOJINSTS.txtを確認する。
		$status_file = CARD_KOJIN_DIR . "/load/KOJINSTS.txt";
		if (file_exists($status_file))
		{
			// あれば中身を確認
			if ($data = file_get_contents($status_file))
			{
				// 文字コード変換
				$data = mb_convert_encoding($data, "UTF-8", "SJIS, sjis-win");

				$aryData = explode(",", $data);
				$stauts = $aryData[0];	// 1項目目がステータス

				$stauts = trim($stauts);
				$stauts = trim($stauts,"\x22");

				//if ($status == "8" || $stauts == "9")
				if ($stauts != "0")
				{
					$this->pushError("前回の処理にエラーがあるため処理できません。");
					return false;
				}

				// エラーが無い場合は、KOJINSTS_yyyymmddhhMMss.txtにリネイムして移動する。
				$new_file = "KOJINSTS_" . $work_time . ".txt";

				//$ret = rename($status_file, CARD_KOJIN_DIR . "/load/" . $new_file);
				//				$ret = rename($status_file, CARD_KOJIN_DIR . "/status/" . $new_file);
				//
				//				if (!$ret)
					//				{
					//					$this->pushError("ステータスファイルのリネームに失敗しました。");
					//					return false;
					//				}

				$ret = copy($status_file, CARD_KOJIN_DIR . "/status/" . $new_file);

				if (!$ret)
				{
					$this->pushError("ステータスファイルのコピーに失敗しました。");
					return false;
				}

				$ret = unlink($status_file);

				if (!$ret)
				{
					$this->pushError("ステータスファイルの削除に失敗しました。");
					return false;
				}
			}
			else
			{
				$this->pushError("ステータスファイルの読み込みに失敗しました。");
				return false;
			}
		}
		*/

		$this->strDl("LDKOJIN.csv", $strCsv);
	}

}
?>
