<?php
/**
 * SOAPレスポンスをutf-8で返すため
 */
mb_internal_encoding('utf-8');
mb_http_output('utf-8');

set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/users_detail_mgr.class.php");
class AuthenticateResult
{
	public $status;
	public $Message;
	public $login_id;
	public $login_passwd;
	public $validenddate;
}

$oMgr = new users_detail_mgr();


/**
 * 提供するサービス（関数の場合）
 */
function Authenticate($staffcode, $password)
{
	global $oMgr;

	// 戻り値
	$ret = new AuthenticateResult();

	if ($staffcode == "" || $password == "")
	{
		$ret->status = 9;
		$ret->Message = "パラメータが正しくありません";
		return $ret;
	}

	$staffcode = $oMgr->sqlItemChar($staffcode);
	$password = $oMgr->sqlItemChar($oMgr->passwordEncrypt($password));

	$sql = <<< EOF
SELECT
 UH.user_id,
 UM.login_id,
 UM.login_passwd,
 TO_CHAR(UH.validenddate, 'YYYY-MM-DD') AS validenddate
FROM
 user_his_tbl AS UH,
 user_mst AS UM
WHERE
 UH.user_id = UM.user_id AND
 UH.staffcode = $staffcode AND
 UH.password = $password AND
 UH.validstartdate <= now()::date AND
 UH.validenddate >= now()::date
EOF;

	$aryStaff = $oMgr->oDb->getRow($sql);

	if ($aryStaff['user_id'] == "")
	{
		$ret->status = 9;
		$ret->Message = "IDまたはパスワードが正しくないか有効期限が切れています";
		return $ret;
	}

	$ret->status = 100;
	$ret->Message = "";
	$ret->login_id = $aryStaff['login_id'];
	$ret->login_passwd = $oMgr->passwordDecrypt($aryStaff['login_passwd']);
	$ret->validenddate = $aryStaff['validenddate'];
	return $ret;

}

function ChangePassword($staffcode, $cur_password, $new_password)
{
	global $oMgr;

	// 戻り値
	$ret = new stdClass;

	if ($staffcode == "" || $cur_password == "" || $new_password == "")
	{
		$ret->status = 9;
		$ret->Message = "パラメータが正しくありません";
		return $ret;
	}

	if (!string::checkAlphanumWide($new_password, 4, 10))
	{
		$ret->status = 9;
		$ret->Message = "パスワードは半角英数字4～10文字で入力してください";
		return $ret;
	}


	$staffcode = $oMgr->sqlItemChar($staffcode);
	$password = $oMgr->sqlItemChar($oMgr->passwordEncrypt($cur_password));

	$sql = <<< EOF
SELECT
 user_id,
 list_no
FROM
 user_his_tbl
WHERE
 staffcode = $staffcode AND
 password = $password AND
 validstartdate <= now()::date AND
 validenddate >= now()::date
EOF;

	$aryStaff = $oMgr->oDb->getRow($sql);

	if ($aryStaff['user_id'] == "")
	{
		$ret->status = 9;
		$ret->Message = "IDまたはパスワードが正しくないか有効期限が切れています";
		return $ret;
	}

	//$res = $oMgr->reissuePassword($aryStaff['list_no'], $aryStaff['user_id'], $new_password);

	$res = 1;
	if ($res !== 1)
	{
		$ret->status = 9;
		$ret->Message = "パスワード変更に失敗しました。";
		return $ret;
	}

	$ret->status = 0;
	return $ret;

}

/*
$ary = array();
$ary['staffcode'] = '01234567';
$ary['password'] = '1yYSQrUh';

$ret = Authenticate($ary);
var_dump($ret);
exit;
*/

/**
 * SOAPサーバオブジェクトの作成
 */
$server = new SoapServer(null, array('uri' => 'http://10.1.2.17/soap/'));
//$server = new SoapServer(null, array('uri' => 'http://10.1.2.16/soap/'));

/**
 * サービスの追加
 */
$server->addFunction(array("Authenticate", "ChangePassword"));

/**
 * サービスを実行
 */
$server->handle();
?>
