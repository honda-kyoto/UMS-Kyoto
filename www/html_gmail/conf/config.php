<?php
// ini_set("display_errors", "On");

// 処理日時保持
$current_time = date('YmdHi');


// GoogleNewAPI用設定
	// GoogleNewAppドメイン名
	$google_new_admin['domain'] = 'kuhp.kyoto-u.ac.jp';

	// GoogleNewAPI管理者ログインID
	$google_new_admin['authEmail'] = '24147420016-d12oka5igv6h16gvkim2s3kpfrmvt05n@developer.gserviceaccount.com';

	// GoogleNewAPI管理者認証email
	$google_new_admin['apiAdminMail'] = 'api_developer@kuhp.kyoto-u.ac.jp';

	// GoogleNewAPI p12Key
	$google_new_admin['p12Key'] = 'GmailApi.p12';

	// GoogleNewAPI scope
	$google_new_admin['scopes'] = array(
		'https://www.googleapis.com/auth/admin.directory.user.readonly',
		'https://www.googleapis.com/auth/admin.directory.user'
	);
	

// GoogleAPI用設定
	// GoogleAppドメイン名
	$google_admin['domain'] = 'kuhp.kyoto-u.ac.jp';

	// GoogleApp管理者ログインID
	$google_admin['email'] = 'toinoue7_usercontrol@kuhp.kyoto-u.ac.jp';
	
	// GoogleApp管理者ログインPW
	$google_admin['password'] = 'TswxW47yxKSeXttg';


// Mailsearch DB登録用
	$maildb['url'] = "localhost";
	$maildb['user'] = "root";
	$maildb['pass'] = "root";
	$maildb['db'] = "mail";


// ファイル設定
	// ルートログディレクトリ
	$log_dir = "./log/";

	// エラーログファイルの保存先ディレクトリ
	$log_dir_error = "./log/error/";

	// アカウント作成ログファイルの保存先ディレクトリ
	$log_dir_create = "./log/create/";

	// ログファイル名
	$error_file_name = "error-log_".$current_time.".txt";
	$create_file_name = "create-log_".$current_time.".txt";

	// CSVヘッダ行
	$csv_header = 'アカウント名,職員番号,ﾌﾙﾈｰﾑ(漢字),姓(ローマ字),名(ローマ字),ﾌﾙﾈｰﾑ(ｶﾀｶﾅ),所属部署(漢字),所属部署(ローマ字),連絡先電話番号,職種(ローマ字1文字),役職(備考)';

	// CSVディレクトリ
	$log_dir_csv = "./log/csv/";

	// CSVファイル名
	$csv_file_name = "create-csv_".$current_time.".csv";



// KUHPサーバー情報
	// Delta(メーリングリストサーバー)
	$serverinfo['ml']['host_name'] = "Delta";
	$serverinfo['ml']['host_ip'] = "130.54.188.226";
	$serverinfo['ml']['ssh_port'] = 22;
	$serverinfo['ml']['login_user'] = "list";
	$serverinfo['ml']['password'] = "";

	// Kilo(Webサーバー)
	$serverinfo['web']['host_name'] = "Kilo";
	$serverinfo['web']['host_ip'] = "192.168.225.103";
	$serverinfo['web']['ssh_port'] = 22000;
	$serverinfo['web']['login_user'] = "netman";
	$serverinfo['web']['password'] = "";


// バッチ処理用
	// 経過上限値(日)		ログファイルが作成されてから上限値の日数が経過した場合、削除される
	$past_limit_date = 30;

	// アプリへのフルパス
	// $full_path = '/var/www/dev/gmail/';
	$full_path = '/var/www/gmail/';