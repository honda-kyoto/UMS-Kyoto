<?php
/**********************************************************
* File         : mlists_agree_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/mlists_regist_common_mgr.class.php");
require_once("sql/mlists_agree_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_agree_mgr extends mlists_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function agreeMlistEntry($request)
	{
		$mlist_id = $request['mlist_id'];
		$entry_no = $request['entry_no'];

		$this->oDb->begin();

		$aryEnt = $this->getMlistData($mlist_id, $entry_no);

		$entry_kbn = $aryEnt['entry_kbn'];

		// 連携用にデータを取っておく。
		$aryOrg = $this->getMlistData($mlist_id);

		switch ($entry_kbn)
		{
			case ENTRY_KBN_ADD:
				$ret = $this->insertMlistOrgData($request);
				break;
			case ENTRY_KBN_EDIT:
				$ret = $this->updateMlistOrgData($request);
				break;
			case ENTRY_KBN_DEL:
				$ret = $this->deleteMlistOrgData($request);
				break;
		}

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 申請データを承認済みに変更
		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;
		$args[1] = $entry_no;
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_AGREE);

		$sql = $this->getQuery('AGREE_MLIST_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// メールサーバ連携
		switch ($entry_kbn)
		{
			case ENTRY_KBN_ADD:
				$this->addMailingList($aryEnt);
				break;
			case ENTRY_KBN_EDIT:
				if ($aryOrg['mlist_acc'] != $request['mlist_acc'])
				{
					// アカウントが変わった場合
					$this->editMailingList($aryOrg['mlist_acc'], $aryEnt['mlist_acc']);
				}
				// 送信者制限が変わった場合
				if ($aryOrg['sender_kbn'] != $aryEnt['sender_kbn'])
				{
					$this->editSenderKbn($aryEnt);
				}
				break;
			case ENTRY_KBN_DEL:
				$this->delMailingList($mlist_id);
				break;
		}

		return true;
	}

	function insertMlistOrgData($request)
	{
		$mlist_id = $request['mlist_id'];
		$entry_no = $request['entry_no'];

		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;
		$args[1] = $entry_no;

		$sql = $this->getQuery('INSERT_MLIST_ORG_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// 管理者を登録
		$sql = $this->getQuery('INSERT_MLIST_ORG_ADMIN', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function updateMlistOrgData($request)
	{
		$mlist_id = $request['mlist_id'];
		$entry_no = $request['entry_no'];

		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;
		$args[1] = $entry_no;

		$sql = $this->getQuery('UPDATE_MLIST_ORG_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		$aryEntList = $this->getAdminId($mlist_id, $entry_no);
		$aryOrgList = $this->getAdminId($mlist_id);

		if (is_array($aryEntList))
		{
			foreach ($aryEntList AS $list_no => $user_id)
			{
				if (@$aryOrgList[$list_no] == $user_id)
				{
					// 同じ
					unset($aryOrgList[$list_no]);
					continue;
				}

				$args = $this->getSqlArgs();
				$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);
				$args['LIST_NO'] = $this->sqlItemInteger($list_no);
				$args['USER_ID'] = $this->sqlItemChar($user_id);

				if (@$aryOrgList[$list_no] != "")
				{
					// 更新
					$sql = $this->getQuery('UPDATE_MLIST_ADMIN_DATA', $args);

					$ret = $this->oDb->query($sql);

					if (!$ret)
					{
						Debug_Print($sql);
						return false;
					}

					// 消す
					unset($aryOrgList[$list_no]);
					continue;
				}

				// 新規

				// 削除データがないかチェック
				$sql = $this->getQuery('EXISTS_MLIST_ADMIN_DATA', $args);

				$ext_id = $this->oDb->getOne($sql);

				if ($ext_id != "")
				{
					$sql_id = 'UPDATE_MLIST_ADMIN_DATA';
				}
				else
				{
					$sql_id = 'INSERT_MLIST_ADMIN_DATA';
				}

				$sql = $this->getQuery($sql_id, $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					return false;
				}
			}
		}

		// 残ってたら消す
		if (is_array($aryOrgList))
		{
			foreach ($aryOrgList AS $list_no => $user_id)
			{
				$args = $this->getSqlArgs();
				$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);
				$args['LIST_NO'] = $this->sqlItemInteger($list_no);

				$sql = $this->getQuery('DELETE_MLIST_ADMIN_DATA', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
					return false;
				}
			}
		}


		return true;
	}

	function deleteMlistOrgData($request)
	{
		$mlist_id = $request['mlist_id'];

		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;

		$sql = $this->getQuery('DELETE_MLIST_ORG_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// 管理者を削除
		$sql = $this->getQuery('DELETE_MLIST_ORG_ADMIN', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function rejectMlistEntry($request)
	{
		$mlist_id = $request['mlist_id'];
		$entry_no = $request['entry_no'];
		$agree_note = $request['agree_note'];

		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;
		$args[1] = $entry_no;
		$args['AGREE_NOTE'] = $this->sqlItemChar($agree_note);
		$args['ENTRY_STATUS'] = $this->sqlItemChar(ENTRY_STATUS_REJECT);

		$sql = $this->getQuery('REJECT_MLIST_HEAD_ENTRY', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}
}
?>
