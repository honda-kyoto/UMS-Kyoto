<?php
/**********************************************************
* File         : vlan_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");


$cmnSql['GET_VLAN_ADMIN_DATA'] = "
SELECT
    VA.list_no,
    UM.kanjisei,
    UM.kanjimei,
    UM.belong_chg_id
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

$cmnSql['EXISTS_VLAN_ADMIN'] = "SELECT user_id FROM vlan_admin_list WHERE del_flg = '0' AND vlan_id = {0} AND user_id = {1}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO vlan_admin_list (
vlan_id,
list_no,
user_id,
make_id,
update_id
)
VALUES
(
{0},
(SELECT COALESCE(MAX(list_no),0) + 1 FROM vlan_admin_list WHERE vlan_id = {0}),
{1},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    vlan_admin_list
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_id = {0} AND
    list_no = {1}
";



?>