<?php
/**********************************************************
* File         : tag_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT tag_id FROM tag_mst WHERE del_flg = '0' AND tag_name = '{0}' {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO tag_mst (
tag_id,
tag_name,
tag_uid,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('tag_id_seq'),
'{0}',
'{1}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM tag_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    tag_mst
SET
    tag_name = '{1}',
    tag_uid = '{2}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    tag_id = {0}
";

$cmnSql['DELETE_DATA'] = "
DELETE
FROM
    tag_mst
WHERE
    tag_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    tag_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    tag_id = {0}
";

?>