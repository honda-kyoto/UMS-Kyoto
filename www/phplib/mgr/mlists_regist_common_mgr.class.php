<?php
/**********************************************************
* File         : mlists_regist_common_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_regist_common_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
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
