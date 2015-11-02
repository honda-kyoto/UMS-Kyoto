<?php
/**********************************************************
* File         : printer_driver_add_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['EXISTS_APP_ID'] = "
SELECT
    driver_name
FROM
    sbc_device_drivername
WHERE
    app_id = {0}
";

$cmnSql['DELETE_DEVICE_DRIVERNAME'] = "DELETE FROM sbc_device_drivername WHERE app_id = {0}";

$cmnSql['INSERT_DEVICE_DRIVERNAME'] = "
INSERT INTO sbc_device_drivername (
app_id,
driver_name,
make_id,
update_id
)
VALUES
(
{0},
'{1}',
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DEVICE_DRIVERNAME'] = "
UPDATE
    sbc_device_drivername
SET
    driver_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    app_id = {0}
";

$cmnSql['GET_PRINTER_LIST'] = "
SELECT
    APP.app_id,
    APP.app_name,
    APP.vlan_room_id
FROM
    app_head_tbl AS APP
WHERE
    APP.app_type_id = {APP_TYPE_ID} AND
    APP.use_sbc = '1' {COND}
ORDER BY
    APP.app_name
";

$cmnSql['GET_DRIVERNAME_LIST'] = "
SELECT
    driver_name
FROM
    sbc_drivername_lst
WHERE
    app_type_id = {APP_TYPE_ID}
ORDER BY
    driver_name
";


?>