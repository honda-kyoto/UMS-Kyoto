<?php

//設定
$ldap_host = "ldaps://10.1.2.11/"; //LDAPサーバのホスト
$ldap_pass = "117#ncvc"; // パスワード
$new_user = "dantest2";
$password = "Passw0rd";

//接続開始
$ldap_DN = "CN=ncvcadmin,CN=Users,DC=ncvc,DC=local";
$ldap_conn = ldap_connect($ldap_host);

if($ldap_conn){
    echo "<p>接続成功</p>";

    $ldap_bind  = ldap_bind($ldap_conn, $ldap_DN, $ldap_pass);
   
    if($ldap_bind){
        echo "<p>バインド成功</p>";
        
    }else{
        echo "<p>バインド失敗</p>";
    }
    ldap_close($ldap_conn);
}else{
    echo "<p>接続失敗</p>";
}

?>
