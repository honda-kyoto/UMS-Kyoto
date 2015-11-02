<?php
/**********************************************************
* File         : belong_chg_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT belong_chg_id FROM belong_chg_mst WHERE del_flg = '0' AND belong_chg_name = '{0}' AND belong_sec_id ={1} {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO belong_chg_mst (
belong_chg_id,
belong_chg_name,
belong_sec_id,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('belong_chg_id_seq'),
'{0}',
{BELONG_SEC_ID},
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM belong_chg_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    belong_chg_mst
SET
    belong_chg_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_chg_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    belong_chg_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_chg_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    belong_chg_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_chg_id = {0}
";



$cmnSql['UPDATE_PARENT_ID'] = "
UPDATE
    belong_chg_mst
SET
    belong_sec_id = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_sec_id = {0}
    and belong_chg_id in({2})
";


?>