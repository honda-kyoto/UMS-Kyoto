<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/apps_regist_common_sql.inc.php");



$cmnSql['UPDATE_APP_HEAD_TBL'] = "
UPDATE
    app_head_tbl
SET
    app_name = {APP_NAME},
    vlan_room_id = {VLAN_ROOM_ID},
    vlan_id = {VLAN_ID},
    mac_addr = {MAC_ADDR},
    ip_addr = {IP_ADDR},
    wire_kbn = {WIRE_KBN},
    ip_kbn = {IP_KBN},
    note = {NOTE},
    use_sbc = {USE_SBC},
    update_time = now(),
    update_id = {UPDATE_ID},
    app_user_id = {APP_USER_ID}
WHERE
    app_id = {APP_ID}
";

$cmnSql['EXISTS_APP_LIST_TBL'] = "SELECT vlan_id FROM app_list_tbl WHERE app_id = {APP_ID} AND vlan_id = {VLAN_ID}";

$cmnSql['UPDATE_APP_LIST_TBL'] = "
UPDATE
    app_list_tbl
SET
    busy_flg = {BUSY_FLG},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    app_id = {APP_ID} AND
    vlan_id = {VLAN_ID}
";

$cmnSql['INSERT_APP_LIST_TBL'] = "
INSERT INTO app_list_tbl (
app_id,
vlan_id,
busy_flg,
make_id,
update_id
)
VALUES
(
{APP_ID},
{VLAN_ID},
{BUSY_FLG},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['DELETE_APP_LIST_TBL'] = "UPDATE app_list_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {APP_ID} AND vlan_id = {VLAN_ID}";


?>