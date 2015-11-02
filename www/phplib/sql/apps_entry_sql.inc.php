<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/apps_regist_common_sql.inc.php");

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
make_id,
update_id,
entry_time,
entry_id,
wireless_id,
use_sbc
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
{MAKE_ID},
{UPDATE_ID},
now(),
{ENTRY_ID},
{WIRELESS_ID},
{USE_SBC}
)
";


$cmnSql['INSERT_APP_LIST_ENTRY'] = "
INSERT INTO app_list_entry (
app_id,
entry_no,
vlan_id,
make_id,
update_id,
entry_time,
entry_id,
busy_flg
)
VALUES
(
{APP_ID},
{ENTRY_NO},
{VLAN_ID},
{MAKE_ID},
{UPDATE_ID},
now(),
{ENTRY_ID},
{BUSY_FLG}
)
";

$cmnSql['INSERT_APP_LIST_ENTRY_SAVE'] = "
INSERT INTO app_list_entry (
app_id,
entry_no,
vlan_id,
make_id,
update_id,
entry_time,
entry_id,
agree_flg,
busy_flg
)
VALUES
(
{APP_ID},
{ENTRY_NO},
{VLAN_ID},
{MAKE_ID},
{UPDATE_ID},
now(),
{ENTRY_ID},
'1',
{BUSY_FLG}
)
";

?>