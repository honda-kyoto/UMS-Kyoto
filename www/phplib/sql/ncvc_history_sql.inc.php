<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/users_regist_common_sql.inc.php");

$cmnSql['GET_LAST_HISTORY_NO'] = "SELECT MAX(history_no) FROM user_history WHERE user_id = {0} AND (history_type = 'ncvc' or history_type is null)";

$cmnSql['GET_USER_NCVC_HISTORY_DATA'] = "
SELECT
    login_id,
    login_passwd,
    eijisei,
    eijimei,
    mail_disused_flg,
    mail_acc,
    TO_CHAR(start_date, 'YYYY/MM/DD') AS start_date,
    TO_CHAR(end_date, 'YYYY/MM/DD') AS end_date,
    garoon_disused_flg,
    mlist_disused_flg,
    vdi_user_flg,
    ftrans_user_flg,
    ftrans_user_kbn
FROM
    user_history
WHERE
    user_id = {0} AND
    history_no = {1}
";

$cmnSql['GET_ROLE_HISTORY_DATA'] = "
SELECT
    role_id
FROM
    user_role_history
WHERE
    role_id < 100 AND
    user_id = {0} AND
    history_no = {1}
 ORDER BY
    role_id
";


$cmnSql['GET_NCVC_HISTORY_LIST'] = "
SELECT
    history_no,
    TO_CHAR(history_time, 'YYYY/MM/DD HH24:MI') AS history_time,
    update_id AS history_user_id
FROM
    user_history
WHERE
    user_id = {0} AND
    (history_type = 'ncvc' or history_type is null)
ORDER BY
    history_no desc
";

?>