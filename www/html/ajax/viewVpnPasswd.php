<?php
/**********************************************************
* File         : viewVpnPasswd.php
* Authors      : mie tsutsui
* Date         : 2013.01.29
* Last Update  : 2013.01.29
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/vpns_members.class.php");

//=================================
// メイン
//=================================

// モード設定
$_REQUEST["mode"] = "viewVpnPasswd";

// 処理オブジェクト生成
$mgr = new vpns_members($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 終了
exit;

?>
