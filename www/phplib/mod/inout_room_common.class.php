<?php
/**********************************************************
* File         : inout_room_common.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");

class inout_room_common extends common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);
	}

	/*======================================================
	* Name         : runInput
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runInit()
	{
		$this->setListData();
		return 1;
	}

	/*======================================================
	 * Name         : runReturn
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runReturn()
	{
		$this->setListData();
		return 1;
	}

	/*======================================================
	 * Name         : runComplete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runComplete()
	{
		// 完了メッセージをセット
		$this->oMgr->setErr('C002');
		$this->errMsg = $this->oMgr->getErrMsg();

		$this->setListData();

		return 1;
	}


	/*======================================================
	 * Name         : runInsert
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runInsert()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラー
		if (!$ret)
		{
			$this->setListData();
			return 1;
		}

		// 登録処理
		$ret = $this->oMgr->insertData($this->request);

		// エラー
		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}

	/*======================================================
	 * Name         : runUpdate
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runUpdate()
	{
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラー
		if (!$ret)
		{
			$this->setListData();
			return 1;
		}

		// 登録処理
		$ret = $this->oMgr->updateData($this->request);

		// エラー
		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}

	function runEditall()
	{
		echo 1;
		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラー
		if (!$ret)
		{
			$this->setListData();
			return 1;
		}

		// 登録処理
		$ret = $this->oMgr->updateAll($this->request);

		// エラー
		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}

	/*======================================================
	 * Name         : runDelete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runDelete()
	{
		// 登録削除
		$ret = $this->oMgr->deleteData($this->request);

		// エラー
		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->setErr('E999');
			$this->errMsg = $this->oMgr->getErrMsg();
			return 1;
		}

		// リストへ
		$this->postComplete();
	}

	/*======================================================
	 * Name         : runDispnum
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  : テーブル行をD&Dで移動させた時のイベントハンドラ
	*              : 呼び出し元はsystem_list.tplのAjaxから
	=======================================================*/
	function runDispnum()
	{
		$ret = true;

		// リクエスト変数を一旦別配列へ
		$arNewOrder = $this->request['listTab_TB'];

		// 並び順更新
		$ret = $this->oMgr->sortAjaxEvent( $arNewOrder );

		if (!$ret)
		{
			echo -1;
		}

		echo 1;
	}

}

?>
