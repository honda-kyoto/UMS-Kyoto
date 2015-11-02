<?php

//設定
//$ldap_host = "ldaps://y-ncvc.local"; //LDAPサーバのホスト
$ldap_host = "10.254.225.155";         //LDAPサーバのホスト（テスト環境）

$ldap_port = 389; //ポート
//$ldap_port = 636;   //ポートSSL用

$ldap_pass = "Passw0rd"; // パスワード

//接続開始
//$ldap_DN = "OU=enable, OU=y-ncvc, DC=y-ncvc, DC=local";

// 更新権限のあるユーザーを指定（例:Administrator）
$ldap_DN = "CN=Administrator,CN=Users,DC=y-ncvc,DC=local";
$ldap_conn = ldap_connect($ldap_host, $ldap_port);

if($ldap_conn){

    echo "<p>接続成功</p>";

    $ldap_bind  = ldap_bind($ldap_conn, $ldap_DN, $ldap_pass);
    //echo "ldap_bind : " . $ldap_bind;

    if($ldap_bind){

        echo "<p>バインド成功</p>";

        // アカウント検索
	//$uid = "hoge";
	$uid = "binder";
        $base_dn = "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local";
        $target = "CN=$uid";
        $filter = array("cn", "sAMAccountName");
        
        $result = ldap_search($ldap_conn, $base_dn, $target, $filter);
        
        $data = ldap_get_entries($ldap_conn, $result);
        //print_r($data);
        
        if($data['count']==1){
        	echo "$uid はおるで";
        } else {
        	echo "$uid はおらんで";
        }
        
    }else{
        echo "<p>バインド失敗</p>";
    }
    ldap_close($ldap_conn);
}else{
    echo "<p>接続失敗</p>";
}

?>
