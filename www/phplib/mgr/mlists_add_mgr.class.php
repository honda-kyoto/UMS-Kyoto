<?php
/**********************************************************
* File         : mlists_add_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/mlists_regist_common_mgr.class.php");
require_once("sql/mlists_add_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_add_mgr extends mlists_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function insertMlistData(&$request)
	{
		$this->oDb->begin();

		// ユーザID取得
		$mlist_id = $this->oDb->getSequence('mlist_id_seq');

		// 基本情報
		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);
		$args['MLIST_NAME'] = $this->sqlItemChar($request['mlist_name']);
		$args['MLIST_ACC'] = $this->sqlItemChar($request['mlist_acc']);
		$args['SENDER_KBN'] = $this->sqlItemChar($request['sender_kbn']);
		$args['MLIST_KBN'] = $this->sqlItemChar($request['mlist_kbn']);
		$args['NOTE'] = $this->sqlItemChar($request['note']);
		$args['USAGE'] = $this->sqlItemChar($request['usage']);

		$sql = $this->getQuery('INSERT_MLIST_HEAD', $args);
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

		if (is_array($request['admin_id']))
		{
			$args = $this->getSqlArgs();
			$args['MLIST_ID'] = $this->sqlItemInteger($mlist_id);

			foreach ($request['admin_id'] AS $no => $user_id)
			{
				$args['LIST_NO'] = $this->sqlItemInteger($no);
				$args['USER_ID'] = $this->sqlItemInteger($user_id);

				$sql = $this->getQuery('INSERT_MLIST_ADMIN', $args);

				$ret = $this->oDb->query($sql);

				if (!$ret)
				{
					Debug_Print($sql);
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
		if ($request['mlist_acc'] != "")
		{
			$this->addMailingList($request);
		}



		$request['mlist_id'] = $mlist_id;
		return true;
	}

}
?>
