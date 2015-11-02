<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/mlists_regist_common_sql.inc.php");

$cmnSql['ENTRY_LOCK'] = "SELECT * FROM mlist_head_entry WHERE mlist_id = {0} FOR UPDATE";
$cmnSql['GET_ENTRY_NO'] = "SELECT COALESCE(MAX(entry_no), 0) + 1 FROM mlist_head_entry WHERE mlist_id = {0}";

$cmnSql['INSERT_MLIST_HEAD_ENTRY'] = "
INSERT INTO mlist_head_entry (
mlist_id,
entry_no,
entry_status,
entry_kbn,
mlist_name,
mlist_acc,
sender_kbn,
mlist_kbn,
note,
usage,
make_id,
update_id,
entry_time,
entry_id
)
VALUES
(
{MLIST_ID},
{ENTRY_NO},
{ENTRY_STATUS},
{ENTRY_KBN},
{MLIST_NAME},
{MLIST_ACC},
{SENDER_KBN},
{MLIST_KBN},
{NOTE},
{USAGE},
{MAKE_ID},
{UPDATE_ID},
now(),
{ENTRY_ID}
)
";

$cmnSql['INSERT_MLIST_ADMIN_ENTRY'] = "
INSERT INTO mlist_admin_entry (
mlist_id,
entry_no,
list_no,
user_id,
make_id,
update_id,
entry_time,
entry_id
)
VALUES
(
{MLIST_ID},
{ENTRY_NO},
{LIST_NO},
{USER_ID},
{MAKE_ID},
{UPDATE_ID},
now(),
{ENTRY_ID}
)
";


?>