<?php

require_once('./Google/Auth/AssertionCredentials.php');
require_once('./Google/Service/Directory.php');
require_once('./Google/Client.php');

class Googleapi_new{
	private $domain;
	private $authEmail;
	private $apiAdminMail;
	private $p12Key;
	private $scopes;
	private $credential;


	function __construct($data){

		$this->domain 		= $data['domain'];
		$this->authEmail 	= $data['authEmail'];
		$this->apiAdminMail = $data['apiAdminMail'];
		$this->p12Key 		= $data['p12Key'];
		$this->scopes 		= $data['scopes'];
		
		$this->credential = new Google_Auth_AssertionCredentials(
			$this->authEmail,
			$this->scopes,
			file_get_contents($this->p12Key),
			'notasecret',
			'http://oauth.net/grant_type/jwt/1.0/bearer',
			$this->apiAdminMail
		);
		
	}

	// ユーザー情報取得
	public function get_user_info($username){
		$client = new Google_Client();
		$client->setAssertionCredentials($this->credential);
		$service = new Google_Service_Directory($client);
		$result = $service->users->get($username."@".$this->domain);
		return $result;
	}

	// ユーザーアカウント作成
	public function create_user_account($user_info){
		$password = $this->createPassword();

		$client = new Google_Client();
		$client->setAssertionCredentials($this->credential);
		$service = new Google_Service_Directory($client);
		
		$user = new Google_Service_Directory_User();
		$name = new Google_Service_Directory_UserName();
		$name->setFamilyName($user_info['familyname']);
		$name->setGivenName($user_info['givenname']);

		$user->setPassword($password);
		$user->setPrimaryEmail($user_info['accountname']."@".$this->domain);
		$user->setName($name);
		$user->setChangePasswordAtNextLogin(true);

		$res[0] = $service->users->insert($user);
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