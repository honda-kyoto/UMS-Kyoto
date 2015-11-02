<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(AHE.app_id) AS list_count
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
    entry_user_name
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
        AHE.entry_id,
        UM.kanjisei || '　' || UM.kanjimei AS entry_user_name,
        AHE.update_time
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
    ) AS AHE
WHERE
    true
    {COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";

?>