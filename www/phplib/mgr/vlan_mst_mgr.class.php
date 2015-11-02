<?php
/**********************************************************
* File         : vlan_mst_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/vlan_mst_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class vlan_mst_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function getVlanList($vlan_room_id)
	{
		$sql = $this->getQuery('GET_VLAN_LIST', $vlan_room_id);

		$aryRet = $this->oDb->getAssoc($sql);

		return $aryRet;
	}

	function getVlanAdminList($vlan_id)
	{
		$sql = $this->getQuery('GET_VLAN_ADMIN_LIST', $vlan_id);

		$aryRet = $this->oDb->getRow($sql);

		return $aryRet;
	}

	/*
	 * isExist
	*/
	function isExist($vlan_room_id, $vlan_name, $vlan_id="")
	{
		$args = $this->getSqlArgs();
		$args[0] = $vlan_name;
		$args[1] = $vlan_room_id;
		$args['COND'] = "";
		if ($vlan_id != "")
		{
			$args['COND'] = " AND vlan_id != " . $vlan_id;
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
		$ret = $this->makeNewRec($request['vlan_room_id'], $request['new_vlan_name'], $request['new_admin_name']);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function makeNewRec($vlan_room_id, $vlan_name, $admin_name)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $vlan_name;
		$args[1] = $admin_name;
		$args[2] = $vlan_room_id;

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
		$vlan_id = $request['vlan_id'];

		// 既存レコード編集
		$ret = $this->editExistRec($vlan_id, $request['vlan_name'][$vlan_id], $request['admin_name'][$vlan_id]);

		if (!$ret)
		{
			return false;
		}

		return true;
	}

	function editExistRec($vlan_id, $vlan_name, $admin_name)
	{
		// データを作成
		$args = $this->getSqlArgs();
		$args[0] = $vlan_id;
		$args[1] = $vlan_name;
		$args[2] = $admin_name;

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

		$vlan_room_id = $request['vlan_room_id'];

		// 新規レコードがある場合
		if ($request['new_vlan_name'] != "" && $request['new_admin_name'] != "")
		{
			$ret = $this->makeNewRec($vlan_room_id, $request['new_vlan_name'], $request['new_admin_name']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		if (is_array($request['vlan_name']))
		{
			foreach ($request['vlan_name'] AS $vlan_id => $vlan_name)
			{
				// 既存レコード編集
				$ret = $this->editExistRec($vlan_id, $vlan_name, $request['admin_name'][$vlan_id]);

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
		// トランザクション開始
		$this->oDb->begin();

		$vlan_id = $request['vlan_id'];

		// 削除
		$args = $this->getSqlArgs();
		$args[0] = $vlan_id;

		$sql = $this->getQuery('DELETE_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$sql = $this->getQuery('DELETE_ADMIN_DATA', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		// トランザクション終了
		$this->oDb->end();

		return true;
	}

	/*======================================================
	 * Name         : sortAjaxEvent
	* IN           : $arNewOrderPrimaryId : 新しい区分IDの並び順で配置された配列
	* OUT          : 新しい表示順のHTMLを構築し表示して終了する
	* Discription  : テーブル行をD&Dで移動させた時のイベントハンドラ
	*              : 呼び出し元はvlan_list.tplのAjaxから
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
