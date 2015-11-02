<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['EXISTS_MAC_ADDR'] = "SELECT mac_addr FROM app_head_{TYPE} WHERE del_flg = '0' AND mac_addr = '{0}' {COND}";


$cmnSql['EXISTS_USER_WIRELESS_ID'] = "SELECT wireless_id FROM user_wireless_id WHERE del_flg = '0' AND wireless_id = '{0}'";
$cmnSql['EXISTS_APP_WIRELESS_ID'] = "SELECT wireless_id FROM app_head_tbl WHERE del_flg = '0' AND wireless_id = '{0}'";


$cmnSql['EXISTS_WIRELESS_ID'] = "SELECT wireless_id FROM user_wireless_id WHERE user_id = {0}";

$cmnSql['UPDATE_WIRELESS_ID'] = "UPDATE user_wireless_id SET wireless_id = '{1}', del_flg = '0', update_time = now(), update_id = {UPDATE_ID} WHERE user_id = {0}";

$cmnSql['INSERT_WIRELESS_ID'] = "INSERT INTO user_wireless_id (user_id, wireless_id, make_id, update_id) VALUES ({0}, '{1}', {MAKE_ID}, {UPDATE_ID})";

$cmnSql['GET_APP_HEAD_DATA'] = "
SELECT
    AH.app_id,
    AH.app_type_id,
    ATM.app_type_name,
    AH.app_name,
    AH.vlan_room_id,
    AH.vlan_id,
    AH.mac_addr,
    AH.ip_addr,
    AH.wire_kbn,
    AH.ip_kbn,
    AH.note,
    AH.wireless_id,
    use_sbc,
    TO_CHAR(AH.make_time, 'YYYY/MM/DD HH24:MI:SS') AS make_time,
    AH.make_id,
    TO_CHAR(AH.update_time, 'YYYY/MM/DD HH24:MI:SS') AS update_time,
    AH.update_id
    {COL}
FROM
    app_head_{TYPE} AS AH,
    app_type_mst AS ATM
WHERE
    AH.app_type_id = ATM.app_type_id AND
    AH.del_flg='0' AND
    ATM.del_flg='0' AND
    AH.app_id = {0}
    {COND}
";

$cmnSql['GET_APP_LIST_DATA'] = "
SELECT
    vlan_id,
    busy_flg,
    {AGREE_FLG}
FROM
    app_list_{TYPE}
WHERE
    del_flg='0' AND
    app_id = {0}
    {COND}
";

$cmnSql['GET_USER_ID_BY_MAC'] = "
SELECT
    app_id,
    vlan_id,
    app_user_id
FROM
    app_head_tbl AS AHT
WHERE
    mac_addr = '{0}' AND
    del_flg = '0' AND
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
";

$cmnSql['GET_LAST_AGREE_DATA'] = "
SELECT
        AHE.app_id,
        AHE.entry_no,
        AHE.entry_status,
        AHE.entry_kbn,
        TO_CHAR(AHE.entry_time, 'YYYY/MM/DD HH24:MI:SS') AS entry_time,
        AHE.entry_id,
        TO_CHAR(AHE.agree_time, 'YYYY/MM/DD HH24:MI:SS') AS agree_time,
        AHE.agree_id
    FROM
        app_head_entry AS AHE,
        app_type_mst AS ATM
    WHERE
        AHE.app_type_id = ATM.app_type_id AND
        AHE.entry_status = {ENTRY_STATUS_AGREE} AND
        AHE.del_flg='0' AND
        ATM.del_flg='0' AND
        AHE.app_id = {0} AND
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
                    del_flg = '0' AND
                    entry_status = {ENTRY_STATUS_AGREE}
                GROUP BY
                    app_id
                ) AS LST
            WHERE
                AHE.app_id = app_id AND
                AHE.entry_no = entry_no
        )
";


$cmnSql['CHECK_VDI_APP'] = "SELECT COUNT(app_type_id) FROM sbc_drivername_lst WHERE app_type_id = {0}";


$cmnSql['EXISTS_APP_AD_ERR'] = "SELECT log_cd FROM app_ad_errlog WHERE app_id = {0} AND complete_flg = '0'";

$cmnSql['INSERT_APP_AD_ERR'] = "INSERT INTO app_ad_errlog (log_cd, log_time, app_id) VALUES ('{0}', now(), {1})";

?>