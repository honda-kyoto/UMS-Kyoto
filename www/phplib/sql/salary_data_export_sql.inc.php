<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : sumio imoto
* Date         : 2013.05.23
* Last Update  : 2013.05.23
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_SALARY_LAST_OUTPUT'] = "SELECT TO_CHAR(salary_last_output_time, 'YYYY/MM/DD') AS salary_last_output_time FROM system_setting_mst";

$cmnSql['UPDATE_SALARY_LAST_OUTPUT'] = "
UPDATE
    system_setting_mst
SET
    salary_last_output_time = now()
";

$cmnSql['INSERT_SALARY_LAST_OUTPUT'] = "INSERT INTO system_setting_mst (salary_last_output_time) VALUES (now())";

$cmnSql['GET_SALARY_OUTPUT_DATA'] = "
SELECT 
    SUBSTR(UM.staff_id, 2)                          AS salary_no,
    UM.login_id,
    UM.kanjisei,
    UM.kanjimei,
    UST.salary_passwd,
    TO_CHAR(UST.make_time, 'YYYY/MM/DD HH24:MI:SS') AS make_time,
    UST.history_no
FROM 
    user_mst UM,
    user_salary_tbl UST,
    (
    SELECT DISTINCT
        UST.user_id,
        MAX(UST.history_no) OVER (PARTITION BY UST.user_id) AS history_no
    FROM
        user_salary_tbl UST
        {COND}
    ) LST
WHERE 
    UM.user_id     = UST.user_id AND 
    UST.user_id    = LST.user_id AND 
    UST.history_no = LST.history_no
ORDER BY
    UST.make_time desc
";
?>