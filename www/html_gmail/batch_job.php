<?php
// #######################################
// # ユーザーアカウント一括操作-バッチ処理
// #   ・一定期間経過後のログファイルの整理を行う
// #   ・期間の設定についてはconfig.iniを参照
// #   ・crontab設定
// #      
// # 
// #######################################

	require_once('conf/config.php');
	$today = date('Ymd');


	// createログファイルの取得
    $file_name = array();
	if ($dir = opendir($full_path."log/create/")) {
		if(!$dir){
			// ディレクトリオープン失敗
		}else{
		    while (($file = readdir($dir)) !== false) {
		        if ($file != "." && $file != "..") {
		            // echo "$file\n";
		            array_push($file_name, $file);
		        }
		    } 
		    closedir($dir);
		}
	}
	// 一番古いログファイルが配列の最初にくる(降順並び替え)
	sort($file_name);


	// ログファイルの生成日時チェックおよびログファイルの削除
	for($i = 0; $i < count($file_name); $i++ ){
		// ログファイルの生成年月日を取得し、今日の日付から何日経過しているかを算出する
		preg_match('/^create-log_([0-9]{8})([0-9]{4})(.txt)$/', $file_name[$i], $matches);
		$past_date = (strtotime($today)-strtotime($matches[1])) / (60*60*24);

		if ($past_date >= $past_limit_date) {
			// ファイル削除
			unlink($full_path."log/create/".$file_name[$i]);
		}
	}
