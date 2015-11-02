<?php
/**
 * SOAPレスポンスをutf-8で返すため
 */
mb_internal_encoding('utf-8');
mb_http_output('utf-8');

/**
 * 提供するサービス（関数の場合）
 */
function sendUserData($x, $y)
{
	$dir = "/var/www/phplib/hiscsv";
	$aryFile = array();
	if ($handle = opendir($dir))
	{
		$key = 0;
		while (false !== ($entry = readdir($handle)))
		{
			if ($entry != "." && $entry != "..")
			{
				$aryFile[$key]['file_name'] = $entry;
				$aryFile[$key]['file_data'] = file_get_contents($dir."/".$entry);

				unlink($dir."/".$entry);

				$key++;
			}
		}
		closedir($handle);
	}

	return $aryFile;
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
