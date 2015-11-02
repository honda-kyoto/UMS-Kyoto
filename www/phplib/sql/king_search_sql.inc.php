<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : kazuyoshi shibuta
* Date         : 2015.06.04
* Last Update  : 2015.06.04
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(king_18) AS list_count
FROM king_mst WHERE king_18 NOT IN ('PW(必須)','') 
{COND}
";

$cmnSql['GETLIST'] = "
SELECT
    king_1 as king_id,
    king_5 as king_name,
    king_6 as king_name_kana
FROM
    king_mst
WHERE
    king_18 NOT IN ('PW(必須)','') 
{COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";

$cmnSql['DELETE_MLIST_HEAD'] = "UPDATE mlist_head_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {0}";
$cmnSql['DELETE_MLIST_ADMIN'] = "UPDATE mlist_admin_list SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {0}";
$cmnSql['DELETE_MLIST_MEMBERS'] = "UPDATE mlist_members_list SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {0}";


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

$cmnSql['GET_MLIST_DATA'] = "
SELECT
    mlist_name,
    mlist_acc,
    sender_kbn,
    mlist_kbn,
    usage,
    note
    {COL}
FROM
    mlist_head_{TYPE}
WHERE
    del_flg = '0' AND
    mlist_id = {0}
    {COND}
";

$cmnSql['GET_ADMIN_LIST'] = "
SELECT
    UM.kanjisei || '　' || UM.kanjimei AS admin_name
FROM
    mlist_admin_{TYPE} AS MA,
    user_mst AS UM
WHERE
    MA.user_id = UM.user_id AND
    UM.start_date <= now()::date AND
    COALESCE(UM.end_date, now()::date) >= now()::date AND
    MA.del_flg = '0' AND
    MA.mlist_id = {0}
    {COND}
ORDER BY
    MA.list_no
";

$cmnSql['GET_ADMIN_ID'] = "
SELECT
    list_no,
    user_id
FROM
    mlist_admin_{TYPE}
WHERE
    mlist_id = {0} AND
    del_flg = '0'
    {COND}
ORDER BY
    list_no
";


$cmnSql['GET_ADMIN_NAME'] = "SELECT kanjisei || '　' || kanjimei FROM user_mst WHERE user_id = {0}";

$cmnSql['EXISTS_MLIST_NAME'] = "SELECT mlist_id FROM mlist_head_tbl WHERE mlist_name = '{0}' AND del_flg = '0' {COND}";


$cmnSql['INSERT_MLIST_ADMIN'] = "
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

$cmnSql['GET_LAST_AGREE_DATA'] = "
SELECT
        MHE.mlist_id,
        MHE.entry_no,
        MHE.entry_status,
        MHE.entry_kbn,
        TO_CHAR(MHE.entry_time, 'YYYY/MM/DD HH24:MI:SS') AS entry_time,
        MHE.entry_id,
        TO_CHAR(MHE.agree_time, 'YYYY/MM/DD HH24:MI:SS') AS agree_time,
        MHE.agree_id
    FROM
        mlist_head_entry MHE
    WHERE
        del_flg = '0' AND
        mlist_id = {0} AND
        entry_status = {ENTRY_STATUS_AGREE} AND
        EXISTS (SELECT
                    *
                FROM
                    (SELECT
                        MAX(entry_no) AS entry_no
                    FROM
                        mlist_head_entry
                    WHERE
                        del_flg = '0' AND
                        entry_status = {ENTRY_STATUS_AGREE}
                    GROUP BY
                        mlist_id
                    ) AS LST
                WHERE
                    MHE.mlist_id = mlist_id AND
                    MHE.entry_no = entry_no
                )
";

?>