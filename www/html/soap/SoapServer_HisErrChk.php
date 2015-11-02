<?php
/**
 * SOAPレスポンスをutf-8で返すため
 */
mb_internal_encoding('utf-8');
mb_http_output('utf-8');

/**
 * 提供するサービス（関数の場合）
 */
function receiveErrorData($ary)
{
	if (is_array($ary))
	{
		foreach ($ary AS $aryFile)
		{
			$file_path = "/var/www/phplib/hiscsv_err/" . $aryFile['file_name'];

			$file_data = $aryFile['file_data'];

			//
			// UTF-8に変換
			//
			//echo $strUser;

			$file_data = mb_convert_encoding($file_data, "UTF-8", "SJIS");

			file_put_contents($file_path, $file_data, LOCK_EX);
		}
	}
}

/**
 * SOAPサーバオブジェクトの作成
 */
$server = new SoapServer(null, array('uri' => 'http://10.1.2.17/soap/'));
//$server = new SoapServer(null, array('uri' => 'http://10.1.2.16/soap/'));

/**
 * サービスの追加
 */
$server->addFunction('sendUserData');

/**
 * サービスを実行
 */
$server->handle();
?>
