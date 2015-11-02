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
    COUNT(MLT.mlist_id) AS list_count
FROM
    (
    SELECT
        MHE.mlist_id,
        MHE.entry_no,
        MHE.entry_status,
        MHE.entry_kbn,
        MHE.mlist_kbn,
        MHE.mlist_name,
        MHE.mlist_acc,
        MHE.update_time
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
    UNION
    SELECT
        MHE.mlist_id,
        MHE.entry_no,
        MHE.entry_status,
        MHE.entry_kbn,
        MHE.mlist_kbn,
        MHE.mlist_name,
        MHE.mlist_acc,
        MHE.update_time
    FROM
        mlist_head_entry MHE
    WHERE
        del_flg = '0' AND
        entry_status = {ENTRY_STATUS_REJECT} AND
        NOT EXISTS (SELECT
                        *
                    FROM
                        mlist_head_entry AS MHET
                    WHERE
                        MHE.mlist_id = mlist_id AND
                        del_flg = '0' AND
                        entry_status = {ENTRY_STATUS_ENTRY} AND
                        EXISTS (SELECT
                                    *
                                FROM
                                    (SELECT
                                        mlist_id,
                                        MAX(entry_no) AS entry_no
                                    FROM
                                        mlist_head_entry
                                    WHERE
                                        del_flg = '0'
                                    GROUP BY
                                        mlist_id
                                    ) AS LST
                                WHERE
                                    MHET.mlist_id = mlist_id AND
                                    MHET.entry_no = entry_no
                                )
                    ) AND
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
    UNION
    SELECT
        MHT.mlist_id,
        NULL::integer AS entry_no,
        NULL::varchar AS entry_status,
        NULL::varchar AS entry_kbn,
        MHT.mlist_kbn,
        MHT.mlist_name,
        MHT.mlist_acc,
        MHT.update_time
    FROM
        mlist_head_tbl AS MHT
    WHERE
        del_flg = '0' AND
        NOT EXISTS (SELECT
                        *
                    FROM
                        mlist_head_entry AS MHE
                    WHERE
                        MHT.mlist_id = mlist_id AND
                        del_flg = '0' AND
                        entry_status IN ({ENTRY_STATUS_ENTRY}, {ENTRY_STATUS_REJECT}) AND
                        EXISTS (SELECT
                                    *
                                FROM
                                    (SELECT
                                        mlist_id,
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
                    )
) AS MLT
{COND}
";

$cmnSql['GETLIST'] = "
SELECT
    MLT.mlist_id,
    MLT.entry_no,
    MLT.entry_status,
    MLT.entry_kbn,
    MLT.mlist_kbn,
    MLT.mlist_name,
    MLT.mlist_acc
FROM
    (
    SELECT
        MHE.mlist_id,
        MHE.entry_no,
        MHE.entry_status,
        MHE.entry_kbn,
        MHE.mlist_kbn,
        MHE.mlist_name,
        MHE.mlist_acc,
        MHE.update_time,
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
    UNION
    SELECT
        MHE.mlist_id,
        MHE.entry_no,
        MHE.entry_status,
        MHE.entry_kbn,
        MHE.mlist_kbn,
        MHE.mlist_name,
        MHE.mlist_acc,
        MHE.update_time,
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
        entry_status = {ENTRY_STATUS_REJECT} AND
        NOT EXISTS (SELECT
                        *
                    FROM
                        mlist_head_entry AS MHET
                    WHERE
                        MHE.mlist_id = mlist_id AND
                        del_flg = '0' AND
                        entry_status = {ENTRY_STATUS_ENTRY} AND
                        EXISTS (SELECT
                                    *
                                FROM
                                    (SELECT
                                        mlist_id,
                                        MAX(entry_no) AS entry_no
                                    FROM
                                        mlist_head_entry
                                    WHERE
                                        del_flg = '0'
                                    GROUP BY
                                        mlist_id
                                    ) AS LST
                                WHERE
                                    MHET.mlist_id = mlist_id AND
                                    MHET.entry_no = entry_no
                                )
                    ) AND
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
    UNION
    SELECT
        MHT.mlist_id,
        NULL::integer AS entry_no,
        NULL::varchar AS entry_status,
        NULL::varchar AS entry_kbn,
        MHT.mlist_kbn,
        MHT.mlist_name,
        MHT.mlist_acc,
        MHT.update_time,
        ARRAY(
            SELECT
                UM.kanjisei || '　' || UM.kanjimei AS admin_name
            FROM
                mlist_admin_list AS MA,
                user_mst AS UM
            WHERE
                MA.user_id = UM.user_id AND
                UM.start_date <= now()::date AND
                COALESCE(UM.end_date, now()::date) >= now()::date AND
                MA.del_flg = '0' AND
                MA.mlist_id = MHT.mlist_id
            ORDER BY
                MA.list_no
        ) AS sort_admin_name
    FROM
        mlist_head_tbl AS MHT
    WHERE
        del_flg = '0' AND
        NOT EXISTS (SELECT
                        *
                    FROM
                        mlist_head_entry AS MHE
                    WHERE
                        MHT.mlist_id = mlist_id AND
                        del_flg = '0' AND
                        entry_status IN ({ENTRY_STATUS_ENTRY}, {ENTRY_STATUS_REJECT}) AND
                        EXISTS (SELECT
                                    *
                                FROM
                                    (SELECT
                                        mlist_id,
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
                    )
) AS MLT
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

?>