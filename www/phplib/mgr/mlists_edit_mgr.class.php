<?php
/**********************************************************
* File         : mlists_edit_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/mlists_regist_common_mgr.class.php");
require_once("sql/mlists_edit_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_edit_mgr extends mlists_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function updateMlistData($request)
	{
		// ID
		$mlist_id = $request['mlist_id'];

		$aryOld = $this->getMlistData($mlist_id);

		$this->oDb->begin();

		// 基本情報
		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);
		$args['MLIST_NAME'] = $this->sqlItemChar($request['mlist_name']);
		$args['MLIST_ACC'] = $this->sqlItemChar($request['mlist_acc']);
		$args['SENDER_KBN'] = $this->sqlItemChar($request['sender_kbn']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['USAGE'] = $this->sqlItemChar($request['usage']);

		$sql = $this->getQuery('UPDATE_MLIST_HEAD', $args);
		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		//
		// 管理者データ
		//
		// 一旦論理削除
		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $mlist_id;
		$sql = $this->getQuery('DELETE_MLIST_ADMIN_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		if (is_array($request['admin_id']))
		{
			$args = $this->getSqlArgs();
			$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);

			foreach ($request['admin_id'] AS $no => $user_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['USER_ID'] = $this->sqlItemInteger($user_id);

				// 存在チェック
				$sql = $this->getQuery('EXISTS_MLIST_ADMIN', $args);
				$ex_sql = $sql;

				$list_no = $this->oDb->getOne($sql);

				if ($list_no == "")
				{
					$sql_id = "INSERT_MLIST_ADMIN";
				}
				else
				{
					$sql_id = "UPDATE_MLIST_ADMIN";
				}

				$sql = $this->getQuery($sql_id, $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql.$ex_sql);
					$this->oDb->rollback();
					return false;
				}

				// 管理者ロールを割り当てる
				$ret = $this->setUserAdminRole($user_id, ROLE_ID_MLIST_ADMIN);

				if (!$ret)
				{
					Debug_Print($sql);
					$this->oDb->rollback();
					return false;
				}

			}
		}
		// 一旦トランザクション終了
		$this->oDb->end();


		//
		// メールアカウント連携
		//

		if ($aryOld['mlist_acc'] != $request['mlist_acc'])
		{
			// アカウントが変わった場合
			$this->editMailingList($aryOld['mlist_acc'], $request['mlist_acc']);
		}

		// 送信者制限が変わった場合
		if ($aryOld['sender_kbn'] != $request['sender_kbn'])
		{
			$this->editSenderKbn($request);
		}

		return true;
	}
}
?>
