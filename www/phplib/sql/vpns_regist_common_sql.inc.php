<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_VPN_DATA'] = "
SELECT
    vpn_kbn,
    vpn_name,
    group_name,
    group_code,
    usage,
    note
FROM
    vpn_head_tbl
WHERE
    del_flg = '0' AND
    vpn_id = {0}
";

$cmnSql['GET_ADMIN_LIST'] = "
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
    MA.vpn_id = {0}
ORDER BY
    MA.list_no
";

$cmnSql['GET_ADMIN_ID'] = "
SELECT
    list_no,
    user_id
FROM
    vpn_admin_list
WHERE
    vpn_id = {0} AND
    del_flg = '0'
ORDER BY
    list_no
";


$cmnSql['GET_ADMIN_NAME'] = "SELECT kanjisei || '　' || kanjimei FROM user_mst WHERE user_id = {0}";

$cmnSql['EXISTS_VPN_NAME'] = "SELECT vpn_id FROM vpn_head_tbl WHERE vpn_name = '{0}' AND del_flg = '0' {COND}";
$cmnSql['EXISTS_GROUP_CODE'] = "SELECT vpn_id FROM vpn_head_tbl WHERE group_name = '{0}' AND del_flg = '0' {COND}";
$cmnSql['EXISTS_GROUP_CODE'] = "SELECT vpn_id FROM vpn_head_tbl WHERE group_code = '{0}' AND del_flg = '0' {COND}";


$cmnSql['INSERT_VPN_ADMIN'] = "
INSERT INTO vpn_admin_list (
vpn_id,
list_no,
user_id,
make_id,
update_id
)
VALUES
(
{VPN_ID},
{LIST_NO},
{USER_ID},
{MAKE_ID},
{UPDATE_ID}
)
";


?>