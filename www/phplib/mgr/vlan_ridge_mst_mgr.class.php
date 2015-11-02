<?php
/**********************************************************
* File         : vlan_ridge_mst_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/vlan_ridge_mst_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vlan_ridge_mst_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	/*
	 * isExist
	*/
	function isExist($vlan_ridge_name, $vlan_ridge_id="")
	{
		$args = $this->getSqlArgs();
		$args[0] = $vlan_ridge_name;
		$args['COND'] = "";
		if ($vlan_ridge_id != "")
		{
			$args['COND'] = " AND vlan_ridge_id != " . $vlan_ridge_id;
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
		$ret = $this->makeNewRec($request['new_vlan_ridge_name']);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function makeNewRec($vlan_ridge_name)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $vlan_ridge_name;

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
		$vlan_ridge_id = $request['vlan_ridge_id'];

		// 既存レコード編集
		$ret = $this->editExistRec($vlan_ridge_id, $request['vlan_ridge_name'][$vlan_ridge_id]);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function editExistRec($vlan_ridge_id, $vlan_ridge_name)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $vlan_ridge_id;
		$args[1] = $vlan_ridge_name;

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
		if ($request['new_vlan_ridge_name'] != "")
		{
			$ret = $this->makeNewRec($request['new_vlan_ridge_name']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		if (is_array($request['vlan_ridge_name']))
		{
			foreach ($request['vlan_ridge_name'] AS $vlan_ridge_id => $vlan_ridge_name)
			{
				// 既存レコード編集
				$ret = $this->editExistRec($vlan_ridge_id, $vlan_ridge_name);

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
		$vlan_ridge_id = $request['vlan_ridge_id'];

		// 削除
		$args = $this->getSqlArgs();
		$args[0] = $vlan_ridge_id;

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
	*              : 呼び出し元はvlan_ridge_list.tplのAjaxから
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
