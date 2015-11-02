<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/apps_regist_common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(APP.app_id) AS list_count
FROM
    (SELECT
        AHE.app_id,
        AHE.entry_no,
        AHE.entry_status,
        AHE.entry_kbn,
        AHE.app_type_id,
        ATM.app_type_name,
        AHE.app_name,
        AHE.vlan_room_id,
        AHE.vlan_id,
        AHE.mac_addr,
        AHE.ip_addr,
        AHE.wire_kbn,
        AHE.ip_kbn,
        AHE.update_time,
        NULL::integer AS app_user_id,
        AHE.entry_id  AS entry_id
    FROM
        app_head_entry AS AHE,
        app_type_mst AS ATM
    WHERE
        AHE.app_type_id = ATM.app_type_id AND
        AHE.entry_status = {ENTRY_STATUS_ENTRY} AND
        AHE.del_flg='0' AND
        ATM.del_flg='0' AND
        EXISTS (
            SELECT
                *
            FROM
                (
                SELECT
                    app_id,
                    MAX(entry_no) AS entry_no
                FROM
                    app_head_entry
                WHERE
                    del_flg = '0'
                GROUP BY
                    app_id
                ) AS LST
            WHERE
                AHE.app_id = app_id AND
                AHE.entry_no = entry_no
        )
    UNION
    SELECT
        AHE.app_id,
        AHE.entry_no,
        AHE.entry_status,
        AHE.entry_kbn,
        AHE.app_type_id,
        ATM.app_type_name,
        AHE.app_name,
        AHE.vlan_room_id,
        AHE.vlan_id,
        AHE.mac_addr,
        AHE.ip_addr,
        AHE.wire_kbn,
        AHE.ip_kbn,
        AHE.update_time,
        NULL::integer AS app_user_id,
        AHE.entry_id  AS entry_id
    FROM
        app_head_entry AS AHE,
        app_type_mst AS ATM
    WHERE
        AHE.app_type_id = ATM.app_type_id AND
        AHE.entry_status = {ENTRY_STATUS_REJECT} AND
        AHE.del_flg='0' AND
        ATM.del_flg='0' AND
        NOT EXISTS (
            SELECT
                *
            FROM
                app_head_entry AS AHET
            WHERE
                AHE.app_id = app_id AND
                del_flg = '0' AND
                entry_status = {ENTRY_STATUS_ENTRY} AND
                EXISTS (
                    SELECT
                        *
                    FROM
                        (
                        SELECT
                            app_id,
                            MAX(entry_no) AS entry_no
                        FROM
                            app_head_entry
                        WHERE
                            del_flg = '0'
                        GROUP BY
                            app_id
                        ) AS LST
                    WHERE
                        AHET.app_id = app_id AND
                        AHET.entry_no = entry_no
                )
            ) AND
        EXISTS (
            SELECT
                *
            FROM
                (
                SELECT
                    app_id,
                    MAX(entry_no) AS entry_no
                FROM
                    app_head_entry
                WHERE
                    del_flg = '0'
                GROUP BY
                    app_id
                ) AS LST
            WHERE
                AHE.app_id = app_id AND
                AHE.entry_no = entry_no
        )
    UNION
    SELECT
        AHT.app_id,
        NULL::integer AS entry_no,
        NULL::varchar AS entry_status,
        NULL::varchar AS entry_kbn,
        AHT.app_type_id,
        ATM.app_type_name,
        AHT.app_name,
        AHT.vlan_room_id,
        AHT.vlan_id,
        AHT.mac_addr,
        AHT.ip_addr,
        AHT.wire_kbn,
        AHT.ip_kbn,
        AHT.update_time,
        AHT.app_user_id,
        NULL::integer AS entry_id
    FROM
        app_head_tbl AS AHT,
        app_type_mst AS ATM
    WHERE
        AHT.app_type_id = ATM.app_type_id AND
        AHT.del_flg='0' AND
        ATM.del_flg='0' AND
        NOT EXISTS (
            SELECT
                *
            FROM
                app_head_entry AS AHE
            WHERE
                AHT.app_id = app_id AND
                del_flg = '0' AND
                entry_status IN ({ENTRY_STATUS_ENTRY}, {ENTRY_STATUS_REJECT}) AND
                EXISTS (
                    SELECT
                        *
                    FROM
                        (
                        SELECT
                            app_id,
                            MAX(entry_no) AS entry_no
                        FROM
                            app_head_entry
                        WHERE
                            del_flg = '0'
                        GROUP BY
                            app_id
                        ) AS LST
                    WHERE
                        AHE.app_id = app_id AND
                        AHE.entry_no = entry_no
                )
            )
    ) AS APP
{COND}
";

