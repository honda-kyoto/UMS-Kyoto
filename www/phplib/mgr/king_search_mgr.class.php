<?php
/**********************************************************
* File         : king_search_mgr.class.php
* Authors      : kazuyoshi shibuta
* Date         : 2015.06.04
* Last Update  : 2015.06.04
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/king_search_sql.inc.php");

define ("DEFAULT_ORDER", "king_1");
define ("DEFAULT_DESC", "ASC");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class king_search_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function saveSearchData($request)
	{
		$_SESSION['ML_SCH']['king_name'] = @$request['king_name'];
		$_SESSION['ML_SCH']['king_name_kana'] = @$request['king_name_kana'];

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
		$request['king_name'] = @$_SESSION['ML_SCH']['king_name'];
		$request['king_name_kana'] = @$_SESSION['ML_SCH']['king_name_kana'];

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

		$aryCond = array();

		// 氏名
		if (@$request['king_name'] != "")
		{
			$aryCond[] = "king_5 LIKE '%" . string::replaceSql($request['king_name']) . "%'";
		}

		// カナ
		if (@$request['king_name_kana'] != "")
		{
			$aryCond[] = "king_6 LIKE '%" . string::replaceSql($request['king_name_kana']) . "%'";
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
			$args['ORDER'] = $request['order'];
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

	function getMlistData($mlist_id, $entry_no="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "tbl";
		$args['COL'] = "";
		$args['COND'] = "";
		if ($entry_no != "")
		{
			$args['TYPE'] = "entry";
			$args['COL'] = <<< SQL
, entry_kbn
, entry_status
, TO_CHAR(entry_time, 'YYYY/MM/DD HH24:MI:SS') AS entry_time
, entry_id
, TO_CHAR(agree_time, 'YYYY/MM/DD HH24:MI:SS') AS agree_time
, agree_id
, agree_note
SQL;
			$args['COND'] = " AND entry_no = " . $this->sqlItemInteger($entry_no);
		}

		$sql = $this->getQuery('GET_MLIST_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function getAgreedEntryData($mlist_id)
	{
		// 最後に承認された申請データを取ってくる
		$args = array();
		$args['ENTRY_STATUS_AGREE'] = $this->sqlItemChar(ENTRY_STATUS_AGREE);
		$args[0] = $mlist_id;

		$sql = $this->getQuery('GET_LAST_AGREE_DATA', $args);

		$ret = $this->oDb->getRow($sql);

		return $ret;
	}

	function getAdminList($mlist_id, $entry_no="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "list";
		$args['COND'] = "";

		if ($entry_no != "")
		{
			$args['TYPE'] = "entry";
			$args['COND'] = " AND MA.entry_no = " . $this->sqlItemInteger($entry_no);
		}

		$sql = $this->getQuery('GET_ADMIN_LIST', $args);

		$ret = $this->oDb->getCol($sql);

		return $ret;
	}

	function getAdminId($mlist_id, $entry_no="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "list";
		$args['COND'] = "";
		if ($entry_no != "")
		{
			$args['TYPE'] = "entry";
			$args['COND'] = " AND entry_no = " . $this->sqlItemInteger($entry_no);
		}

		$sql = $this->getQuery('GET_ADMIN_ID', $args);

		$ret = $this->oDb->getAssoc2Ary($sql);

		return $ret;
	}

	function getAdminName($user_id)
	{
		$sql = $this->getQuery('GET_ADMIN_NAME', $user_id);

		$name = $this->oDb->getOne($sql);

		return $name;
	}

	function existsMlistName($mlist_name, $mlist_id="")
	{
		$args = array();
		$args[0] = $mlist_name;
		$args['COND'] = "";
		if ($mlist_id != "")
		{
			$args['COND'] = " AND mlist_id != " . string::replaceSql($mlist_id);
		}

		$sql = $this->getQuery('EXISTS_MLIST_NAME', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;
	}


	function addMailingList($request)
	{
		$client = null;
		$this->createMlistClient(&$client);

		if (is_null($client))
		{
			return;
		}

		if ($request['sender_kbn'] == "")
		{
			$request['sender_kbn'] = "0";
		}

		$params = array(
				'listName' => $request['mlist_acc'],
				'value'    => $request['sender_kbn'],
		);

		$res = $client->mailingAdd( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メーリングリスト登録は成功しました", 121);
		}
		else if ( $res->resultCode == 200 )
		{
			Debug_Trace($params, 999);
			Debug_Trace("メーリングリスト登録は失敗しました(クライアント側エラー)", 121);
		}
		else
		{
			Debug_Trace("メーリングリスト登録は失敗しました(サーバー側エラー)", 121);
		}

		return;
	}

	function editMailingList($old_acc, $mlist_acc)
	{
		$client = null;
		$this->createMlistClient(&$client);

		if (is_null($client))
		{
			return;
		}

		$params = array(
				'listName' => $old_acc,
				'listNameNew'    => $mlist_acc,
		);

		$res = $client->mailingEdit( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メーリングリスト変更は成功しました", 321);
		}
		else if ( $res->resultCode == 200 )
		{
			Debug_Trace($res->message, 999);
			Debug_Trace("メーリングリスト変更は失敗しました(クライアント側エラー)", 321);
		}
		else
		{
			Debug_Trace("メーリングリスト変更は失敗しました(サーバー側エラー)", 321);
		}

		return;
	}


	function editSenderKbn($request)
	{
		$client = null;
		$this->createMlistClient(&$client);

		if (is_null($client))
		{
			return;
		}

		if ($request['sender_kbn'] == "")
		{
			$request['sender_kbn'] = "0";
		}

		$params = array(
				'listName' => $request['mlist_acc'],
				'value'    => $request['sender_kbn'],
		);

		$res = $client->mailingSender( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メンバー制限変更は成功しました", 821);
		}
		else if ( $res->resultCode == 200 )
		{
			Debug_Trace("メンバー制限変更は失敗しました(クライアント側エラー)", 821);
		}
		else
		{
			Debug_Trace("メンバー制限変更は失敗しました(サーバー側エラー)", 821);
		}

		return;
	}


	function delMailingList($mlist_id)
	{
		$client = null;
		$this->createMlistClient(&$client);

		if (is_null($client))
		{
			return;
		}

		$mlist_acc = $this->getMlistAcc($mlist_id);

		$params = array(
				'listName' => $mlist_acc,
		);

		$res = $client->mailingDelete( $params );

		if ( $res->resultCode == 100 )
		{
			Debug_Trace("メーリングリスト削除は成功しました", 521);
		}
		else if ( $res->resultCode == 200 )
		{
			Debug_Trace($res->message, 999);
			Debug_Trace("メーリングリスト削除は失敗しました(クライアント側エラー)", 521);
		}
		else
		{
			Debug_Trace("メーリングリスト削除は失敗しました(サーバー側エラー)", 521);
		}

		return;
	}
}
?>
