<?php
/**********************************************************
* File         : tag_mst.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/inout_room_common.class.php");
require_once("mgr/tag_mst_mgr.class.php");

class tag_mst extends inout_room_common
{
	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "タグ管理";
		$this->header_file = "tag_mst_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new tag_mst_mgr();

	}

	function setListData()
	{

		// モードがlist,completeの場合のみDBから取得
		if ($this->mode == 'init' || $this->mode == 'complete' || $this->mode == 'return')
		{
			$aryTmp = $this->oMgr->getTagAry();

			// リクエスト値にセット
			$this->request['post_name'] = array();
			$this->request['tag_uid'] = array();
			if (is_array($aryTmp))
			{
				foreach ($aryTmp AS $id => $data)
				{
					$this->request['post_name'][$id] = $data['tag_name'];
					$this->request['tag_uid'][$id]  = $data['tag_uid'];
				}
			}
		}
	}

	/*======================================================
	 * Name         : checkInputdata
	* IN           :
	* OUT          : [true]エラーなし [false]エラーあり
	* Discription  : 会員情報入力値をチェックする
	=======================================================*/
	function checkInputdata()
	{
		//
		if ($this->mode == 'insert')
		{
			if ($this->request['new_post_name'] == "")
			{
				$this->oMgr->setErr('E001', 'タグ番号');
			}
			else if ($this->oMgr->isExist($this->request['new_post_name']))
			{
				$this->oMgr->setErr('E005', 'タグ番号');
			}
			
			if ($this->request['new_tag_uid'] == "")
			{
				$this->oMgr->setErr('E001', 'uid');
			}
			else if ($this->oMgr->isExist($this->request['new_tag_uid']))
			{
				$this->oMgr->setErr('E005', 'uid');
			}

		}
		else if ($this->mode == 'update')
		{
			if ($this->request['post_name'][$this->request['post_id']] == "")
			{
				$this->oMgr->setErr('E001', 'タグ番号');
			}
			else if ($this->oMgr->isExist($this->request['post_name'][$this->request['post_id']], $this->request['post_id']))
			{
				$this->oMgr->setErr('E005', 'タグ番号');
			}
			
			if ($this->request['tag_uid'][$this->request['post_id']] == "")
			{
				$this->oMgr->setErr('E001', 'uid');
			}
			else if ($this->oMgr->isExist($this->request['tag_uid'][$this->request['post_id']], $this->request['post_id']))
			{
				$this->oMgr->setErr('E005', 'uid');
			}
		}
		else if ($this->mode == 'editall')
		{
			$aryName = $this->request['post_name'];
			if ($this->request['new_post_name'] != "")
			{
				$aryName['new'] = $this->request['new_post_name'];
			}
			$has_empty_error = false;
			$has_double_error = false;
			if (is_array($aryName))
			{
				foreach ($aryName AS $id => $name)
				{
					foreach ($aryName AS $key => $val)
					{
						if ($id == $key)
						{
							continue;
						}
						if ($name == "")
						{
							if (!$has_empty_error)
							{
								$this->oMgr->setErr('E001', 'タグ番号');
								$has_empty_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						else if ($name == $val)
						{
							if (!$has_double_error)
							{
								$this->oMgr->setErr('E005', 'タグ番号');
								$has_double_error = true;
								// ↑同じメッセージは１回でよい
							}
						}
						if ($has_empty_error && $has_double_error)
						{
							break 2;
						}
					}
				}
			}
		}

		// エラーなし
		if (sizeof($this->oMgr->aryErrMsg) == 0)
		{
			return true;
		}

		// エラー発生
		$this->errMsg = $this->oMgr->getErrMsg();
		return false;
	}

	function postComplete()
	{
		$param = array();
		$param['mode'] = "complete";
		$this->oMgr->postTo($_SERVER['SCRIPT_NAME'], $param);
	}

}

?>
