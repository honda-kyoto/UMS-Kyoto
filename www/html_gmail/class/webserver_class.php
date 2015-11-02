<?php
class Webserver{
	function __construct($serverinfo){
		$this->host_name	= $serverinfo['host_name'];
		$this->host_ip		= $serverinfo['host_ip'];
		$this->ssh_port		= $serverinfo['ssh_port'];
		$this->login_user	= $serverinfo['login_user'];
		$this->password		= $serverinfo['password'];
	}

	public function connect_server(){
		$link = ssh2_connect($this->host_ip, $this->ssh_port);
		if(!$link){
			$res['result'] = false;
			$res['detail'] = "[Error] Webサーバーへの接続に失敗しました。接続情報もしくはサーバー、ネットワークの状態を確認してください。";
		}else{
			$login = ssh2_auth_password($link, $this->login_user, $this->password);
			if(!$login){
				$res['result'] = false;
				$res['detail'] = "[Error] Webサーバーへのログインに失敗しました。ユーザー名、パスワードを確認してください。";
			}else{
				$res['result'] = true;
				$res['detail'] = $link;
			}
		}
		return $res;
	}


	public function checkuser_on_ssh($user_name){
		// 送信シェルスクリプト配列(添え字の順で実行される)
		// $command_list[0] = array('command' => 'sudo bash'.PHP_EOL,	-> command：実行するスクリプト
		// 						'usleep' => 1);						-> usleep：実行後の待機時間(マイクロ秒)
		$command_list[0] = array('command' => "finger \"".$user_name."\"".PHP_EOL,
								'usleep' => 500000);

		$res = $this->exec_shellscript($command_list);


		if(preg_match('/Login: '.$user_name.'/', $res['detail']) == 1){
			// exist
			$result = 'Registered';
		}elseif(preg_match('/'.$user_name.': no such user\./', $res['detail']) == 1){
			// not exist
			$result = 'Not registered';
		}else{
			// cannot exec
			$result = 'Failed';
		}

		return $result;
	}


	public function registuser_on_ssh($user_info){
		// 送信シェルスクリプト配列(添え字の順で実行される)
		// $command_list[0] = array('command' => 'sudo bash'.PHP_EOL,	-> command：実行するスクリプト
		// 						'usleep' => 1);						-> usleep：実行後の待機時間(マイクロ秒)
		$command_list[0] = array('command' => "sudo /usr/local/bin/adduser_chroot3.pl \"".$user_info['accountname']."\" \"".$user_info['password']."\" \"".$user_info['familyname']."\" \"".$user_info['givenname']."\" \"".$user_info['officename_roman']."\" \"".$user_info['officetell_num']."\" \"".$user_info['job_type']."\"".PHP_EOL,
								'usleep' => 7000000);

		$res = $this->exec_shellscript($command_list);


		// 処理結果判定
		if(preg_match('/RESULT:Failed\./', $res['detail']) == 1){
			$res['result'] = false;
			$res['detail'] = '[Error] ユーザー名: '.$user_info['accountname'].' ユーザーWeb領域の登録に失敗しました。ユーザー登録およびユーザーディレクトリが存在するか確認してください。';

		}elseif(preg_match('/RESULT:Exists\./', $res['detail']) == 1){
			$res['result'] = false;
			$res['detail'] = '[Error] ユーザー名: '.$user_info['accountname'].' ユーザーWeb領域に登録済みのユーザーです。';
			
		}elseif(preg_match('/RESULT:Success\./', $res['detail']) == 1){
			$res['result'] = true;
			$res['detail'] = '';
		}else{
			$res['result'] = false;
			$res['detail'] = '[Alert] ユーザー名: '.$user_info['accountname'].' ユーザーWeb領域の登録処理結果を確認できませんでした。ユーザー登録およびユーザーディレクトリが存在するか確認してください。';
		}
		return $res;
	}


	private function exec_shellscript($command_list){
		$res = $this->connect_server();
		if($res['result']){
			$link = $res['detail'];
		}else{
			return $res;
		}

		// シェルスクリプト配列を順次実行
		$stream = ssh2_shell($link, "xterm", null, 80, 24, SSH2_TERM_UNIT_CHARS);
		// usleep(1000000);
		for($i = 0; $i < count($command_list); $i++) {
			fwrite($stream, $command_list[$i]['command']);
			usleep($command_list[$i]['usleep']);
			// 最終添え字まで来たら画面の表示を返す
			if($i == (count($command_list)-1)){
				$screen = "";
				while($line = fgets($stream)){
					flush();
					$screen .= $line.PHP_EOL;
				}
				fclose($stream);
			}
		}
		$res['detail'] = $screen;
		return $res;
	}


}