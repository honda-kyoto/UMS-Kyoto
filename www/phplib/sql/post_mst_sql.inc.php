<?php
/**********************************************************
* File         : post_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT post_id FROM post_mst WHERE del_flg = '0' AND post_name = '{0}' {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO post_mst (
post_id,
post_name,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('post_id_seq'),
'{0}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM post_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    post_mst
SET
    post_name = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    post_id = {0}
";

$cmnSql['DELETE_DATA'] = "
UPDATE
    post_mst
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    post_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    post_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    post_id = {0}
";

?>