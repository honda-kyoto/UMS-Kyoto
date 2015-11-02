<?php
/**********************************************************
* File         : belong_class_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT belong_class_id FROM belong_class_mst WHERE del_flg = '0' AND belong_class_name = '{0}' {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO belong_class_mst (
belong_class_id,
belong_class_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('belong_class_id_seq'),
'{0}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM belong_class_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['INSERT_DIV_DATA'] = "
INSERT INTO belong_div_mst (
belong_div_id,
belong_class_id,
belong_div_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('belong_div_id_seq'),
'{0}',
'',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM belong_div_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['INSERT_DEP_DATA'] = "
INSERT INTO belong_dep_mst (
belong_dep_id,
belong_div_id,
belong_dep_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('belong_dep_id_seq'),
'{0}',
'',
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
    belong_class_mst
SET
    belong_class_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_class_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    belong_class_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_class_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    belong_class_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    belong_class_id = {0}
";

$cmnSql['SELECT_CLASS_ID'] = "
SELECT
    currval('belong_class_id_seq')
";

$cmnSql['SELECT_DIV_ID'] = "
SELECT
    currval('belong_div_id_seq')
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