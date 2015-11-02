<?php

/**********************************************************
* File         : server.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    : 
***********************************************************/

//define("_RELEASE_"  ,1) ; // 譛ｬ繧ｵ繝ｼ繝千畑
define("_TRACE_", 1);

define ("PGSQL_PHPTYPE", "pgsql");
define ("PGSQL_DBUSER" , "ncvcumgr");
define ("PGSQL_DBPASS" , "ncvcumgrpass");
define ("PGSQL_DBHOST" , "localhost");
define ("PGSQL_DBNAME" , "ncvcumgr");

define ("TRACELOG_PATH", "/var/www/phplib/syslog/");
define ("SQLLOG_PATH", "/var/www/phplib/sqllog/");
define ("BACKEND_PATH", "/var/www/phplib/backend/");
define ("PHP_CMD", "/usr/bin/php");

// データエクスポート用一時ディレクトリ
define ("EXPTEMP_PATH", "/var/www/phplib/exptemp/");
// 機器不明ユーザID
define ("APP_UNKNOWN_USER_ID", 18);

// メーリングリスト管理者ID
define ("MLIST_ADMIN_USER_ID", 1);

?>
