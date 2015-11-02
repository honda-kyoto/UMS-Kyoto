<?php
/**********************************************************
* File         : print_guests.php
* Authors      : sumio imoto
* Date         : 2013.05.21
* Last Update  : 2013.05.21
* Copyright    :
***********************************************************/

//=================================
// 共通処理
//=================================
// 処理プログラム
require_once("mod/guests_search.class.php");
//=================================
// 各処理後の表示HTMLファイル
//=================================
$aryHtml[1] = "view_p/print_guests_body.tpl";

//=================================
// メイン
//=================================
// モード設定
$_REQUEST["mode"] = "print";

// 処理オブジェクト生成
$mgr = new guests_search($_REQUEST);

// 処理開始
$ret = $mgr->run();

// 表示ファイル取得
$strHtml = $aryHtml[$ret];

// 画面表示
include_once($strHtml);

// 終了
exit;
?>
