<?php
/**********************************************************
* File         : mlists_search_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/mlists_regist_common_mgr.class.php");
require_once("sql/mlists_search_sql.inc.php");

define ("DEFAULT_ORDER", "update_time");
define ("DEFAULT_DESC", "DESC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_search_mgr extends mlists_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function saveSearchData($request)
	{
		$_SESSION['ML_SCH']['mlist_name'] = @$request['mlist_name'];
		$_SESSION['ML_SCH']['mlist_acc'] = @$request['mlist_acc'];
		$_SESSION['ML_SCH']['mlist_kbn'] = @$request['mlist_kbn'];
		$_SESSION['ML_SCH']['entry_kbn_status'] = @$request['entry_kbn_status'];

		$_SESSION['ML_SCH']['list_max'] = @$request['list_max'];
		$_SESSION['ML_SCH']['order']    = "";
		$_SESSION['ML_SCH']['desc']     = "";
	}

	function saveOrderData($request)
	{
		$desc = "";
		if (@$request['order'] == @$_SESSION['ML_SCH']['order'])
		{
			if (@$_SESSION['ML_SCH']['desc'] == "")
			{
				$desc = "DESC";
			}
		}
		$_SESSION['ML_SCH']['order'] = $request['order'];
		$_SESSION['ML_SCH']['desc']  = $desc;
	}

	function loadOrderKey()
	{
		return @$_SESSION['ML_SCH']['order'];
	}

	function loadOrderDesc()
	{
		return $_SESSION['ML_SCH']['desc'];
	}

	function savePage($page)
	{
		$_SESSION['ML_SCH']['page'] = $page;
	}

	function saveListMax($max)
	{
		$_SESSION['ML_SCH']['list_max'] = $max;
	}

	function loadSearchData(&$request)
	{
		$request['mlist_name'] = @$_SESSION['ML_SCH']['mlist_name'];
		$request['mlist_acc'] = @$_SESSION['ML_SCH']['mlist_acc'];
		$request['mlist_kbn'] = @$_SESSION['ML_SCH']['mlist_kbn'];
		$request['entry_kbn_status'] = @$_SESSION['ML_SCH']['entry_kbn_status'];

		$request['list_max'] = @$_SESSION['ML_SCH']['list_max']   ;
		$request['order'] = @$_SESSION['ML_SCH']['order']   ;
		$request['desc'] = @$_SESSION['ML_SCH']['desc']    ;
	}

	function loadPage(&$page)
	{
		$page = @$_SESSION['ML_SCH']['page'];
	}

	function getSearchArgs($request)
	{
		$args = $this->getSqlArgs();
		$args['COND'] = "";
		$args['ENTRY_STATUS_ENTRY'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);
		$args['ENTRY_STATUS_REJECT'] = $this->sqlItemChar(ENTRY_STATUS_REJECT);

		$aryCond = array();

		// 名称
		if (@$request['mlist_name'] != "")
		{
			$aryCond[] = "MLT.mlist_name LIKE '%" . string::replaceSql($request['mlist_name']) . "%'";
		}

		// アカウント
		if (@$request['mlist_acc'] != "")
		{
			$aryCond[] = "MLT.mlist_acc LIKE '%" . string::replaceSql($request['mlist_acc']) . "%'";
		}

		// 種別
		if (@$request['mlist_kbn'] != "")
		{
			$aryCond[] = "MLT.mlist_kbn = '" . string::replaceSql($request['mlist_kbn']) . "'";
		}


		$cnt = 0;
		if (is_array($request['entry_kbn_status']))
		{
			foreach ($request['entry_kbn_status'] AS $key => $val)
			{
				if ($val != "1")
				{
					continue;
				}

				if ($key == 'agreed')
				{
					$aryKbnStat[] = "(MLT.entry_kbn IS NULL AND APP.entry_status IS NULL)";
				}

				list ($entry_kbn, $entry_status) = explode("_", $key);

				$aryKbnStat[] = "(MLT.entry_kbn = '" . string::replaceSql($entry_kbn) . "' AND MLT.entry_status = '" . string::replaceSql($entry_status) . "')";

				$cnt++;
			}
		}
		if ($cnt > 0)
		{
			$aryCond[] = "(" . implode(" OR ", $aryKbnStat) . ")";
		}

		// ログインユーザの権限によって処理分け

		// 管理権限があるか？
		if (!$this->hasAdminActType('mlists_search.php'))
		{
			// 一般ユーザの場合自分の申請したもののみ表示
			$user_id = $this->getSessionData('LOGIN_USER_ID');
			$uid = string::replaceSql($user_id);
			$aryCond[] = <<< SQL
(
EXISTS (SELECT * FROM mlist_head_entry WHERE MLT.mlist_id = mlist_id AND entry_id = $uid)
OR
EXISTS (SELECT * FROM mlist_admin_list WHERE MLT.mlist_id = mlist_id AND del_flg = '0' AND user_id = $uid)
)

SQL;
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
				case "admin_name":
					$args['ORDER'] = "sort_admin_name";
					break;
				case "entry_status":
					$args['ORDER'] = "entry_kbn || '_' || entry_status";
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

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function delMlistEntry($mlist_id, &$entry_no)
	{
		$user_id = $this->getSessionData('LOGIN_USER_ID');

		$request = $this->getMlistData($mlist_id);

		$this->oDb->begin();

		//$mlist_id = $request['mlist_id'];
		$entry_kbn = ENTRY_KBN_DEL;

		// 申請番号取得（ロック）
		$sql = $this->getQuery('ENTRY_LOCK', $args);
		$this->oDb->query($sql);

		$sql = $this->getQuery('GET_ENTRY_NO', $mlist_id);

		$entry_no = $this->oDb->getOne($sql);

		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);
		$args['ENTRY_NO'] = $this->sqlItemInteger($entry_no);
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_ENTRY);
		$args['ENTRY_KBN'] = $this->sqlItemChar($entry_kbn);
		$args['MLIST_NAME'] = $this->sqlItemChar($request['mlist_name']);
		$args['MLIST_ACC'] = $this->sqlItemChar($request['mlist_acc']);
		$args['SENDER_KBN'] = $this->sqlItemChar($request['sender_kbn']);
		$args['MLIST_KBN'] = $this->sqlItemChar($request['mlist_kbn']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['USAGE'] = $this->sqlItemChar($request['usage']);
		$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);

		$sql = $this->getQuery('INSERT_MLIST_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// 管理者
		$aryAdminList = $this->getAdminId($mlist_id);

		if (is_array($aryAdminList))
		{
			foreach ($aryAdminList AS $no => $admin_id)
			{
				$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);
				$args['ENTRY_NO'] = $this->sqlItemInteger($entry_no);
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['USER_ID'] = $this->sqlItemInteger($admin_id);
				$args['ENTRY_ID'] = $this->sqlItemInteger($user_id);
			}

			$sql = $this->getQuery('INSERT_MLIST_ADMIN_ENTRY', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				$this->oDb->rollback();
				return false;
			}
		}


		$this->oDb->end();

		// ここにメール送信処理を入れる


		return true;
	}

	function deleteMlistData($mlist_id)
	{
		$this->oDb->begin();

		// 基本情報
		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;

		$sql = $this->getQuery('DELETE_MLIST_HEAD', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$sql = $this->getQuery('DELETE_MLIST_ADMIN', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$sql = $this->getQuery('DELETE_MLIST_MEMBERS', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// 同期
		$this->delMailingList($mlist_id);

		return true;

	}

}
?>