$cmnSql['GETLIST'] = "
SELECT
    app_id,
    entry_no,
    entry_status,
    entry_kbn,
    app_type_id,
    app_type_name,
    app_name,
    vlan_room_id,
    vlan_id,
    mac_addr,
    ip_addr,
    wire_kbn,
    ip_kbn,
    app_user_name,
    app_user_id,
    entry_id
FROM
    (SELECT
        AHE.app_id,
        AHE.entry_no,
        AHE.entry_status,
        AHE.entry_kbn,
        AHE.app_type_id,
        ATM.app_type_name,
        AHE.app_name,
        AHE.vlan_room_id,
        AHE.vlan_id,
        AHE.mac_addr,
        AHE.ip_addr,
        CASE
          WHEN ATM.ip_kbn = {IP_KBN_DHCP} THEN {DUMY_IP_ADDR_DHCP}
          WHEN ATM.ip_kbn = {IP_KBN_FREE} AND AHE.ip_kbn = {IP_KBN_DHCP} THEN {DUMY_IP_ADDR_DHCP}
          WHEN COALESCE(AHE.ip_addr, '') = '' THEN {DUMY_IP_ADDR_NONE}
          ELSE AHE.ip_addr
        END AS ip_addr_sort,
        CASE
          WHEN ATM.wire_kbn = {WIRE_KBN_WLESS} THEN VROOM.vlan_area_name
          WHEN ATM.wire_kbn = {WIRE_KBN_FREE} AND AHE.wire_kbn = {WIRE_KBN_WLESS} THEN VROOM.vlan_area_name
          ELSE VLAN.vlan_area_name
        END AS vlan_area_name,
        AHE.wire_kbn,
        AHE.ip_kbn,
        AHE.update_time,
        UM.kanjisei || '　' || UM.kanjimei AS app_user_name,
        NULL::integer AS app_user_id,
        AHE.entry_id  AS entry_id
    FROM
        app_head_entry AS AHE
        LEFT OUTER JOIN (
            SELECT
                VDM.vlan_ridge_id,
                VFM.vlan_floor_id,
                VRM.vlan_room_id,
                VDM.vlan_ridge_name || '　' || VFM.vlan_floor_name || '　' || VRM.vlan_room_name AS vlan_area_name
            FROM
                vlan_room_mst AS VRM,
                vlan_floor_mst AS VFM,
                vlan_ridge_mst AS VDM
            WHERE
                VRM.vlan_floor_id = VFM.vlan_floor_id AND
                VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
                VRM.del_flg = '0' AND
                VFM.del_flg = '0' AND
                VDM.del_flg = '0'
        ) VROOM ON VROOM.vlan_room_id = AHE.vlan_room_id
        LEFT OUTER JOIN (
            SELECT
                VDM.vlan_ridge_id,
                VDM.vlan_ridge_name,
                VFM.vlan_floor_id,
                VFM.vlan_floor_name,
                VRM.vlan_room_id,
                VRM.vlan_room_name,
                VLM.vlan_id,
                VLM.admin_name,
                VDM.vlan_ridge_name || '　' ||
                  VFM.vlan_floor_name || '　' ||
                  VRM.vlan_room_name || '　' ||
                  VLM.admin_name || '（VLAN' || VLM.vlan_name || '）' AS vlan_area_name
            FROM
                vlan_mst AS VLM,
                vlan_room_mst AS VRM,
                vlan_floor_mst AS VFM,
                vlan_ridge_mst AS VDM
            WHERE
                VLM.vlan_room_id = VRM.vlan_room_id AND
                VRM.vlan_floor_id = VFM.vlan_floor_id AND
                VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
                VLM.del_flg = '0' AND
                VRM.del_flg = '0' AND
                VFM.del_flg = '0' AND
                VDM.del_flg = '0'
        ) VLAN ON VLAN.vlan_id = AHE.vlan_id,
        user_mst AS UM,
        app_type_mst AS ATM
    WHERE
        AHE.app_type_id = ATM.app_type_id AND
        AHE.entry_status = {ENTRY_STATUS_ENTRY} AND
        AHE.del_flg='0' AND
        ATM.del_flg='0' AND
        UM.user_id = AHE.entry_id AND
        EXISTS (
            SELECT
                *
            FROM
                (
                SELECT
                    app_id,
                    MAX(entry_no) AS entry_no
                FROM
                    app_head_entry
                WHERE
                    del_flg = '0'
                GROUP BY
                    app_id
                ) AS LST
            WHERE
                AHE.app_id = app_id AND
                AHE.entry_no = entry_no
        )
    UNION
    SELECT
        AHE.app_id,
        AHE.entry_no,
        AHE.entry_status,
        AHE.entry_kbn,
        AHE.app_type_id,
        ATM.app_type_name,
        AHE.app_name,
        AHE.vlan_room_id,
        AHE.vlan_id,
        AHE.mac_addr,
        AHE.ip_addr,
        CASE
          WHEN ATM.ip_kbn = {IP_KBN_DHCP} THEN {DUMY_IP_ADDR_DHCP}
          WHEN ATM.ip_kbn = {IP_KBN_FREE} AND AHE.ip_kbn = {IP_KBN_DHCP} THEN {DUMY_IP_ADDR_DHCP}
          WHEN COALESCE(AHE.ip_addr, '') = '' THEN {DUMY_IP_ADDR_NONE}
          ELSE AHE.ip_addr
        END AS ip_addr_sort,
        CASE
          WHEN ATM.wire_kbn = {WIRE_KBN_WLESS} THEN VROOM.vlan_area_name
          WHEN ATM.wire_kbn = {WIRE_KBN_FREE} AND AHE.wire_kbn = {WIRE_KBN_WLESS} THEN VROOM.vlan_area_name
          ELSE VLAN.vlan_area_name
        END AS vlan_area_name,
        AHE.wire_kbn,
        AHE.ip_kbn,
        AHE.update_time,
        UM.kanjisei || '　' || UM.kanjimei AS app_user_name,
        NULL::integer AS app_user_id,
        AHE.entry_id  AS entry_id
    FROM
        app_head_entry AS AHE
        LEFT OUTER JOIN (
            SELECT
                VDM.vlan_ridge_id,
                VFM.vlan_floor_id,
                VRM.vlan_room_id,
                VDM.vlan_ridge_name || '　' || VFM.vlan_floor_name || '　' || VRM.vlan_room_name AS vlan_area_name
            FROM
                vlan_room_mst AS VRM,
                vlan_floor_mst AS VFM,
                vlan_ridge_mst AS VDM
            WHERE
                VRM.vlan_floor_id = VFM.vlan_floor_id AND
                VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
                VRM.del_flg = '0' AND
                VFM.del_flg = '0' AND
                VDM.del_flg = '0'
        ) VROOM ON VROOM.vlan_room_id = AHE.vlan_room_id
        LEFT OUTER JOIN (
            SELECT
                VDM.vlan_ridge_id,
                VDM.vlan_ridge_name,
                VFM.vlan_floor_id,
                VFM.vlan_floor_name,
                VRM.vlan_room_id,
                VRM.vlan_room_name,
                VLM.vlan_id,
                VLM.admin_name,
                VDM.vlan_ridge_name || '　' ||
                  VFM.vlan_floor_name || '　' ||
                  VRM.vlan_room_name || '　' ||
                  VLM.admin_name || '（VLAN' || VLM.vlan_name || '）' AS vlan_area_name
            FROM
                vlan_mst AS VLM,
                vlan_room_mst AS VRM,
                vlan_floor_mst AS VFM,
                vlan_ridge_mst AS VDM
            WHERE
                VLM.vlan_room_id = VRM.vlan_room_id AND
                VRM.vlan_floor_id = VFM.vlan_floor_id AND
                VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
                VLM.del_flg = '0' AND
                VRM.del_flg = '0' AND
                VFM.del_flg = '0' AND
                VDM.del_flg = '0'
        ) VLAN ON VLAN.vlan_id = AHE.vlan_id,
        user_mst AS UM,
        app_type_mst AS ATM
    WHERE
        AHE.app_type_id = ATM.app_type_id AND
        AHE.entry_status = {ENTRY_STATUS_REJECT} AND
        AHE.del_flg='0' AND
        ATM.del_flg='0' AND
        UM.user_id = AHE.entry_id AND
        NOT EXISTS (
            SELECT
                *
            FROM
                app_head_entry AS AHET
            WHERE
                AHE.app_id = app_id AND
                del_flg = '0' AND
                entry_status = {ENTRY_STATUS_ENTRY} AND
                EXISTS (
                    SELECT
                        *
                    FROM
                        (
                        SELECT
                            app_id,
                            MAX(entry_no) AS entry_no
                        FROM
                            app_head_entry
                        WHERE
                            del_flg = '0'
                        GROUP BY
                            app_id
                        ) AS LST
                    WHERE
                        AHET.app_id = app_id AND
                        AHET.entry_no = entry_no
                )
            ) AND
        EXISTS (
            SELECT
                *
            FROM
                (
                SELECT
                    app_id,
                    MAX(entry_no) AS entry_no
                FROM
                    app_head_entry
                WHERE
                    del_flg = '0'
                GROUP BY
                    app_id
                ) AS LST
            WHERE
                AHE.app_id = app_id AND
                AHE.entry_no = entry_no
        )
    UNION
    SELECT
        AHT.app_id,
        NULL::integer  AS entry_no,
        NULL::varchar  AS entry_status,
        NULL::varchar  AS entry_kbn,
        AHT.app_type_id,
        ATM.app_type_name,
        AHT.app_name,
        AHT.vlan_room_id,
        AHT.vlan_id,
        AHT.mac_addr,
        AHT.ip_addr,
        CASE
          WHEN ATM.ip_kbn = {IP_KBN_DHCP} THEN {DUMY_IP_ADDR_DHCP}
          WHEN ATM.ip_kbn = {IP_KBN_FREE} AND AHT.ip_kbn = {IP_KBN_DHCP} THEN {DUMY_IP_ADDR_DHCP}
          WHEN COALESCE(AHT.ip_addr, '') = '' THEN {DUMY_IP_ADDR_NONE}
          ELSE AHT.ip_addr
        END AS ip_addr_sort,
        CASE
          WHEN ATM.wire_kbn = {WIRE_KBN_WLESS} THEN VROOM.vlan_area_name
          WHEN ATM.wire_kbn = {WIRE_KBN_FREE} AND AHT.wire_kbn = {WIRE_KBN_WLESS} THEN VROOM.vlan_area_name
          ELSE VLAN.vlan_area_name
        END AS vlan_area_name,
        AHT.wire_kbn,
        AHT.ip_kbn,
        AHT.update_time,
        UM.kanjisei || '　' || UM.kanjimei AS app_user_name,
        AHT.app_user_id,
        NULL::integer AS entry_id
    FROM
        app_head_tbl AS AHT
        LEFT OUTER JOIN (
            SELECT
                VDM.vlan_ridge_id,
                VFM.vlan_floor_id,
                VRM.vlan_room_id,
                VDM.vlan_ridge_name || '　' || VFM.vlan_floor_name || '　' || VRM.vlan_room_name AS vlan_area_name
            FROM
                vlan_room_mst AS VRM,
                vlan_floor_mst AS VFM,
                vlan_ridge_mst AS VDM
            WHERE
                VRM.vlan_floor_id = VFM.vlan_floor_id AND
                VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
                VRM.del_flg = '0' AND
                VFM.del_flg = '0' AND
                VDM.del_flg = '0'
        ) VROOM ON VROOM.vlan_room_id = AHT.vlan_room_id
        LEFT OUTER JOIN (
            SELECT
                VDM.vlan_ridge_id,
                VDM.vlan_ridge_name,
                VFM.vlan_floor_id,
                VFM.vlan_floor_name,
                VRM.vlan_room_id,
                VRM.vlan_room_name,
                VLM.vlan_id,
                VLM.admin_name,
                VDM.vlan_ridge_name || '　' ||
                  VFM.vlan_floor_name || '　' ||
                  VRM.vlan_room_name || '　' ||
                  VLM.admin_name || '（VLAN' || VLM.vlan_name || '）' AS vlan_area_name
            FROM
                vlan_mst AS VLM,
                vlan_room_mst AS VRM,
                vlan_floor_mst AS VFM,
                vlan_ridge_mst AS VDM
            WHERE
                VLM.vlan_room_id = VRM.vlan_room_id AND
                VRM.vlan_floor_id = VFM.vlan_floor_id AND
                VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
                VLM.del_flg = '0' AND
                VRM.del_flg = '0' AND
                VFM.del_flg = '0' AND
                VDM.del_flg = '0'
        ) VLAN ON VLAN.vlan_id = AHT.vlan_id,
        user_mst AS UM,
        app_type_mst AS ATM
    WHERE
        AHT.app_type_id = ATM.app_type_id AND
        AHT.del_flg='0' AND
        ATM.del_flg='0' AND
        UM.user_id = AHT.app_user_id AND
        NOT EXISTS (
            SELECT
                *
            FROM
                app_head_entry AS AHE
            WHERE
                AHT.app_id = app_id AND
                del_flg = '0' AND
                entry_status IN ({ENTRY_STATUS_ENTRY}, {ENTRY_STATUS_REJECT}) AND
                EXISTS (
                    SELECT
                        *
                    FROM
                        (
                        SELECT
                            app_id,
                            MAX(entry_no) AS entry_no
                        FROM
                            app_head_entry
                        WHERE
                            del_flg = '0'
                        GROUP BY
                            app_id
                        ) AS LST
                    WHERE
                        AHE.app_id = app_id AND
                        AHE.entry_no = entry_no
                )
            )
    ) AS APP
{COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";



$cmnSql['ENTRY_LOCK'] = "SELECT * FROM app_head_entry WHERE app_id = {0} FOR UPDATE";
$cmnSql['GET_ENTRY_NO'] = "SELECT COALESCE(MAX(entry_no), 0) + 1 FROM app_head_entry WHERE app_id = {0}";


$cmnSql['INSERT_APP_HEAD_ENTRY'] = "
INSERT INTO app_head_entry (
app_id,
entry_no,
entry_status,
entry_kbn,
app_type_id,
app_name,
vlan_room_id,
vlan_id,
mac_addr,
ip_addr,
wire_kbn,
ip_kbn,
note,
use_sbc,
make_id,
update_id,
entry_time,
entry_id
)
VALUES
(
{APP_ID},
{ENTRY_NO},
{ENTRY_STATUS},
{ENTRY_KBN},
{APP_TYPE_ID},
{APP_NAME},
{VLAN_ROOM_ID},
{VLAN_ID},
{MAC_ADDR},
{IP_ADDR},
{WIRE_KBN},
{IP_KBN},
{NOTE},
{USE_SBC},
{MAKE_ID},
{UPDATE_ID},
now(),
{ENTRY_ID}
)
";


$cmnSql['INSERT_APP_LIST_ENTRY'] = "
INSERT INTO app_list_entry (
app_id,
entry_no,
vlan_id,
busy_flg,
make_id,
update_id,
entry_time,
entry_id
)
VALUES
(
{APP_ID},
{ENTRY_NO},
{VLAN_ID},
{BUSY_FLG},
{MAKE_ID},
{UPDATE_ID},
now(),
{ENTRY_ID}
)
";

$cmnSql['DELETE_APP_HEAD_TBL'] = "UPDATE app_head_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {0}";

$cmnSql['DELETE_APP_LIST_TBL'] = "UPDATE app_list_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {0}";

?>