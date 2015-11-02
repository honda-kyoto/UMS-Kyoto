<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/vpns_regist_common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(VHT.vpn_id) AS list_count
FROM
    vpn_head_tbl AS VHT
WHERE
    del_flg = '0'
    {COND}
";

$cmnSql['GETLIST'] = "
SELECT
    VHT.vpn_id,
    VHT.vpn_kbn,
    VHT.vpn_name,
    VHT.group_name,
    VHT.group_code,
    ARRAY(
        SELECT
            UM.kanjisei || '　' || UM.kanjimei AS admin_name
        FROM
            vpn_admin_list AS MA,
            user_mst AS UM
        WHERE
            MA.user_id = UM.user_id AND
            UM.start_date <= now()::date AND
            COALESCE(UM.end_date, now()::date) >= now()::date AND
            MA.del_flg = '0' AND
            MA.vpn_id = VHT.vpn_id
        ORDER BY
            MA.list_no
    ) AS sort_admin_name
FROM
    vpn_head_tbl AS VHT
WHERE
    del_flg = '0'
    {COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";

$cmnSql['DELETE_VPN_HEAD'] = "UPDATE vpn_head_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE vpn_id = {0}";
$cmnSql['DELETE_VPN_ADMIN'] = "UPDATE vpn_admin_list SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE vpn_id = {0}";
$cmnSql['DELETE_VPN_MEMBERS'] = "UPDATE vpn_members_list SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE vpn_id = {0}";




?>