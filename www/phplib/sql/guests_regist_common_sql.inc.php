<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_GUEST_DATA'] = "
SELECT
    guest_id,
    guest_name,
    company_name,
    belong_name,
    telno,
    mac_addr,
    wireless_id,
    password,
    usage,
    note,
    CASE WHEN make_time >= current_timestamp + '-1 day' THEN '0' ELSE '1' END AS over_flg,
    TO_CHAR(make_time, 'YYYY/MM/DD HH24:MI:SS') AS entry_time,
    TO_CHAR(make_time + '1 day', 'YYYY/MM/DD HH24:MI:SS') AS over_time,
    make_id
FROM
    guest_head_tbl
WHERE
    del_flg='0' AND
    guest_id = {0}
";

$cmnSql['DELETE_GUEST_HEAD'] = "UPDATE guest_head_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE guest_id = {0}";



?>