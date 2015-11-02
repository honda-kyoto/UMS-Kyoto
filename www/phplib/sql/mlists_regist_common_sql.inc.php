<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");


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