<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/apps_regist_common_sql.inc.php");

$cmnSql['UPDATE_APP_USE_SBC'] = "
UPDATE
    app_head_tbl
SET
    use_sbc = {USE_SBC},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    app_id = {APP_ID}
";

?>