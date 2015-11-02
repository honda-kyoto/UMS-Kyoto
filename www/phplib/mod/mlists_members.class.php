<?php
/**********************************************************
* File         : mlists_members.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

require_once("mod/common.class.php");
require_once("mgr/mlists_members_mgr.class.php");

class mlists_members extends common
{
	var $sender_kbn;
	var $mlist_kbn;
	var $has_work_data;

	//============================================
	// コンストラクタ
	//============================================
	function __construct($request)
	{
		parent::__construct($request);

		$this->page_title = "ＭＬ参加者リスト　";
		$this->header_file = "mlists_members_head.tpl";
		// 基本処理オブジェクト生成
		$this->oMgr = new mlists_members_mgr();
	}

	/*======================================================
	* Name         : runList
	* IN           :
	* OUT          : 画面番号
	* Discription  : ログイン画面
	=======================================================*/
	function runList()
	{
		$this->setListData();

		if ($this->request['complete'] == "1")
		{
			$this->oMgr->setErr('C002');
			$this->errMsg = $this->oMgr->getErrMsg();
		}

		return 1;
	}

	function runView()
	{
		$this->setAutoMembersList();

		return 1;
	}

	function runAdd()
	{
		$this->mlist_kbn = $this->oMgr->getMlistKbn($this->request['mlist_id']);

		// 入力値チェック
		$ret = $this->checkInputdata();

		// エラーあり
		if (!$ret)
		{
			$this->setListData();
			return 1;
		}

		if ($this->mlist_kbn == MLIST_KBN_AUTO)
		{
			$ret = $this->oMgr->addMember($this->request, "work");
			$is_work = true;
		}
		else
		{
			$ret = $this->oMgr->addMember($this->request);
			$is_work = false;
		}

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete($is_work);
	}

	/*======================================================
	 * Name         : runDelete
	* IN           :
	* OUT          : [1]正常処理 [0]異常終了
	* Discription  :
	=======================================================*/
	function runDelete()
	{
		$mlist_kbn = $this->oMgr->getMlistKbn($this->request['mlist_id']);
		if ($mlist_kbn == MLIST_KBN_AUTO)
		{
			$ret = $this->oMgr->deleteMember($this->request['mlist_id'], $this->request['target_addr'], "work");
			$is_work = true;
		}
		else
		{
			$ret = $this->oMgr->deleteMember($this->request['mlist_id'], $this->request['target_addr']);
			$is_work = false;
		}

		if (!$ret)
		{
			$this->setListData();
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete($is_work);
	}

	function runPush()
	{
		$ret = $this->oMgr->addAutoCond($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(true);
	}

	function runPop()
	{
		$ret = $this->oMgr->delAutoCond($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(true);
	}

	function runChange()
	{
		$ret = $this->oMgr->chgSenderSetType($this->request);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(true);
	}

	function runCancel()
	{
		$ret = $this->oMgr->cancelAutoCondData($this->request['mlist_id']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(false);
	}

	function runCommit()
	{
		$ret = $this->oMgr->commitAutoCondData($this->request['mlist_id']);

		if (!$ret)
		{
			$this->oMgr->showSystemError();
		}

		// リダイレクト
		$this->postComplete(false);
	}

	function postComplete($is_work)
	{
		$param = array();
		$param['mode'] = 'list';
		$param['mlist_id'] = $this->request['mlist_id'];

		if (!$is_work)
		{
			$param['complete'] = "1";
		}
		$this->oMgr->postTo('mlists_members.php', $param);
	}

	function setListData()
	{
		$this->sender_kbn = $this->oMgr->getSenderKbn($this->request['mlist_id']);
		$this->mlist_kbn = $this->oMgr->getMlistKbn($this->request['mlist_id']);

		$aryHead = $this->oMgr->getMlistData($this->request['mlist_id']);
		$this->output['mlist_name'] = $aryHead['mlist_name'];
		$this->output['mlist_acc'] = $aryHead['mlist_acc'];

		if ($this->mlist_kbn == MLIST_KBN_AUTO)
		{
			$this->setAutoCondData();
		}
		else
		{
			$this->setMembersData();
		}
	}

	function setAutoCondData()
	{
		$this->has_work_data = $this->oMgr->existsMlistAutoSet($this->request['mlist_id'], 'work');

		$this->request['sender_set_type'] = $this->oMgr->getAutoSetType($this->request['mlist_id'], 'work');

		if ($this->request['sender_set_type'] == "")
		{
			$this->request['sender_set_type'] = $this->oMgr->getAutoSetType($this->request['mlist_id']);
		}

		if ($this->request['sender_set_type'] == "")
		{
			$this->request['sender_set_type'] = "0";
		}

		// 条件部分作成
		$this->setAutoCondList();

		if ($this->sender_kbn == SENDER_KBN_LIMIT && $this->request['sender_set_type'] == SENDER_SET_TYPE_LIMIT )
		{
			$this->aryList = $this->oMgr->getList($this->request['mlist_id'], 'work');
			if (count(@$this->aryList) == 0)
			{
				// 登録済みデータ
				$this->aryList = $this->oMgr->getList($this->request['mlist_id']);

				$key = 0;
				if (is_array($this->aryList))
				{
					foreach ($this->aryList AS $aryData)
					{
						$this->aryList[$key]['mail_addr'] = htmlspecialchars($aryData['mail_addr']);
						$this->aryList[$key]['member_name'] = htmlspecialchars($aryData['member_name']);

						$key++;
					}
				}

			}

		}
	}

	function setMembersData()
	{
		// 一覧データ取得
		$this->aryList = $this->oMgr->getList($this->request['mlist_id']);

		$check_img = '<img src="image/checkbox_small_black.gif" width="17" height="15" border="0">';

		$key = 0;
		if (is_array($this->aryList))
		{
			foreach ($this->aryList AS $aryData)
			{
				$this->aryList[$key]['mail_addr'] = htmlspecialchars($aryData['mail_addr']);
				$this->aryList[$key]['member_name'] = htmlspecialchars($aryData['member_name']);
				$sender_flg = "&nbsp;";
				if ($aryData['sender_flg'] == '1')
				{
					$sender_flg = $check_img;
				}
				$this->aryList[$key]['sender_flg'] = $sender_flg;
				$recipient_flg = "&nbsp;";
				if ($aryData['recipient_flg'] == '1')
				{
					$recipient_flg = $check_img;
				}
				$this->aryList[$key]['recipient_flg'] = $recipient_flg;

				$key++;
			}
		}
	}

	function setAutoMembersList()
	{
		$has_work_data = $this->oMgr->existsMlistAutoSet($this->request['mlist_id'], 'work');
		$type = "";
		if ($has_work_data)
		{
			$type = "work";
		}

		$mlist_id = $this->request['mlist_id'];

		$aryTmp = array();

		$this->sender_kbn = $this->oMgr->getSenderKbn($mlist_id);

		$check_img = '<img src="image/checkbox_small_black.gif" width="17" height="15" border="0">';

		if ($this->sender_kbn == SENDER_KBN_LIMIT)
		{
			// 制限アリの場合設定方法を取得
			$sender_set_type = $this->oMgr->getAutoSetType($mlist_id, $type);

			// 別途指定の場合送信者リストを取得
			if ($sender_set_type == SENDER_SET_TYPE_LIMIT)
			{
				$aryTmp = $this->oMgr->getList($mlist_id, $type);

				if (is_array($aryTmp))
				{
					foreach ($aryTmp AS $data)
					{
						$key = $data['mail_addr'];
						$this->aryList[$key]['mail_addr'] = htmlspecialchars($data['mail_addr']);
						$this->aryList[$key]['member_name'] = htmlspecialchars($data['member_name']);
						$this->aryList[$key]['sender_flg'] = $check_img;
						$this->aryList[$key]['recipient_flg'] = "&nbsp;";
					}
				}
			}
		}

		// 検索実行
		$aryUsers = $this->oMgr->searchMlistAutoMembers($mlist_id, $type);

		if (is_array($aryUsers))
		{
			foreach ($aryUsers AS $data)
			{
				$mail_acc = $data['mail_acc'];
				if ($data['mail_acc'] == "")
				{
					$mail_acc = $data['login_id'];
				}
				$key = $mail_acc . USER_MAIL_DOMAIN;
				$this->aryList[$key]['mail_addr'] = htmlspecialchars($key);
				$this->aryList[$key]['member_name'] = htmlspecialchars($data['kanjisei']."　".$data['kanjimei']);
				$this->aryList[$key]['belong_name'] = $this->oMgr->getBelongName($data['belong_chg_id']);
				$this->aryList[$key]['sender_flg'] = "&nbsp;";
				$this->aryList[$key]['recipient_flg'] = $check_img;
				if ($this->sender_kbn == SENDER_KBN_LIMIT && $sender_set_type == SENDER_SET_TYPE_MEMBER)
				{
					// 送信者制限がある場合でメンバーが送信可能の場合送信者にもメンバーアドレスを追加
					$this->aryList[$key]['sender_flg'] = $check_img;
				}
			}
		}



		// 条件部分作成
		$this->setAutoCondList();
	}


	function setAutoCondList()
	{

		// 作業データ
		$aryTmp = $this->oMgr->getAutoCondList($this->request['mlist_id'], 'work');
		if (count(@$aryTmp) == 0)
		{
			// 登録済みデータ
			$aryTmp = $this->oMgr->getAutoCondList($this->request['mlist_id']);
		}

		$aryPost = $this->oMgr->getPostAry();
		$aryJob = $this->oMgr->getJobAry();
		if (is_array($aryTmp) && count($aryTmp > 0))
		{
			$this->aryAutoCond = array();
			foreach ($aryTmp AS $no => $aryData)
			{
				if ($aryData['belong_class_id'] == "" && $aryData['belong_div_id'] == "" && $aryData['belong_dep_id'] == ""
						&& $aryData['belong_sec_id'] == "" && $aryData['belong_chg_id'] == ""
						&& $aryData['job_id'] == "" && $aryData['post_id'] == "" && $aryData['joukin_kbn'] == "")
				{
					$this->aryAutoCond[$no]['is_all_user'] = true;
					$this->is_all_user = true;
					break;
				}

				$this->aryAutoCond[$no]['belong_name'] = htmlspecialchars($this->oMgr->getBelongName(&$aryData));
				$this->aryAutoCond[$no]['post_name'] = htmlspecialchars($aryPost[$aryData['post_id']]);
				$this->aryAutoCond[$no]['job_name'] = htmlspecialchars($aryJob[$aryData['job_id']]);
				$this->aryAutoCond[$no]['joukin_name'] = htmlspecialchars($this->oMgr->getValue('joukin_kbn', $aryData['joukin_kbn']));
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
		// メールアドレス
		if (!$this->oMgr->checkEmpty($this->request['mail_addr']))
		{
			//
			$this->oMgr->setErr('E001',"メールアドレス");
		}
		else if (!string::checkMailAddr($this->request['mail_addr']))
		{
			// エラーメッセージをセット
			$this->oMgr->setErr('E006', "メールアドレス");
		}
		else if ($this->oMgr->existsMailAddr($this->request['mail_addr'], $this->request['mlist_id']))
		{
			$this->oMgr->setErr('E017',"メールアドレス");
		}

		$sender_kbn = $this->oMgr->getSenderKbn($this->request['mlist_id']);
		if ($sender_kbn == SENDER_KBN_LIMIT && $this->mlist_kbn != MLIST_KBN_AUTO)
		{
			if (!$this->oMgr->checkEmpty($this->request['sender_flg']) && !$this->oMgr->checkEmpty($this->request['recipient_flg']))
			{
				// エラーメッセージをセット
				$this->oMgr->setErr('E007', "受信または送信");
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
}

?>
