<?php
/**********************************************************
* File         : reissuePassword.php
* Authors      : mie tsutsui
* Date         : 2013.01.29
* Last Update  : 2013.01.29
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/users_detail.class.php");
//require_once("mod/users_edit.class.php");

//=================================
// メイン
//=================================

// モード設定
$_REQUEST["mode"] = "reissuePassword";

// 処理オブジェクト生成
$mgr = new users_detail($_REQUEST);
//$mgr = new users_edit($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 終了
exit;

?>
