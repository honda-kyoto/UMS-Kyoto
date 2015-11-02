<?php
/**********************************************************
* File         : mlists_members_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/mlists_regist_common_mgr.class.php");
require_once("sql/mlists_members_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mlists_members_mgr extends mlists_regist_common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	/*
	 * getList
	*/
	function getList($mlist_id, $type="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = 'list';
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}
		$sql = $this->getQuery('GETLIST', $args);

		$aryRet = $this->oDb->getAll($sql);

		return $aryRet;
	}

	/*
	 * getMember
	*/
	function getMember($mlist_id, $mail_addr)
	{
		$args = array();
		$args[0] = $mlist_id;
		$args[1] = $mail_addr;

		$sql = $this->getQuery('GETMEMBER', $args);

		$aryRet = $this->oDb->getRow($sql);

		return $aryRet;
	}

	function getSenderKbn($mlist_id)
	{
		$sql = $this->getQuery('GET_SENDER_KBN', $mlist_id);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}

	function getMlistKbn($mlist_id)
	{
		$sql = $this->getQuery('GET_MLIST_KBN', $mlist_id);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}

	function existsMailAddr($mail_addr, $mlist_id)
	{
		$args = array();
		$args[0] = $mlist_id;
		$args[1] = $mail_addr;

		$sql = $this->getQuery('EXISTS_MAIL_ADDR', $args);

		$ret = $this->oDb->getOne($sql);

		if ($ret != "")
		{
			return true;
		}

		return false;

	}

	function addMember($request, $type="")
	{
		$this->oDb->begin();

		if ($type == "work")
		{
			if (!$this->existsMlistAutoSet($request['mlist_id'], 'work'))
			{
				// 最初の作業の場合本番データをコピー
				$ret = $this->copyDataToWork($request['mlist_id']);

				if (!$ret)
				{
					$this->oDb->rollback();
					return;
				}
			}
		}

		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($request['mlist_id']);
		$args['MAIL_ADDR'] = $this->sqlItemChar($request['mail_addr']);
		$args['MEMBER_NAME'] = $this->sqlItemChar($request['member_name']);
		$args['SENDER_FLG'] = $this->sqlItemFlg(@$request['sender_flg']);
		$args['RECIPIENT_FLG'] = $this->sqlItemFlg(@$request['recipient_flg']);
		$args['TYPE'] = "list";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		// 存在チェック
		$sql = $this->getQuery('EXISTS_MLIST_MEMBER', $args);

		$addr = $this->oDb->getOne($sql);

		if ($addr == "")
		{
			$sql_id = "INSERT_MLIST_MEMBER";
		}
		else
		{
			$sql_id = "UPDATE_MLIST_MEMBER";
		}

		$sql = $this->getQuery($sql_id, $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// 同期
		if ($type == "")
		{
			$this->addMailingMembers($request);
		}

		return true;
	}

	function deleteMember($mlist_id, $mail_addr, $type="")
	{
		$this->oDb->begin();

		if ($type == "work")
		{
			if (!$this->existsMlistAutoSet($mlist_id, 'work'))
			{
				// 最初の作業の場合本番データをコピー
				$ret = $this->copyDataToWork($mlist_id);

				if (!$ret)
				{
					$this->oDb->rollback();
					return;
				}
			}
		}

		$aryFlgs = $this->getMember($mlist_id, $mail_addr);

		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;
		$args[1] = $mail_addr;
		$args['TYPE'] = "list";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		$sql = $this->getQuery('DELETE_MLIST_MEMBER', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// 同期
		if ($type == "")
		{
			$this->delMailingMembers($mlist_id, $mail_addr, $aryFlgs);
		}

		return true;

	}

	function getAutoSetType($mlist_id, $type="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "tbl";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}
		$sql = $this->getQuery('GET_AUTO_SET_TYPE', $args);

		$ret = $this->oDb->getOne($sql);

		return $ret;
	}

	function getAutoCondList($mlist_id, $type="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "list";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}
		$sql = $this->getQuery('GET_AUTO_COND_LIST', $args);

		$ary = $this->oDb->getAssoc($sql);

		return $ary;
	}

	function existsMlistAutoSet($mlist_id, $type="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "tbl";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		// 設定データの存在チェック
		$sql = $this->getQuery('EXISTS_MLIST_AUTO_SET', $args);

		$id = $this->oDb->getOne($sql);

		if ($id != "")
		{
			return true;
		}

		return false;
	}

	function copyDataToWork($mlist_id)
	{
		if ($this->existsMlistAutoSet($mlist_id))
		{
			// 設定テーブル
			$sql = $this->getQuery('COPY_MLIST_AUTO_SET', $mlist_id);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				return false;
			}

			// 条件リスト
			$sql = $this->getQuery('COPY_MLIST_AUTO_COND', $mlist_id);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				return false;
			}

			// 送信者リスト
			$sql = $this->getQuery('COPY_MLIST_MEMBERS', $mlist_id);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				Debug_Print($sql);
				return false;
			}
		}
		else
		{
			// 設定テーブルだけ作成
			$ret = $this->insertMlistAutoSet($mlist_id, '0', 'work');

			if (!$ret)
			{
				return false;
			}
		}

		return true;
	}

	function insertMlistAutoSet($mlist_id, $set_type, $type="")
	{
		$args = $this->getSqlArgs();
		$args[0] = $mlist_id;
		$args[1] = $set_type;
		$args['TYPE'] = 'tbl';
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		$sql = $this->getQuery('INSERT_MLIST_AUTO_SET', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function addAutoCond($request)
	{
		$this->oDb->begin();

		if (!$this->existsMlistAutoSet($request['mlist_id'], 'work'))
		{
			// 最初の作業の場合本番データをコピー
			$ret = $this->copyDataToWork($request['mlist_id']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return;
			}
		}

		if (@$request['post_id'] == "" && @$request['job_id'] == "" && @$request['joukin_kbn'] == ""
				&& @$request['belong_class_id'] == "" && @$request['belong_div_id'] == ""
				 && @$request['belong_dep_id'] == "" && @$request['belong_sec_id'] == "" && @$request['belong_chg_id'] == "")
		{
			// 全対象の場合一旦条件をクリア
			$args = $this->getSqlArgs();
			$args['MLIST_ID'] = $this->sqlItemInteger($request['mlist_id']);
			$args['TYPE'] = 'work';

			$sql = $this->getQuery('CLEAR_MLIST_AUTO_COND', $args);

			$this->oDb->query($sql);
		}

		// レコード作成
		$ret = $this->insertMlistAutoCond($request, 'work');

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}

	function insertMlistAutoCond($request, $type="")
	{
		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($request['mlist_id']);
		$args['TYPE'] = 'list';
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}
		$args['POST_ID'] = $this->sqlItemInteger(@$request['post_id']);
		$args['JOB_ID'] = $this->sqlItemInteger(@$request['job_id']);
		$args['JOUKIN_KBN'] = $this->sqlItemChar(@$request['joukin_kbn']);
		$args['BELONG_CLASS_ID'] = $this->sqlItemInteger(@$request['belong_class_id']);
		$args['BELONG_DIV_ID'] = $this->sqlItemInteger(@$request['belong_div_id']);
		$args['BELONG_DEP_ID'] = $this->sqlItemInteger(@$request['belong_dep_id']);
		$args['BELONG_SEC_ID'] = $this->sqlItemInteger(@$request['belong_sec_id']);
		$args['BELONG_CHG_ID'] = $this->sqlItemInteger(@$request['belong_chg_id']);

		$sql = $this->getQuery('INSERT_MLIST_AUTO_COND', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function delAutoCond($request)
	{
		$this->oDb->begin();

		if (!$this->existsMlistAutoSet($request['mlist_id'], 'work'))
		{
			// 最初の作業の場合本番データをコピー
			$ret = $this->copyDataToWork($request['mlist_id']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return;
			}
		}

		$ret = $this->deleteMlistAutoCond($request, 'work');

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}

	function deleteMlistAutoCond($request, $type="")
	{
		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($request['mlist_id']);
		$args['LIST_NO'] = $this->sqlItemInteger($request['list_no']);
		$args['TYPE'] = 'list';
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		$sql = $this->getQuery('DELETE_MLIST_AUTO_COND', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}


	function chgSenderSetType($request)
	{
		$this->oDb->begin();

		if (!$this->existsMlistAutoSet($request['mlist_id'], 'work'))
		{
			// 最初の作業の場合本番データをコピー
			$ret = $this->copyDataToWork($request['mlist_id']);

			if (!$ret)
			{
				$this->oDb->rollback();
				return;
			}
		}

		$ret = $this->updateSenderSetType($request, 'work');

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}

	function updateSenderSetType($request, $type="")
	{
		$args = $this->getSqlArgs();
		$args['MLIST_ID'] = $this->sqlItemInteger($request['mlist_id']);
		$args['SENDER_SET_TYPE'] = $this->sqlItemChar($request['sender_set_type']);
		$args['TYPE'] = 'tbl';
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		$sql = $this->getQuery('UPDATE_SENDER_SET_TYPE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;

	}

	function cancelAutoCondData($mlist_id)
	{
		$this->oDb->begin();
		//
		// 作業テーブルを全部消す
		//

		// メンバー
		$ret = $this->truncateMlistMembers($mlist_id, "work");

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 条件リスト
		$ret = $this->truncateMlistAutoCond($mlist_id, "work");

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 送信者設定
		$ret = $this->truncateMlistAutoSetType($mlist_id, "work");

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		return true;
	}


	function commitAutoCondData($mlist_id)
	{
		$this->oDb->begin();
		//
		// 本番テーブルを全部消す
		//

		// メンバー
		$ret = $this->truncateMlistMembers($mlist_id);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 条件リスト
		$ret = $this->truncateMlistAutoCond($mlist_id);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 送信者設定
		$ret = $this->truncateMlistAutoSetType($mlist_id);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		//
		// 作業テーブルから本番テーブルへコピー
		//


		// 設定テーブル
		$sql = $this->getQuery('COMMIT_MLIST_AUTO_SET', $mlist_id);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// 条件リスト
		$sql = $this->getQuery('COMMIT_MLIST_AUTO_COND', $mlist_id);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		// 送信者リスト
		$sql = $this->getQuery('COMMIT_MLIST_MEMBERS', $mlist_id);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		//
		// 作業テーブルを全部消す
		//

		// メンバー
		$ret = $this->truncateMlistMembers($mlist_id, "work");

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 条件リスト
		$ret = $this->truncateMlistAutoCond($mlist_id, "work");

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		// 送信者設定
		$ret = $this->truncateMlistAutoSetType($mlist_id, "work");

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// 同期
		$this->relationAutoMembers($mlist_id);

		return true;

	}

	function truncateMlistMembers($mlist_id, $type="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "list";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		$sql = $this->getQuery('TRUNCATE_MLIST_MEMBERS', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function truncateMlistAutoCond($mlist_id, $type="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "list";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		$sql = $this->getQuery('TRUNCATE_MLIST_AUTO_COND', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	function truncateMlistAutoSetType($mlist_id, $type="")
	{
		$args = array();
		$args[0] = $mlist_id;
		$args['TYPE'] = "tbl";
		if ($type != "")
		{
			$args['TYPE'] = $type;
		}

		$sql = $this->getQuery('TRUNCATE_MLIST_AUTO_SET_TYPE', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			Debug_Print($sql);
			return false;
		}

		return true;
	}

	/* ここからAPI関連 */

	function searchMlistAutoMembers($mlist_id)
	{
		// 検索条件取得
		$aryCondList = $this->getAutoCondList($mlist_id);
		$arySql = array();

		if (is_array($aryCondList))
		{
			foreach ($aryCondList AS $aryVal)
			{
				$aryCond = array();

				// 常勤/非常勤
				if (@$aryVal['joukin_kbn'] != "")
				{
					$aryCond[] = "UM.joukin_kbn = '" . string::replaceSql($aryVal['joukin_kbn']) . "'";
				}

				// 所属・職種・役職はまとめて処理

				// 所属
				$strMainCond = "";
				$strSubFrom = "";
				$strSubCond = "";
				if (@$aryVal['belong_chg_id'] != "")
				{
					$strMainCond = "belong_chg_id = " . string::replaceSql($aryVal['belong_chg_id']);
					$strSubFrom = "";
					$strSubCond = "USC.belong_chg_id = " . string::replaceSql($aryVal['belong_chg_id']);
				}
				else if (@$aryVal['belong_sec_id'] != "")
				{
					$strMainCond = "EXISTS (SELECT * FROM belong_chg_mst WHERE UM.belong_chg_id = belong_chg_id AND del_flg = '0' AND belong_sec_id = " . string::replaceSql($aryVal['belong_sec_id']) . ")";
					$strSubFrom = ",belong_chg_mst AS BCM";
					$strSubCond = "USC.belong_chg_id = BCM.belong_chg_id AND BCM.del_flg = '0' AND BCM.belong_sec_id = " . string::replaceSql($aryVal['belong_sec_id']);
				}
				else if (@$aryVal['belong_dep_id'] != "")
				{
					$strMainCond = "EXISTS (SELECT * FROM belong_chg_mst AS BCM,belong_sec_mst AS BSM WHERE UM.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BSM.belong_dep_id = " . string::replaceSql($aryVal['belong_dep_id']) . ")";
					$strSubFrom = ",belong_chg_mst AS BCM,belong_sec_mst AS BSM";
					$strSubCond = "USC.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BSM.belong_dep_id = " . string::replaceSql($aryVal['belong_dep_id']);
				}
				else if (@$aryVal['belong_div_id'] != "")
				{
					$strMainCond = "EXISTS (SELECT * FROM belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM WHERE UM.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BDM.belong_div_id = " . string::replaceSql($aryVal['belong_div_id']) . ")";
					$strSubFrom = ",belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM";
					$strSubCond = "USC.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BDM.belong_div_id = " . string::replaceSql($aryVal['belong_div_id']);
				}
				else if (@$aryVal['belong_class_id'] != "")
				{
					$strMainCond = "EXISTS (SELECT * FROM belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM,belong_div_mst AS BVM WHERE UM.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BDM.belong_div_id = BVM.belong_div_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BVM.del_flg = '0' AND BVM.belong_class_id = " . string::replaceSql($aryVal['belong_class_id']) . ")";
					$strSubFrom = ",belong_chg_mst AS BCM,belong_sec_mst AS BSM,belong_dep_mst AS BDM,belong_div_mst AS BVM";
					$strSubCond = "USC.belong_chg_id = BCM.belong_chg_id AND BCM.belong_sec_id = BSM.belong_sec_id AND BSM.belong_dep_id = BDM.belong_dep_id AND BDM.belong_div_id = BVM.belong_div_id AND BCM.del_flg = '0' AND BSM.del_flg = '0' AND BDM.del_flg = '0' AND BVM.del_flg = '0' AND BVM.belong_class_id = " . string::replaceSql($aryVal['belong_class_id']);
				}

				$strMain = $strMainCond;
				$strSub = $strSubCond;
				// 職種
				if (@$aryVal['job_id'] != "")
				{
					if ($strMain != "")
					{
						$strMain .= " AND ";
						$strSub .= " AND ";
					}
					$strMain .= "job_id = " . string::replaceSql($aryVal['job_id']);
					$strSub .= "USC.job_id = " . string::replaceSql($aryVal['job_id']);
				}

				// 役職
				if (@$aryVal['post_id'] != "")
				{
					if ($strMain != "")
					{
						$strMain .= " AND ";
						$strSub .= " AND ";
					}
					$strMain .= "post_id = " . string::replaceSql($aryVal['post_id']);
					$strSub .= "USC.post_id = " . string::replaceSql($aryVal['post_id']);
				}

				if ($strMain != "")
				{
					$strBuff = "(";
					$strBuff .= "(" . $strMain . ")";
					$strBuff .= " OR ";
					$strBuff .= "(EXISTS (SELECT * FROM user_sub_unit_view AS USC" . $strSubFrom . " WHERE UM.user_id = USC.user_id AND " . $strSub . "))";
					$strBuff .= ")";
					$aryCond[] = $strBuff;
				}

				$args['COND'] = "";
				if (count($aryCond) > 0)
				{
					$args['COND'] = " AND " . join(" AND ", $aryCond);
				}

				$arySql[] = $this->getQuery('SEARCH_MLIST_AUTO_MEMBERS', $args);
			}
		}

		if (is_array($arySql))
		{
			$sql = implode(" UNION ", $arySql);

		}

//		Debug_Trace($sql);

		$aryRet = array();
		if ($sql != "")
		{
			$aryRet = $this->oDb->getAll($sql);
		}

		return $aryRet;
	}

	function addMailingMembers($request)
	{
		$client = null;
		$this->createMlistClient(&$client);

		if (is_null($client))
		{
			return;
		}

		$mlist_acc = $this->getMlistAcc($request['mlist_id']);
		$sender_kbn = $this->getSenderKbn($request['mlist_id']);

		if ($sender_kbn == SENDER_KBN_FREE)
		{
			$params = array(
					'listName' => $mlist_acc,
					'member'    => $request['mail_addr'],
					'file'      => 'actives',
			);
			$res = $client->mailingMemAdd( $params );

			if ( $res->resultCode == 100 )
			{
				Debug_Trace("メンバー追加は成功しました", 151);
			}
			else if ( $res->resultCode == 200 )
			{
				Debug_Trace("メンバー追加は失敗しました(クライアント側エラー)", 151);
			}
			else
			{
				Debug_Trace("メンバー追加は失敗しました(サーバー側エラー)", 151);
			}
		}
		else
		{
			if (@$request['sender_flg'] == "1")
			{
				$params = array(
						'listName' => $mlist_acc,
						'member'    => $request['mail_addr'],
						'file'     => 'members',
				);
				$res = $client->mailingMemAdd( $params );

				if ( $res->resultCode == 100 )
				{
					Debug_Trace("メンバー追加は成功しました", 151);
				}
				else if ( $res->resultCode == 200 )
				{
					Debug_Trace("メンバー追加は失敗しました(クライアント側エラー)", 151);
					return;
				}
				else
				{
					Debug_Trace("メンバー追加は失敗しました(サーバー側エラー)", 151);
					return;
				}

			}

			if (@$request['recipient_flg'] == "1")
			{
				$params = array(
						'listName' => $mlist_acc,
						'member'    => $request['mail_addr'],
						'file'     => 'actives',
				);
				$res = $client->mailingMemAdd( $params );


				if ( $res->resultCode == 100 )
				{
					Debug_Trace("メンバー追加は成功しました", 151);
				}
				else if ( $res->resultCode == 200 )
				{
					Debug_Trace("メンバー追加は失敗しました(クライアント側エラー)", 151);
				}
				else
				{
					Debug_Trace("メンバー追加は失敗しました(サーバー側エラー)", 151);
				}

			}
		}

		return;
	}

	function delMailingMembers($mlist_id, $mail_addr, $aryFlgs)
	{
		$client = null;
		$this->createMlistClient(&$client);

		if (is_null($client))
		{
			exit;
			return;
		}

		$mlist_acc = $this->getMlistAcc($mlist_id);

		$sender_kbn = $this->getSenderKbn($mlist_id);

		if ($sender_kbn == SENDER_KBN_FREE)
		{
			$params = array(
					'listName' => $mlist_acc,
					'member'    => $mail_addr,
					'file'      => 'actives',
			);
			$res = $client->mailingMemDelete( $params );

			if ( $res->resultCode == 100 )
			{
				Debug_Trace("メンバー削除は成功しました", 351);
			}
			else if ( $res->resultCode == 200 )
			{
				Debug_Trace("メンバー削除は失敗しました(クライアント側エラー)", 351);
			}
			else
			{
				Debug_Trace("メンバー削除は失敗しました(サーバー側エラー)", 351);
			}
		}
		else
		{
			if (@$aryFlgs['sender_flg'] == "1")
			{
				$params = array(
						'listName' => $mlist_acc,
						'member'    => $mail_addr,
						'file'     => 'members',
				);
				$res = $client->mailingMemDelete( $params );

				if ( $res->resultCode == 100 )
				{
					Debug_Trace("メンバー削除は成功しました", 351);
				}
				else if ( $res->resultCode == 200 )
				{
					Debug_Trace("メンバー削除は失敗しました(クライアント側エラー)", 351);
					return;
				}
				else
				{
					Debug_Trace("メンバー削除は失敗しました(サーバー側エラー)", 351);
					return;
				}

			}

			if (@$aryFlgs['recipient_flg'] == "1")
			{
				$params = array(
						'listName' => $mlist_acc,
						'member'    => $mail_addr,
						'file'     => 'actives',
				);
				$res = $client->mailingMemDelete( $params );


				if ( $res->resultCode == 100 )
				{
					Debug_Trace("メンバー削除は成功しました", 351);
				}
				else if ( $res->resultCode == 200 )
				{
					Debug_Trace("メンバー削除は失敗しました(クライアント側エラー)", 351);
				}
				else
				{
					Debug_Trace("メンバー削除は失敗しました(サーバー側エラー)", 351);
				}

			}
		}

		return;
	}
}
?>
