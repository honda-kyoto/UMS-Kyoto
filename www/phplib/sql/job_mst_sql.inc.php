<?php
/**********************************************************
* File         : job_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT job_id FROM job_mst WHERE del_flg = '0' AND job_name = '{0}' {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO job_mst (
job_id,
job_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('job_id_seq'),
'{0}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM job_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    job_mst
SET
    job_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    job_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    job_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    job_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    job_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    job_id = {0}
";

?>