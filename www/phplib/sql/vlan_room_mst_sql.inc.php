<?php
/**********************************************************
* File         : vlan_room_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT vlan_room_id FROM vlan_room_mst WHERE del_flg = '0' AND vlan_room_name = '{0}' AND vlan_floor_id = {1} {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO vlan_room_mst (
vlan_room_id,
vlan_floor_id,
vlan_room_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('vlan_room_id_seq'),
{1},
'{0}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM vlan_room_mst WHERE vlan_floor_id = {1}),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    vlan_room_mst
SET
    vlan_room_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_room_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    vlan_room_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_room_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    vlan_room_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_room_id = {0}
";

?>