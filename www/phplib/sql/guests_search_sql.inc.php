<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/guests_regist_common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(VHT.guest_id) AS list_count
FROM
    guest_head_tbl AS VHT
WHERE
    del_flg = '0'
    {COND}
";

$cmnSql['GETLIST'] = "
SELECT
    guest_id,
    guest_name,
    company_name,
    belong_name,
    telno,
    mac_addr,
    TO_CHAR(make_time, 'YYYY/MM/DD HH24:MI:SS') AS entry_time,
    make_id
FROM
    guest_head_tbl AS VHT
WHERE
    del_flg = '0'
    {COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";

$cmnSql['GET_PRINT_GUEST_DATA'] = "
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
    TO_CHAR(make_time, 'YYYY年MM月DD日 HH24時MI分SS秒') AS entry_time,
    TO_CHAR(make_time + '1 day', 'YYYY年FMMM月FMDD日 FMHH24時FMMI分FMSS秒') AS over_time,
    make_id
FROM
    guest_head_tbl
WHERE
    del_flg='0' AND
    guest_id = {0}
";


?>