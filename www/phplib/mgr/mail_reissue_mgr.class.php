<?php
/**********************************************************
* File         : mail_reissue_mgr.class.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("mgr/common_mgr.class.php");
require_once("sql/mail_reissue_sql.inc.php");

//----------------------------------------------------------
//	クラス名	：base
//	処理概要	：画面の共通関数クラス
//----------------------------------------------------------
class mail_reissue_mgr extends common_mgr
{

	function __construct()
	{
		parent::__construct();
	}

	function escapeMailAcc()
	{
		return $this->setReissuePasswd();
	}

	function reissueMailAcc($passwd)
	{
		return $this->setReissuePasswd($passwd);
	}

	function setReissuePasswd($mail_acc="")
	{
		$this->oDb->begin();

		$user_id = $this->getSessionData('LOGIN_USER_ID');


		if ($mail_acc != "")
		{
			// 現在のアカウントを取得
			$aryTmp = $this->getUserData();
			$oldmail_addr = $aryTmp['mail_acc'] . USER_MAIL_DOMAIN;

			$mail_acc_col = $this->sqlItemChar($mail_acc);
		}
		else
		{
			$mail_acc_col = 'mail_acc';
			$oldmail_addr = "";
		}

		if ($oldmail_addr != "")
		{
			$args = $this->getSqlArgs();

			$args[0] = $user_id;
			$args[1] = $oldmail_addr;

			$sql = $this->getQuery('ADD_OLDMAIL_LIST', $args);

			$ret = $this->oDb->query($sql);

			if (!$ret)
			{
				$this->oDb->rollback();
				return false;
			}
		}

		$args = $this->getSqlArgs();

		$args[0] = $user_id;
		$args['MAIL_ACC'] = $mail_acc_col;

		$sql = $this->getQuery('REISSUE_MAIL_ACC', $args);

		$ret = $this->oDb->query($sql);

		if (!$ret)
		{
			$this->oDb->rollback();
			return false;
		}

		$this->oDb->end();

		// メールサーバ更新
		$this->relationUserMailAddr('edit');

		return true;
	}

}

?>
