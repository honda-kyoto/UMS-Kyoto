<?php
	// 処理時間 計測開始
	$time_start = microtime(true);

	require_once('./class/logmessage_class.php');
	require_once('./class/csvvalidation_class.php');
	require_once('./class/googleapi_class.php');
	require_once('./class/webserver_class.php');
	require_once('./class/mlserver_class.php');
	require_once('./conf/config.php');

	ini_set('max_execution_time',0);

	// 初期化
	$past_time = "";
	$error_message = "";
	$error_users = "";
	$success_message = "";
	$check_result = "";

	$select_check = "";
	$select_create = "";
	$select_passwd = "";
	$select_stop = "";
	$select_reactive = "";
	$select_delete = "";

	switch ($_POST["func"]) {
		case 'check':
			$select_check = "selected";
			break;
		case 'create':
			$select_create = "selected";
			break;
		case 'changepass':
			$select_passwd = "selected";
			break;
		case 'stop':
			$select_stop = "selected";
			break;
		case 'reactive':
			$select_reactive = "selected";
			break;
		case 'delete':
			$select_delete = "selected";
			break;
		case 'liststop':
			$select_liststop = "selected";
			break;
		default:
			break;
	}

	$loading = new logmessage();
	$googleapi = new Googleapi($google_admin);
	if($_POST["func"] != "" and $_POST["user_list"] != "" ){

		// 受け取ったユーザー情報一覧を処理
		$user_list = $_POST['user_list'];

		// 改行コードの統一化->改行ごとに余計な空白を削除し配列化
		strtr($user_list, array_fill_keys(array("\r\n", "\r", "\n"), "\n"));
		$user_list = explode("\n",$user_list);
		for ($i=0; $i < count($user_list); $i++) {
			$user_list[$i] = trim($user_list[$i]);
		}
		// 空配列を削除してインデックスを詰める
		$user_list = array_filter($user_list, "strlen");
		$user_list = array_values($user_list);

		// アカウント一括操作
		if(count($user_list) > 0){

			// 処理中表示
			$loading->message("<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><div id='loading_message'>一括処理を開始します。<br />終了までしばらくお待ちください...<img src='./image/loading.gif'><br />\n", 'start');

			switch ($_POST["func"]) {
				// パスワード変更要求
				case 'changepass':
					break;

				// ユーザーアカウント停止
				case 'stop':
					break;

				// ユーザーアカウント再開
				case 'reactive':
					break;

				// メーリングリスト配信停止
				case 'liststop':
					break;

				// ユーザーアカウント削除
				case 'delete':
					break;

				// ユーザー登録状況チェック
				case 'check':
						$serverinfo['ml']['login_user'] 	= $_POST['mluser'];
						$serverinfo['ml']['password'] 		= $_POST['mlpass'];
						$serverinfo['web']['login_user'] 	= $_POST['webuser'];
						$serverinfo['web']['password'] 		= $_POST['webpass'];

						$loading->message('サーバー接続中...<br />', 'log');
//
//						// サーバーログイン可能かチェックを行う
//						$webserver = new Webserver($serverinfo['web']);
//						$mlserver = new Mlserver($serverinfo['ml']);
//						$res['web'] = $webserver->connect_server();
//						$res['ml'] = $mlserver->connect_server();
//
//						// サーバーへログイン(もしくは接続)で失敗したら終了させる。両方のサーバーへログインできて初めて処理継続。
//						if(!$res['web']['result'] or !$res['ml']['result']){
//							$error_message .= "<div class='error_message'>";
//							if(!$res['web']['result']){
//								$error_message .= "<div style='color:red;'>".$res['web']['detail']."</div>";
//							}
//							if(!$res['ml']['result']){
//								$error_message .= "<div style='color:red;'>".$res['ml']['detail']."</div>";
//							}
//							$error_message .= "</div>";
//
//							$loading->message('</div>','end');
//							require_once('./include/index.html');
//							exit();
//						}

//						$loading->message('サーバー接続成功<br /><br />', 'log');
						$loading->message("[登録状況チェック]<br />", 'log');
						// 登録状況チェック ここから
						for($i=0; $i < count($user_list); $i++){
							// 初期化
							$check_status[$i]['user_name']			= '';
							$check_status[$i]['gmail'] 				= '';
							$check_status[$i]['gmail_pass_req'] 	= '';
							$check_status[$i]['suspended'] 			= '';
							$check_status[$i]['web'] 				= '';
							$check_status[$i]['ml'] 				= '';


							$check_status[$i]['user_name'] = $user_list[$i];
							$account_info[$i]['accountname'] = $user_list[$i];

							// Gmail確認
							$loading->message('・ユーザー名:'.$account_info[$i]['accountname'].' 登録状況チェック開始<br />', 'log');
							try{
								$user_existance = $googleapi->get_user_info($account_info[$i]['accountname']);
							}catch(Exception $e){
								// ユーザーが存在しない場合は例外が投げられるはずだか、実際投げられていないので、returnされた値からユーザー存在チェック
							}
							$result_name = (string)$user_existance->login->username;
							if($result_name == $account_info[$i]['accountname']){
								$check_status[$i]['gmail'] = 'Registered';

								// パスワード変更要求確認
								if($user_existance->login->changePasswordAtNextLogin){
									$check_status[$i]['gmail_pass_req'] = 'Yes';
								}else{
									$check_status[$i]['gmail_pass_req'] = 'No';
								}

								if($user_existance->login->suspended){
									$check_status[$i]['suspended'] = 'Stop';
								}else{
									$check_status[$i]['suspended'] = 'Active';
								}

							}else{
								$check_status[$i]['gmail'] = 'Not registered';
								$check_status[$i]['suspended'] = '-';
								$check_status[$i]['gmail_pass_req'] = '-';
							}

						}
					break;


				// ユーザーアカウント作成
				case 'create':

					break;

				default:
					break;
			} // 各一括処理 終了


// var_dump($csv_data);

			// アカウント作成処理以外の終了処理
			if($_POST["func"] != 'create'){
				// エラーなしで処理完了画面表示
				// エラーありでログファイル生成およびログ表示
				if($error_message == ""){
					$success_message  = "<div class='success_message'>";
					$success_message .= "<div>".$i."件の処理が正常に終了しました。 エラーはありません。</div>";
					$success_message .= "</div>";
				}else{
					// ログファイル本文生成
					$errorlog_data  = "===== GoogleAPI エラーログ =====\r\n";
					$errorlog_data .= "実行日時: ".date('Y/m/d H:i')."\r\n";
					$errorlog_data .= "実行内容: ".$_POST['func']."\r\n";
					$errorlog_data .= "(changepass = パスワード変更要求, stop = アカウント停止, reactive = アカウント再開, delete = アカウント削除)\r\n";
					$errorlog_data .= "\r\n";
					$errorlog_data .= "\r\n";
					$errorlog_data .= "--- エラーメッセージ 一覧 ---\r\n";
					$errorlog_data .= str_replace("<br>", "\r\n", $error_message);
					$errorlog_data .= "\r\n";
					$errorlog_data .= "\r\n";
					$errorlog_data .= "--- エラー発生ユーザー 一覧 ---\r\n";
					$errorlog_data .= $error_users;

					// 文字エンコードをSJISに変換しファイル出力
					$errorlog_data = mb_convert_encoding($errorlog_data, "SJIS", "UTF-8");
					file_put_contents($log_dir_error.$error_file_name, $errorlog_data);


					// HTMLログ出力
					$error_header  = "<div class='error_message'>";
					$error_header .= "<div style='color:red; font-weight:bold;'>!!! エラー発生 !!!</div>";
					$error_header .= "エラーログを保存しました。保存されたエラーログを見る場合は<a href='".$log_dir_error.$error_file_name. "'>こちら</a>をクリックしてください。<br><br>";
					$error_header .= "--- Error Message ---<br>";

					$error_message  = $error_header . $error_message;
					$error_message .= "</div>";
				}

				// 登録状況チェック時のHTML出力処理
				if(isset($check_status)){
					$check_result  = "<br><hr>";
					$check_result .= "<table id='check_table'><caption>アカウント登録状況一覧</caption>";
					$check_result .= "<tr><th rowspan='2'>アカウント名</th><th colspan='3'>Gmail</th><th rowspan='2'>Web領域</th><th rowspan='2'>メーリングリスト</th></tr>";
					$check_result .= "<tr><th>アカウント有無</th><th>アカウントステータス</th><th>次回ﾛｸﾞｲﾝ時Pass変更要求</th></tr>";
					for ($i=0; $i < count($check_status); $i++) {
						$check_result .= "<tr><td>".$check_status[$i]['user_name']."</td><td>".$check_status[$i]['gmail']."</td><td>".$check_status[$i]['suspended']."</td><td>".$check_status[$i]['gmail_pass_req']."</td><td>".$check_status[$i]['web']."</td><td>".$ml_result[$i]."</td></tr>";
					}
					$check_result .= "</table>";
				}


			}else{
				// アカウント作成処理時の終了処理
				$create_log  = "===== ユーザーアカウント登録実行ログ =====\r\n";
				$create_log .= "処理日時: ".date('Y/m/d H:i')."\r\n";
				$create_log .= "処理者IP: ".$_SERVER['REMOTE_ADDR']."\r\n";
				$create_log .= "処理件数: ".count($account_info)."件\r\n";
				$create_log .= "\r\n";
				$create_log .= "\r\n";
				$create_log .= "----- アカウント登録ステータス一覧 -----\r\n";
				$create_log .= "[AccName]		[Gmail]		[PChangeReq]	[WebSrv]	[MLSrv]\r\n";

				$except_log  = "\r\n";
				$except_log .= "\r\n";
				$except_log .= "----- エラー・警告ログ一覧 -----\r\n";

				$pass_log	 = "\r\n";
				$pass_log	.= "\r\n";
				$pass_log	.= "----- ログインパスワード一覧 -----\r\n";
				$pass_log	.= "[Gamil]			[Password]\r\n";

				// ログ詳細部分の生成
				for($j = $start; $j <= count($create_status); $j++){
					$create_log .= $account_info[$j]['accountname']."		".$create_status[$j]['gmail']."		".$create_status[$j]['gmail_pass_req']."		".$create_status[$j]['web']."		".$create_status[$j]['ml']."\r\n";
					if($create_status[$j]['gmail'] == 'NG' or $create_status[$j]['gmail'] == 'SKIP'){
						$except_log .= $create_status[$j]['gmail_detail']."\r\n";
						$pass_log .= $account_info[$j]['accountname']."		[".$create_status[$j]['gmail']."]\r\n";
					}else{
						$pass_log .= $account_info[$j]['accountname']."		".$account_info[$j]['password']."\r\n";
						if($create_status[$j]['gmail_pass_req'] == "NG" or $create_status[$j]['gmail_pass_req'] == "SKIP"){
							$except_log .= $create_status[$j]['gmail_pass_req_detail']."\r\n";
						}
						if($create_status[$j]['web'] == 'NG' or $create_status[$j]['web'] == 'SKIP'){
							$except_log .= $create_status[$j]['web_detail']."\r\n";
						}
						if($create_status[$j]['ml'] == 'NG' or $create_status[$j]['ml'] == 'SKIP'){
							$except_log .= $create_status[$j]['ml_detail']."\r\n";
						}
					}
				}
				// ログの結合
				$create_log = $create_log.$except_log.$pass_log;

				// ファイル出力(ログ、CSV)
				$create_log = mb_convert_encoding($create_log, "SJIS", "UTF-8");
				$csv_data = mb_convert_encoding($csv_data, "SJIS", "UTF-8");
				file_put_contents($log_dir_create.$create_file_name, $create_log);
				file_put_contents($log_dir_csv.$csv_file_name, $csv_data);


				// HTML出力
				$success_message  = "<div class='success_message'>";
				$success_message .= "<div>アカウント作成/登録処理が終了しました。<a href='".$log_dir_create.$create_file_name."'>ログファイル</a>を必ず確認してください。</div>";
				$success_message .= "<div>※アカウント登録時のパスワードやエラー情報は、このログファイルにのみ表示されます。</div>";
				$success_message .= "</div>";
			}
		}
	}else{
		// 情報不足時の処理
		if(isset($_POST['mode']) and $_POST['mode'] == "exec"){
			$error_message  = "<div class='error_message'>";
			if($_POST['user_list'] == ""){
				$error_message .= "<div style='color:red;'>アカウント情報を入力してください。</div>";
			}
			if($_POST['func'] == ""){
				$error_message .= "<div style='color:red;'>機能を選択してください。</div>";
			}
			$error_message .= "</div>";
		}
	}

	// 処理時間 計測終了
	$time_end = microtime(true);

	// 処理時間 表示
	$past_time = $time_end - $time_start;
	$past_time = round($past_time, 2);
	$past_minute = floor($past_time / 60);
	$past_seccond = $past_time - ($past_minute * 60);
	$past_time = "<div style='font-size:12;'>(処理時間: " .$past_minute. "分" .$past_seccond. "秒)</div>";

	// 処理中メッセージ(id='loading_message')を閉じる
	$loading->message('</div>', 'end');
	require_once('./include/index.html');