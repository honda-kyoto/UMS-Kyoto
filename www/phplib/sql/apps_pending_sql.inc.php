<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/apps_regist_common_sql.inc.php");

$cmnSql['CANCEL_APP_HEAD_ENTRY'] = "UPDATE app_head_entry SET entry_status = {ENTRY_STATUS}, update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {0} AND entry_no = {1}";

?>