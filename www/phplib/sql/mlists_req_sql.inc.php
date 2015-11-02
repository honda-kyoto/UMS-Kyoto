<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/mlists_regist_common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(MHE.mlist_id) AS list_count
FROM
    mlist_head_entry MHE
WHERE
    del_flg = '0' AND
    entry_status = {ENTRY_STATUS_ENTRY} AND
    EXISTS (SELECT
                *
            FROM
                (SELECT
                    MAX(entry_no) AS entry_no
                FROM
                    mlist_head_entry
                WHERE
                    del_flg = '0'
                GROUP BY
                    mlist_id
                ) AS LST
            WHERE
                MHE.mlist_id = mlist_id AND
                MHE.entry_no = entry_no
            )
    {COND}
";

$cmnSql['GETLIST'] = "
SELECT
    MHE.mlist_id,
    MHE.entry_no,
    MHE.entry_status,
    MHE.entry_kbn,
    MHE.mlist_kbn,
    MHE.mlist_name,
    MHE.mlist_acc,
    ARRAY(
        SELECT
            UM.kanjisei || '　' || UM.kanjimei AS admin_name
        FROM
            mlist_admin_entry AS MA,
            user_mst AS UM
        WHERE
            MA.user_id = UM.user_id AND
            UM.start_date <= now()::date AND
            COALESCE(UM.end_date, now()::date) >= now()::date AND
            MA.del_flg = '0' AND
            MA.entry_no = MHE.entry_no AND
            MA.mlist_id = MHE.mlist_id
        ORDER BY
            MA.list_no
    ) AS sort_admin_name
FROM
    mlist_head_entry MHE
WHERE
    del_flg = '0' AND
    entry_status = {ENTRY_STATUS_ENTRY} AND
    EXISTS (SELECT
                *
            FROM
                (SELECT
                    MAX(entry_no) AS entry_no
                FROM
                    mlist_head_entry
                WHERE
                    del_flg = '0'
                GROUP BY
                    mlist_id
                ) AS LST
            WHERE
                MHE.mlist_id = mlist_id AND
                MHE.entry_no = entry_no
            )
    {COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";

?>