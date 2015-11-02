<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['ADD_OLDMAIL_LIST'] = "
INSERT INTO oldmail_list_tbl (
user_id,
list_no,
oldmail_addr,
make_id,
update_id
)
VALUES
 (
{0},
(SELECT COALESCE(MAX(list_no), 0) + 1 FROM oldmail_list_tbl WHERE user_id = {0}),
'{1}',
{MAKE_ID},
{UPDATE_ID}
)
";


$cmnSql['REISSUE_MAIL_ACC'] = "
UPDATE
    user_mst
SET
    mail_acc = {MAIL_ACC},
    mail_reissue_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";


?>