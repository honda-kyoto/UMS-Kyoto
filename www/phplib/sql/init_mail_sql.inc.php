<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['SET_INIT_MAIL_ACC'] = "
UPDATE
    user_mst
SET
    mail_acc = {MAIL_ACC},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";


?>