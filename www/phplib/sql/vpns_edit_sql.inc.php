<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/vpns_regist_common_sql.inc.php");

$cmnSql['UPDATE_VPN_HEAD'] = "
UPDATE
    vpn_head_tbl
SET
    vpn_kbn = {VPN_KBN},
    vpn_name = {VPN_NAME},
    group_name = {GROUP_NAME},
    group_code = {GROUP_CODE},
    usage = {USAGE},
    note = {NOTE},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vpn_id = {VPN_ID}
";

$cmnSql['DELETE_VPN_ADMIN_DATA'] = "
UPDATE
    vpn_admin_list
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vpn_id = {VPN_ID}
";

$cmnSql['EXISTS_VPN_ADMIN'] = "
SELECT
    list_no
FROM
    vpn_admin_list
WHERE
    vpn_id = {VPN_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['UPDATE_VPN_ADMIN'] = "
UPDATE
    vpn_admin_list
SET
    user_id = {USER_ID},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vpn_id = {VPN_ID} AND
    list_no = {LIST_NO}
";

?>