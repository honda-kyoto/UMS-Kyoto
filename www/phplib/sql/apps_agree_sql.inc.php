<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/apps_regist_common_sql.inc.php");

$cmnSql['REJECT_APP_HEAD_ENTRY'] = "UPDATE app_head_entry SET entry_status = {ENTRY_STATUS}, agree_time = now(), agree_id = {UPDATE_ID}, agree_note = {AGREE_NOTE}, update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {0} AND entry_no = {1}";
$cmnSql['AGREE_APP_HEAD_ENTRY'] = "UPDATE app_head_entry SET entry_status = {ENTRY_STATUS}, update_time = now(), update_id = {UPDATE_ID}, agree_time = now(), agree_id = {UPDATE_ID} WHERE app_id = {0} AND entry_no = {1}";

$cmnSql['INSERT_APP_ORG_DATA'] = "
INSERT INTO app_head_tbl (
app_id,
app_type_id,
app_name,
vlan_room_id,
vlan_id,
mac_addr,
ip_addr,
wire_kbn,
ip_kbn,
note,
make_id,
update_id,
app_user_id,
wireless_id,
use_sbc
)
SELECT
    app_id,
    app_type_id,
    app_name,
    vlan_room_id,
    vlan_id,
    mac_addr,
    {IP_ADDR} AS ip_addr,
    wire_kbn,
    ip_kbn,
    note,
    {MAKE_ID} AS make_id,
    {UPDATE_ID} AS update_id,
    entry_id AS app_user_id,
    wireless_id,
    {USE_SBC} AS use_sbc
FROM
    app_head_entry
WHERE
    app_id = {0} AND
    entry_no = {1}
";

$cmnSql['INSERT_APP_ORG_LIST'] = "
INSERT INTO app_list_tbl (
app_id,
vlan_id,
make_id,
update_id,
busy_flg
)
SELECT
    app_id,
    vlan_id,
    {MAKE_ID} AS make_id,
    {UPDATE_ID} AS update_id,
    busy_flg
FROM
    app_list_entry
WHERE
    app_id = {0} AND
    entry_no = {1}
";

$cmnSql['UPDATE_APP_ORG_DATA'] = "
UPDATE
    app_head_tbl
SET
    app_name = (SELECT app_name FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    vlan_room_id = (SELECT vlan_room_id FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    vlan_id = (SELECT vlan_id FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    mac_addr = (SELECT mac_addr FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    ip_addr = {IP_ADDR},
    wire_kbn = (SELECT wire_kbn FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    ip_kbn = (SELECT ip_kbn FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    note = (SELECT note FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    app_user_id = (SELECT entry_id FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    wireless_id = (SELECT wireless_id FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    use_sbc = (SELECT use_sbc FROM app_head_entry WHERE app_id = {0} AND entry_no = {1}),
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    app_id = {0}
";

$cmnSql['EXISTS_APP_LIST_DATA'] = "SELECT vlan_id FROM app_list_tbl WHERE app_id = {APP_ID} AND vlan_id = {VLAN_ID}";

$cmnSql['UPDATE_APP_LIST_DATA'] = "
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

$cmnSql['INSERT_APP_LIST_DATA'] = "
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

$cmnSql['DELETE_APP_LIST_DATA'] = "UPDATE app_list_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {APP_ID} AND vlan_id = {VLAN_ID}";

$cmnSql['DELETE_APP_ORG_DATA'] = "UPDATE app_head_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {0}";
$cmnSql['DELETE_APP_ORG_LIST'] = "UPDATE app_list_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {0}";

?>