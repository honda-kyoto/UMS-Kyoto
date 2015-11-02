<?php
/**********************************************************
* File         : printer_driver_add_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_USER_DEVICE_LIST'] = "SELECT device_id FROM sbc_user_device_tbl WHERE user_id = {0} ORDER BY disp_num";

$cmnSql['DELETE_USER_DEVICE'] = "DELETE FROM sbc_user_device_tbl WHERE user_id = {0}";

$cmnSql['INSERT_USER_DEVICE'] = "
INSERT INTO sbc_user_device_tbl (
user_id,
device_id,
disp_num,
make_id,
update_id
)
VALUES
(
{0},
{1},
{2},
{MAKE_ID},
{UPDATE_ID}
)
";


$cmnSql['GET_DEVICE_LIST'] = "
SELECT
    APP.app_id,
    APP.app_name,
    APP.vlan_room_id
FROM
    app_head_tbl AS APP,
    sbc_device_drivername AS SDD
WHERE
    APP.app_id = SDD.app_id AND
    APP.app_type_id = {APP_TYPE_ID} AND
    APP.del_flg = '0' {COND}
ORDER BY
    APP.app_name
";

$cmnSql['GET_APP_NAME'] = "
SELECT
    app_name,
    vlan_room_id
FROM
    app_head_tbl
WHERE
    app_id = {0}
";


?>