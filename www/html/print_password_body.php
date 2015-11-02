<?php
/**********************************************************
* File         : print_password.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/users_detail.class.php");
//=================================
// 各処理後の表示HTMLファイル
//=================================
$aryHtml[1] = "view_p/print_password_body.tpl";

//=================================
// メイン
//=================================
// モード設定
$_REQUEST["mode"] = "print";

// 処理オブジェクト生成
$mgr = new users_detail($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 表示ファイル取得
$strHtml = $aryHtml[$ret];

// 画面表示
include_once($strHtml);

// 終了
exit;
?>
