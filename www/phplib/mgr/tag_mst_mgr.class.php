<?php
/**********************************************************
* File         : tag_mst_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/tag_mst_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class tag_mst_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();

	}

	/*
	 * isExist
	*/
	function isExist($post_name, $post_id="")
	{
		$args = $this->getSqlArgs();
		$args[0] = $post_name;
		$args['COND'] = "";
		if ($post_id != "")
		{
			$args['COND'] = " AND post_id != " . $post_id;
		}

		$sql = $this->getQuery('NAME_EXISTS', $args);

		$ret = $this->oDb->getOne($sql);

		if ($ret == "")
		{
			return false;
		}

		return true;
	}

	function insertData($request)
	{
		// 新規レコード作成
		$ret = $this->makeNewRec($request['new_post_name'],$request['new_tag_uid']);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function makeNewRec($tag_name, $tag_uid)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $tag_name;
		$args[1] = $tag_uid;

		// 登録
		$sql = $this->getQuery('INSERT_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function updateData($request)
	{
		$post_id = $request['post_id'];

		// 既存レコード編集
		$ret = $this->editExistRec($post_id, $request['post_name'][$post_id], $request['tag_uid'][$post_id]);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function editExistRec($tag_id, $tag_name, $tag_uid)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $tag_id;
		$args[1] = $tag_name;
		$args[2] = $tag_uid;

		// 更新
		$sql = $this->getQuery('UPDATE_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function updateAll($request)
	{
		// トランザクション開始
		$this->oDb->begin();

		// 新規レコードがある場合
		if ($request['new_post_name'] != "")
		{
			$ret = $this->makeNewRec($request['new_post_name'], $request['new_tag_uid']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		if (is_array($request['post_name']))
		{
			foreach ($request['post_name'] AS $post_id => $post_name)
			{
				// 既存レコード編集
				$ret = $this->editExistRec($post_id, $post_name, $request['tag_uid'][$tag_id]);

				if (!$ret)
				{
					$this->oDb->rollback();
					return false;
				}
			}
		}

		// トランザクション終了
		$this->oDb->end();

		return true;
	}

	/*
	 * deleteData
	*/
	function deleteData($request)
	{
		$post_id = $request['post_id'];

		// 削除
		$args = $this->getSqlArgs();
		$args[0] = $post_id;

		$sql = $this->getQuery('DELETE_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	/*======================================================
	 * Name         : sortAjaxEvent
	* IN           : $arNewOrderPrimaryId : 新しい区分IDの並び順で配置された配列
	* OUT          : 新しい表示順のHTMLを構築し表示して終了する
	* Discription  : テーブル行をD&Dで移動させた時のイベントハンドラ
	*              : 呼び出し元はpost_list.tplのAjaxから
	=======================================================*/
	function sortAjaxEvent( $aryId )
	{
		// トランザクション開始
		$this->oDb->begin();

		// リクエストのIDから順番を更新
		if (is_array($aryId))
		{
			$disp_num = 1;
			$args = $this->getSqlArgs();
			foreach ($aryId AS $key_id)
			{
				$args[0] = $key_id;
				$args[1] = $disp_num;
				$sql = $this->getQuery( 'UPDATE_DISPNUM', $args );

				$ret = $this->oDb->query( $sql );
				if( !$ret )
				{
					$this->oDb->rollback();
					return false;
				}
				$disp_num++;
			}
		}

		// トランザクション終了
		$this->oDb->end();

		return true;
	}

}

?>
