<?php
/**********************************************************
* File         : belong_sec_mst_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/belong_sec_mst_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class belong_sec_mst_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	/*
	 * isExist
	*/
	function isExist($relation_id, $belong_sec_name, $belong_sec_id="")
	{
		$args = $this->getSqlArgs();
		$args[0] = $belong_sec_name;
		$args[1] = $relation_id;
		$args['COND'] = "";
		if ($belong_sec_id != "")
		{
			$args['COND'] = " AND belong_sec_id != " . $belong_sec_id;
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
		// トランザクション開始
		$this->oDb->begin();
                
		// 新規レコード作成
		$ret = $this->makeNewRec($request['belong_dep_id'], $request['new_belong_sec_name']);

		// トランザクション終了
		$this->oDb->end();
                
		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function makeNewRec($belong_dep_id,$belong_sec_name)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $belong_sec_name;
		$args['BELONG_DEP_ID'] = $belong_dep_id;

		// 登録
		$sql = $this->getQuery('INSERT_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// sec_id取得
		$sql = $this->getQuery('SELECT_SEC_ID');
		$ret = $this->oDb->getOne($sql);
                if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}
		// 置換データを作成
		$args = $this->getSqlArgs();
		$args[0] = $ret;                
		// belong_chg_mst登録
		$sql = $this->getQuery('INSERT_CHG_DATA', $args);

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
		$belong_sec_id = $request['belong_sec_id'];

		// 既存レコード編集
		$ret = $this->editExistRec($belong_sec_id, $request['belong_sec_name'][$belong_sec_id]);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function editExistRec($belong_sec_id, $belong_sec_name)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $belong_sec_id;
		$args[1] = $belong_sec_name;

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
		if ($request['new_belong_sec_name'] != "")
		{
			$ret = $this->makeNewRec($request['belong_dep_id'], $request['new_belong_sec_name']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		if (is_array($request['belong_sec_name']))
		{
			foreach ($request['belong_sec_name'] AS $belong_sec_id => $belong_sec_name)
			{
				// 既存レコード編集
				$ret = $this->editExistRec($belong_sec_id, $belong_sec_name);

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
		$belong_sec_id = $request['belong_sec_id'];

		// 削除
		$args = $this->getSqlArgs();
		$args[0] = $belong_sec_id;

		$sql = $this->getQuery('DELETE_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	/*!
	 * 所属する親のIDを変更する
	 */
	function parentChange( $request ){

		$args = $this->getSqlArgs();
		$args[0] = $request['org_belong_dep_id'];
		$args[1] = $request['belong_dep_id'];
		$args[2] = implode(",",$request['belong_sec_checkbox']);
                
		$sql = $this->getQuery('UPDATE_PARENT_ID', $args);

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
	*              : 呼び出し元はbelong_sec_list.tplのAjaxから
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
