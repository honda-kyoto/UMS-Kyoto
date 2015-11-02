<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/mlists_regist_common_sql.inc.php");

$cmnSql['UPDATE_MLIST_HEAD'] = "
UPDATE
    mlist_head_tbl
SET
    mlist_name = {MLIST_NAME},
    mlist_acc = {MLIST_ACC},
    sender_kbn = {SENDER_KBN},
    note = {NOTE},
    usage = {USAGE},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    mlist_id = {MLIST_ID}
";

$cmnSql['DELETE_MLIST_ADMIN_DATA'] = "
UPDATE
    mlist_admin_list
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    mlist_id = {MLIST_ID}
";

$cmnSql['EXISTS_MLIST_ADMIN'] = "
SELECT
    list_no
FROM
    mlist_admin_list
WHERE
    mlist_id = {MLIST_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['UPDATE_MLIST_ADMIN'] = "
UPDATE
    mlist_admin_list
SET
    user_id = {USER_ID},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    mlist_id = {MLIST_ID} AND
    list_no = {LIST_NO}
";

?>