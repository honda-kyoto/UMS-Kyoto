<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['LOGINAUTH'] = "SELECT user_id FROM user_mst WHERE login_id = '{0}' AND login_passwd = '{1}' AND start_date <= now()::date AND COALESCE(end_date, now()::date) >= now()::date";
$cmnSql['LOGINADAUTH'] = "SELECT user_id FROM user_mst WHERE login_id = '{0}' AND start_date <= now()::date AND COALESCE(end_date, now()::date) >= now()::date";
$cmnSql['LOGINUSER'] = "
SELECT
    USM.login_id,
    USM.staffcode,
    USM.kanjisei,
    USM.kanjimei,
    BCM.belong_chg_name,
    BSM.belong_sec_name,
    BDM.belong_dep_name,
    BVM.belong_div_name,
    BLM.belong_class_name,
    PSM.post_name,
    JBM.job_name,
    TO_CHAR(USM.last_logintime, 'YYYY/MM/DD HH24:MI') AS last_logintime
FROM
    user_mst AS USM
    LEFT OUTER JOIN belong_chg_mst AS BCM ON
        USM.belong_chg_id = BCM.belong_chg_id AND
        BCM.del_flg = '0'
    LEFT OUTER JOIN belong_sec_mst AS BSM ON
        BCM.belong_sec_id = BSM.belong_sec_id AND
        BSM.del_flg = '0'
    LEFT OUTER JOIN belong_dep_mst AS BDM ON
        BSM.belong_dep_id = BDM.belong_dep_id AND
        BDM.del_flg = '0'
    LEFT OUTER JOIN belong_div_mst AS BVM ON
        BDM.belong_div_id = BVM.belong_div_id AND
        BVM.del_flg = '0'
    LEFT OUTER JOIN belong_class_mst AS BLM ON
        BVM.belong_class_id = BLM.belong_class_id AND
        BLM.del_flg = '0'
    LEFT OUTER JOIN post_mst AS PSM ON
        USM.post_id = PSM.post_id AND
        PSM.del_flg = '0'
    LEFT OUTER JOIN job_mst AS JBM ON
        USM.job_id = JBM.job_id AND
        JBM.del_flg = '0'
WHERE
    USM.user_id = {0}
";

$cmnSql['UPDATE_LAST_LOGINTIME'] = "UPDATE user_mst SET last_logintime = now() WHERE user_id = {0}";

?>