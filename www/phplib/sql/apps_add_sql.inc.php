<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/apps_regist_common_sql.inc.php");


$cmnSql['INSERT_APP_HEAD_TBL'] = "
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
VALUES
(
{APP_ID},
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
{APP_USER_ID},
{WIRELESS_ID},
{USE_SBC}
)
";


$cmnSql['INSERT_APP_LIST_TBL'] = "
INSERT INTO app_list_tbl (
app_id,
vlan_id,
make_id,
update_id,
busy_flg
)
VALUES
(
{APP_ID},
{VLAN_ID},
{MAKE_ID},
{UPDATE_ID},
{BUSY_FLG}
)
";


?>