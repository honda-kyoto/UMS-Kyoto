<?php
/**********************************************************
* File         : belong_dep_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT belong_dep_id FROM belong_dep_mst WHERE del_flg = '0' AND belong_dep_name = '{0}' AND belong_div_id ={1} {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO belong_dep_mst (
belong_dep_id,
belong_dep_name,
belong_div_id,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('belong_dep_id_seq'),
'{0}',
{BELONG_DIV_ID},
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM belong_dep_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['INSERT_SEC_DATA'] = "
INSERT INTO belong_sec_mst (
belong_sec_id,
belong_dep_id,
belong_sec_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('belong_sec_id_seq'),
'{0}',
'',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM belong_sec_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['INSERT_CHG_DATA'] = "
INSERT INTO belong_chg_mst (
belong_chg_id,
belong_sec_id,
belong_chg_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('belong_chg_id_seq'),
'{0}',
'',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM belong_chg_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    belong_dep_mst
SET
    belong_dep_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_dep_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    belong_dep_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_dep_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    belong_dep_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_dep_id = {0}
";



$cmnSql['UPDATE_PARENT_ID'] = "
UPDATE
    belong_dep_mst
SET
    belong_div_id = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_div_id = {0}
    and belong_dep_id in({2})
";

$cmnSql['SELECT_DEP_ID'] = "
SELECT
    currval('belong_dep_id_seq')
";

$cmnSql['SELECT_SEC_ID'] = "
SELECT
    currval('belong_sec_id_seq')
";

?>