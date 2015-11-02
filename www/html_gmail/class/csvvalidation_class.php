<?php
class Csvvalidation{
	function __construct(){
	}

	// CSVフォーマット異常検知
	// 		$user_info[$i][0],	// アカウント名
	// 		$user_info[$i][1],	// 職員番号
	// 		$user_info[$i][2],	// ﾌﾙﾈｰﾑ(漢字)
	// 		$user_info[$i][3],	// 姓(ローマ字)
	// 		$user_info[$i][4],	// 名(ローマ字)
	// 		$user_info[$i][5],	// ﾌﾙﾈｰﾑ(ｶﾀｶﾅ)
	// 		$user_info[$i][6],	// 所属部署(漢字)
	// 		$user_info[$i][7],	// 所属部署(ローマ字)
	// 		$user_info[$i][8],	// 連絡先電話番号
	// 		$user_info[$i][9],	// 職種
	// 		$user_info[$i][10]	// 役職(備考)
	public function validation($user_info){
		$res['column'] = false;
		$res['require'] = false;
		$res['acc_name'] = false;
		$res['sei_rome'] = false;
		$res['mei_rome'] = false;

		// カラム数チェック
		if(count($user_info) == 11){
			$res['column'] = true;

			// 必須入力個所チェック
			if($user_info[0] != '' and $user_info[3] != '' and $user_info[4] != '' and $user_info[9] != ''){
				$res['require'] = true;

				// アカウント名：30文字以内、半角英数[a-z0-9]、記号[.-_]のみ使用可能
				if(preg_match('/^[a-z0-9\.\-_]{1,30}$/i', $user_info[0]) ){
					$res['acc_name'] = true;
				}
				// 姓(ローマ字): 半角英数60文字まで
				if(preg_match('/^[a-zA-Z0-9\.\-_\/,]{1,60}$/', $user_info[3]) ){
					$res['sei_rome'] = true;
				}
				// 名(ローマ字): 半角英数60文字まで
				if(preg_match('/^[a-zA-Z0-9\.\-_\/,]{1,60}$/', $user_info[4]) ){
					$res['mei_rome'] = true;
				}
			}
		}
		return $res;
	}
}
