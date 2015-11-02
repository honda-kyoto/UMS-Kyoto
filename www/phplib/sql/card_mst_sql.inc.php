<?php
/**********************************************************
* File         : card_mst_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['NAME_EXISTS'] = "SELECT card_id FROM card_mst WHERE del_flg = '0' AND card_name = '{0}' {COND}";

$cmnSql['INSERT_DATA'] = "
INSERT INTO card_mst (
card_id,
card_name,
card_uid,
disp_num,
make_id,
update_id
)
VALUES
(
nextval('card_id_seq'),
'{0}',
'{1}',
(SELECT COALESCE(MAX(disp_num),0) + 1 FROM card_mst ),
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_DATA'] = "
UPDATE
    card_mst
SET
    card_name = '{1}',
    card_uid = '{2}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    card_id = {0}
";

$cmnSql['DELETE_DATA'] = "
DELETE
FROM
    card_mst
WHERE
    card_id = {0}
";

$cmnSql['UPDATE_DISPNUM'] = "
UPDATE
    card_mst
SET
    disp_num = {1},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    card_id = {0}
";

?>