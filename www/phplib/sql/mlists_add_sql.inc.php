<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/mlists_regist_common_sql.inc.php");


$cmnSql['INSERT_MLIST_HEAD'] = "
INSERT INTO mlist_head_tbl (
mlist_id,
mlist_name,
mlist_acc,
sender_kbn,
mlist_kbn,
note,
usage,
make_id,
update_id
)
VALUES
(
{MLIST_ID},
{MLIST_NAME},
{MLIST_ACC},
{SENDER_KBN},
{MLIST_KBN},
{NOTE},
{USAGE},
{MAKE_ID},
{UPDATE_ID}
)
";


?>