<?php
/**********************************************************
* File         : vlan_ridge_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT vlan_ridge_id FROM vlan_ridge_mst WHERE del_flg = '0' AND vlan_ridge_name = '{0}' {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO vlan_ridge_mst (
vlan_ridge_id,
vlan_ridge_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('vlan_ridge_id_seq'),
'{0}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM vlan_ridge_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    vlan_ridge_mst
SET
    vlan_ridge_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_ridge_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    vlan_ridge_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_ridge_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    vlan_ridge_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vlan_ridge_id = {0}
";

?>