<?php
class Googleapi{
	private $domain;
	private $email;
	private $password;

	function __construct($data){
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Gdata_Gapps');

		$this->domain 	= $data['domain'];
		$this->email 	= $data['email'];
		$this->password = $data['password'];
	}

	// ユーザー情報取得
	public function get_user_info($username){
		$client = Zend_Gdata_ClientLogin::getHttpClient($this->email, $this->password, Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
		$gdata = new Zend_Gdata_Gapps($client, $this->domain);
		$result = $gdata->retrieveUser($username);
		return $result;
	}

	// ユーザープロフィール更新
	// (次回ログイン時パスワード変更要求専用)
	public function update_user_info($username){
		$userEntry = $this->get_user_info($username);
		$userEntry->login->changePasswordAtNextLogin = true;

		$client = Zend_Gdata_ClientLogin::getHttpClient($this->email, $this->password, Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
		$gdata = new Zend_Gdata_Gapps($client, $this->domain);
		$gdata->updateUser($username, $userEntry);
	}


	// ユーザーアカウント停止
	public function stop_user_account($username){
		$client = Zend_Gdata_ClientLogin::getHttpClient($this->email, $this->password, Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
		$gdata = new Zend_Gdata_Gapps($client, $this->domain);
		$gdata->suspendUser($username);
	}


	// ユーザーアカウント再開
	public function reactive_user_account($username){
		$client = Zend_Gdata_ClientLogin::getHttpClient($this->email, $this->password, Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
		$gdata = new Zend_Gdata_Gapps($client, $this->domain);
		$gdata->restoreUser($username);
	}


	// ユーザーアカウント削除
	public function delete_user_account($username){
		$client = Zend_Gdata_ClientLogin::getHttpClient($this->email, $this->password, Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
		$gdata = new Zend_Gdata_Gapps($client, $this->domain);
		$gdata->deleteUser($username);
	}


	// ユーザーアカウント作成
	public function create_user_account($user_info){
		$password = $this->createPassword();

		$client = Zend_Gdata_ClientLogin::getHttpClient($this->email, $this->password, Zend_Gdata_Gapps::AUTH_SERVICE_NAME);
		$gdata = new Zend_Gdata_Gapps($client, $this->domain);
		$res[0] = $gdata->createUser($user_info['accountname'], $user_info['givenname'], $user_info['familyname'], $password, $passwordHashFunction=null, $quota=null);
		$res[1] = $password;
		return $res;
	}


	// ランダムパスワード生成
	private function createPassword($length = 8){
		// 判別しづらい文字を除外した使用文字配列生成
		$pwd_strings = array(
								"sletter"	=> array('a','b','c','d','e','f','g','h','i','j','k','m','n','p','q','r','s','t','u','w','x','y','z'),
								"cletter"	=> array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','W','X','Y','Z'),
								"number"	=> array('2','3','4','6','7','8','9'),
								"symbol"	=> array('%','&','<','>','*')
							);
								// "sletter"	=> range('a', 'z'),
								// "cletter"	=> range('A', 'Z'),
								// "number"	=> range('0', '9'),
								// "symbol"	=> array_merge(range('!', '/'), range(':', '?'), range('{', '~'))

		$pwd = array();
		while (count($pwd) < $length) {
			// 4種類必ず入れる
			if (count($pwd) < 4) {
				$key = key($pwd_strings);
				next($pwd_strings);
			} else {
				// 後はランダムに取得
				$key = array_rand($pwd_strings);
			}
			$pwd[] = $pwd_strings[$key][array_rand($pwd_strings[$key])];
		}
		// 生成したパスワードの順番をランダムに並び替え
		shuffle($pwd);
		return implode($pwd);
	}
}