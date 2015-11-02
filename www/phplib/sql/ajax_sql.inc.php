<?php
/**********************************************************
* File         : ajax_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.29
* Last Update  : 2013.01.29
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['CHECK_EXISTS_LOGIN_ID'] = "SELECT user_id FROM user_mst WHERE login_id = '{0}' {COND}";

$cmnSql['CHECK_EXISTS_PBNO'] = "SELECT user_id FROM user_mst WHERE pbno = '{0}' {COND}";

?>