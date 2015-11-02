<?php
/**********************************************************
* File         : vlan_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_VLAN_LIST'] = "SELECT vlan_id, vlan_name, admin_name FROM vlan_mst WHERE del_flg = '0' AND vlan_room_id = {0} ORDER BY disp_num";

$cmnSql['GET_VLAN_ADMIN_LIST'] = "
SELECT
    UM.kanjisei || '　' || UM.kanjimei
FROM
    vlan_admin_list AS VA,
    user_mst AS UM
WHERE
    VA.user_id = UM.user_id AND
    VA.del_flg = '0' AND
    VA.vlan_id = {0}
ORDER BY
    VA.list_no
";

$cmnSql['NAME_EXISTS'] = "SELECT vlan_id FROM vlan_mst WHERE del_flg = '0' AND vlan_name = '{0}' AND vlan_room_id = {1} {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO vlan_mst (
vlan_id,
vlan_room_id,
vlan_name,
admin_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('vlan_id_seq'),
{2},
'{0}',
'{1}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM vlan_mst WHERE vlan_room_id = {2}),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    vlan_mst
SET
    vlan_name = '{1}',
    admin_name = '{2}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    vlan_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_id = {0}
";

$cmnSql['DELETE_ADMIN_DATA'] = "
UPDATE
    vlan_admin_list
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    vlan_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_id = {0}
";

?>