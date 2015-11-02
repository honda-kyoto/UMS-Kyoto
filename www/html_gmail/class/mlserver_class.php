<?php
class Mlserver{
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
			$res['detail'] = "[Error] MLサーバーへの接続に失敗しました。接続情報もしくはサーバー、ネットワークの状態を確認してください。";
		}else{
			$login = ssh2_auth_password($link, $this->login_user, $this->password);
			if(!$login){
				$res['result'] = false;
				$res['detail'] = "[Error] MLサーバーへのログインに失敗しました。ユーザー名、パスワードを確認してください。";
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
		$command_list[0] = array('command' => "sh /usr/local/bin/check_regist_ml.sh ".$user_name."@kuhp.kyoto-u.ac.jp ".PHP_EOL,
								'usleep' => 1000000);

		$res = $this->exec_shellscript($command_list);

		if(preg_match('/RESULT:This user is registered as a ([0-9a-zA-Z]+)\./', $res['detail'], $matches1)){
			$result['matche_pattern'] = 'Registered as "'.$matches1[1].'"';

		}elseif(preg_match('/RESULT:This user is not registered into any Mailling List\./', $res['detail'])){
			$result['matche_pattern'] = 'Not registered';

		}elseif(preg_match('/RESULT:This user is registered into some Mailling List\./', $res['detail'])){
			$result['matche_pattern'] = 'Not matching any patterns';
			preg_match_all('/(([a-z]+)\/(actives|members)):\s(.+)\./', $res['detail'], $matches2);
			for ($i=0; $i < count($matches2[1]); $i++) { 
				$result['detail'][$i]['ml_name'] = $matches2[2][$i];
				$result['detail'][$i]['am_list'] = $matches2[3][$i];
				$result['detail'][$i]['regist'] = $matches2[4][$i];
			}

		}else{
			$result['matche_pattern'] = 'Failed';
		}

		return $result;
	}


	public function registuser_on_ssh($user_info){
		// 送信シェルスクリプト配列(添え字の順で実行される)
		// $command_list[0] = array('command' => 'sudo bash'.PHP_EOL,	-> command：実行するスクリプト
		// 						'usleep' => 1);						-> usleep：実行後の待機時間(マイクロ秒)
		$command_list[0] = array('command' => "sh /usr/local/bin/regist_and_check_ml.sh ".$user_info['accountname']."@kuhp.kyoto-u.ac.jp ".$user_info['job_type_code'].PHP_EOL,
								'usleep' => 10000000);

		$res = $this->exec_shellscript($command_list);

		// 処理結果判定
		if(preg_match('/RESULT:Failed\./', $res['detail']) == 1){
			$res['result'] = false;
			$res['detail'] = '[Error] ユーザー名:'.$user_info['accountname'].' アナウンス、アラート、職種毎のML登録状況に不正があります。各MLへの登録状況を確認してください。';
		}elseif(preg_match('/RESULT:Success\./', $res['detail']) == 1){
			$res['result'] = true;
			$res['detail'] = '';
		}else{
			$res['result'] = false;
			$res['detail'] = '[Alert] ユーザー名: '.$user_info['accountname'].' ML登録処理結果を受け取れませんでした。各MLへの登録状況を確認してください。';
		}
		return $res;
	}


	// メーリングリスト配信停止
	public function liststop($user_name){
		$command_list[0] = array('command' => "sh /usr/local/bin/stop_ml.sh ".$user_name."@kuhp.kyoto-u.ac.jp ".PHP_EOL,
								'usleep' => 8000000);

		$res = $this->exec_shellscript($command_list);

		// 処理結果判定
		if(preg_match('/RESULT:Failed\./', $res['detail']) == 1){
			$res['result'] = false;
			$res['detail'] = '[Error] ユーザー名:'.$user_info['accountname'].' ML停止処理状況に不正があります。各MLへの登録状況を確認してください。';
		}elseif(preg_match('/RESULT:Success\./', $res['detail']) == 1){
			$res['result'] = true;
			$res['detail'] = '';
		}else{
			$res['result'] = false;
			$res['detail'] = '[Alert] ユーザー名: '.$user_info['accountname'].' ML停止処理結果を受け取れませんでした。各MLへの登録状況を確認してください。';
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