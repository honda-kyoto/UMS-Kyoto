<?php

/**********************************************************
* File         : server.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/

//define("_RELEASE_"  ,1) ;
define("_TRACE_", 1);

define ("PGSQL_PHPTYPE", "pgsql");
define ("PGSQL_DBUSER" , "umgradmin");
define ("PGSQL_DBPASS" , "umgradminpass");
define ("PGSQL_DBHOST" , "localhost");
define ("PGSQL_DBNAME" , "umgrdb");

define ("TRACELOG_PATH", "/var/www/phplib/syslog/");
define ("SQLLOG_PATH", "/var/www/phplib/sqllog/");
define ("BACKEND_PATH", "/var/www/phplib/backend/");
define ("PHP_CMD", "/usr/bin/php");

// 外部定義追加　20140612 start

// メールアカウント
define ("USER_MAIL_DOMAIN", "@kuhp.kyoto-u.ac.jp");
define ("MLIST_MAIL_DOMAIN", "@kuhp.kyoto-u.ac.jp");

// システム名称
define ("MAIN_SYSTEM_NAME", "kyoto");

// AD登録時のローカルドメイン
define ("AD_LOCAL_DOMAIN", "@kyoto.local");

// 外部定義追加　20140612 end

// データエクスポート用一時ディレクトリ
define ("EXPTEMP_PATH", "/var/www/phplib/exptemp/");

// 機器不明ユーザID
define ("APP_UNKNOWN_USER_ID", 2946);

// メーリングリスト管理者ID
define ("MLIST_ADMIN_USER_ID", 2091);

// AD
define ("LDAP_AD_HOST_1", "10.254.225.155");
define ("LDAP_AD_HOST_2", "10.254.225.155");
define ("LDAP_AD_PORT", 389);
define ("LDAP_AD_DN", "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local");

define ("LDAP_HOST_1", "10.254.225.155");
define ("LDAP_HOST_2", "10.254.225.155");
define ("LDAP_PORT", 389);
define ("LDAP_PORT_SSL", 636);
define ("LDAP_PASS", "Passw0rd");
//define ("LDAP_DN", "CN=ncvcadmin,CN=Users,DC=y-ncvc,DC=local");
define ("LDAP_DN", "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local");

define("LOGINID_DN", "OU=enable,OU=y-ncvc,DC=y-ncvc,DC=local");
define("VLAN400_DN", "CN=vlan400,OU=VLAN,OU=NetAuth,DC=y-ncvc,DC=local");

define("VLAN_DN", "CN=vlan###VLAN_NAME###,OU=VLAN,OU=NetAuth,DC=y-ncvc,DC=local");

define("WIRED_DN", "OU=enable,OU=wired,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");
define("MAC_WIRED_DN", "CN=mac_wired,OU=wired,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");

define("WLESS_DN", "OU=enable,OU=wireless,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");
define("MAC_WLESS_DN", "CN=mac_wireless,OU=wireless,OU=MAC_Address,OU=NetAuth,DC=y-ncvc,DC=local");

define("WLESS_ID_DN", "OU=enable,OU=WirelessID,OU=NetAuth,DC=y-ncvc,DC=local");
define("VLAN300_DN", "CN=vlan300,OU=VLAN,OU=NetAuth,DC=y-ncvc,DC=local");

define ("VPN_DN", "OU=VPN,OU=NetAuth,DC=y-ncvc,DC=local");
define ("VPN_GROUP_DN", "CN=###GROUP_NAME###,OU=VPN,OU=NetAuth,DC=y-ncvc,DC=local");

define ("PASSLOGIC_DN", "OU=passlogic,OU=NetAuth,DC=y-ncvc,DC=local");

define ("GUEST_DN", "CN=Wireless_Guest_Access,OU=WirelessID,OU=NetAuth,DC=y-ncvc,DC=local");

define ("VDI_DN", "CN=VDIT3G1,OU=Users,OU=VDI,DC=y-ncvc,DC=local");

define ("FTRANS_DN_USR", "CN=FileTransfer_Users,OU=Users,OU=VDI,DC=y-ncvc,DC=local");
define ("FTRANS_DN_ADM", "CN=FileTransfer_Admins,OU=Users,OU=VDI,DC=y-ncvc,DC=local");

define("LOGINID_DN_DISABLE", "OU=disable,OU=y-ncvc,DC=y-ncvc,DC=local");

// HIS
define ("HIS_CSV_DIR", "/var/www/phplib/hiscsv/");



//define ("MAIL_ACCOUNT_WSDL", "https://61.208.193.10/soap/wsdl/user_service.wsdl");
//define ("MAIL_ACCOUNT_WSDL", "https://mail01.ncvc.go.jp/soap/wsdl/user_service.wsdl");
//define ("MAILING_LIST_WSDL", "https://61.208.193.10/soap/wsdl/ml_service.wsdl");
//define ("MAILING_LIST_WSDL", "https://mail01.ncvc.go.jp/soap/wsdl/ml_service.wsdl");
//define ("MAIL_LOGIN_USER", "ncvc");
//define ("MAIL_LOGIN_PWD", "d67gfVdG");

// システムからのメール送信関連
//define ("SYSMAIL_FROM", "ncvc-nw@ml.ncvc.go.jp");
define ("SYSMAIL_SENDERNAME", "");
define ("SYSMAIL_CC", "");
define ("SYSMAIL_BCC", "");
define ("SYSMAIL_ENVELOP", "");
//define ("SYSMAIL_RETURN_PATH", "ncvc-nw@ml.ncvc.go.jp");
define ("STAFF_ID_LEN",8);
define ("ORIGINAL_NAME", "kyoto");
define ("CARD_KOJIN_DIR", "/var/www/phplib/card");

?>
