<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/mlists_regist_common_sql.inc.php");

$cmnSql['REJECT_MLIST_HEAD_ENTRY'] = "UPDATE mlist_head_entry SET entry_status = {ENTRY_STATUS}, agree_time = now(), agree_id = {UPDATE_ID}, agree_note = {AGREE_NOTE}, update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {0} AND entry_no = {1}";
$cmnSql['AGREE_MLIST_HEAD_ENTRY'] = "UPDATE mlist_head_entry SET entry_status = {ENTRY_STATUS}, update_time = now(), update_id = {UPDATE_ID}, agree_time = now(), agree_id = {UPDATE_ID} WHERE mlist_id = {0} AND entry_no = {1}";

$cmnSql['INSERT_MLIST_ORG_DATA'] = "
INSERT INTO mlist_head_tbl (
mlist_id,
mlist_name,
mlist_acc,
sender_kbn,
mlist_kbn,
usage,
note,
make_id,
update_id
)
SELECT
    mlist_id,
    mlist_name,
    mlist_acc,
    sender_kbn,
    mlist_kbn,
    usage,
    note,
    {MAKE_ID} AS make_id,
    {UPDATE_ID} AS update_id
FROM
    mlist_head_entry
WHERE
    mlist_id = {0} AND
    entry_no = {1}
";

$cmnSql['INSERT_MLIST_ORG_ADMIN'] = "
INSERT INTO mlist_admin_list (
mlist_id,
list_no,
user_id,
make_id,
update_id
)
SELECT
    mlist_id,
    list_no,
    user_id,
    {MAKE_ID} AS make_id,
    {UPDATE_ID} AS update_id
FROM
    mlist_admin_entry
WHERE
    mlist_id = {0} AND
    entry_no = {1}
";

$cmnSql['UPDATE_MLIST_ORG_DATA'] = "
UPDATE
    mlist_head_tbl
SET
    mlist_name = (SELECT mlist_name FROM mlist_head_entry WHERE mlist_id = {0} AND entry_no = {1}),
    mlist_acc = (SELECT mlist_acc FROM mlist_head_entry WHERE mlist_id = {0} AND entry_no = {1}),
    sender_kbn = (SELECT sender_kbn FROM mlist_head_entry WHERE mlist_id = {0} AND entry_no = {1}),
    mlist_kbn = (SELECT mlist_kbn FROM mlist_head_entry WHERE mlist_id = {0} AND entry_no = {1}),
    usage = (SELECT usage FROM mlist_head_entry WHERE mlist_id = {0} AND entry_no = {1}),
    note = (SELECT note FROM mlist_head_entry WHERE mlist_id = {0} AND entry_no = {1}),
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    mlist_id = {0}
";

$cmnSql['EXISTS_MLIST_ADMIN_DATA'] = "SELECT list_no FROM mlist_admin_list WHERE mlist_id = {MLIST_ID} AND list_no = {LIST_NO}";

$cmnSql['UPDATE_MLIST_ADMIN_DATA'] = "
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

$cmnSql['INSERT_MLIST_ADMIN_DATA'] = "
INSERT INTO mlist_admin_list (
mlist_id,
list_no,
user_id,
make_id,
update_id
)
VALUES
(
{MLIST_ID},
{LIST_NO},
{USER_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['DELETE_MLIST_ADMIN_DATA'] = "UPDATE mlist_admin_list SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {MLIST_ID} AND list_no = {LIST_NO}";

$cmnSql['DELETE_MLIST_ORG_DATA'] = "UPDATE mlist_head_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {0}";
$cmnSql['DELETE_MLIST_ORG_ADMIN'] = "UPDATE mlist_admin_list SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {0}";

?>