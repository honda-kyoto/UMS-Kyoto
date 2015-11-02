<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/users_regist_common_sql.inc.php");

$cmnSql['GET_LAST_HISTORY_NO'] = "SELECT MAX(history_no) FROM user_history WHERE user_id = {0} AND (history_type = 'base' or history_type is null)";

$cmnSql['GET_USER_BASE_HISTORY_DATA'] = "
SELECT
    UM.staff_id,
    UM.staff_id_flg,
    UM.kanjisei,
    UM.kanjimei,
    UM.kanasei,
    UM.kanamei,
    UM.eijisei,
    UM.eijimei,
    UM.kanjisei_real,
    UM.kanjimei_real,
    UM.kanasei_real,
    UM.kanamei_real,
    UM.kyusei,
    UM.sex,
    UM.birthday,
    UM.belong_chg_id,
    BCM.belong_sec_id,
    BSM.belong_dep_id,
    BDM.belong_div_id,
    BVM.belong_class_id,
    UM.post_id,
    UM.job_id,
    UM.naisen,
    UM.pbno,
    UM.joukin_kbn,
    UM.note,
    TO_CHAR(UM.retire_date, 'YYYY/MM/DD') AS retire_date
FROM
    user_history AS UM
    LEFT OUTER JOIN belong_chg_mst AS BCM ON
        UM.belong_chg_id = BCM.belong_chg_id AND
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
WHERE
    UM.user_id = {0} AND
    UM.history_no = {1}
";


$cmnSql['GET_SUB_BELONG_HISTORY_DATA'] = "
SELECT
    USC.list_no,
    USC.belong_chg_id AS sub_belong_chg_id,
    BCM.belong_sec_id AS sub_belong_sec_id,
    BSM.belong_dep_id AS sub_belong_dep_id,
    BDM.belong_div_id AS sub_belong_div_id,
    BVM.belong_class_id AS sub_belong_class_id
FROM
    user_sub_chg_history AS USC
    LEFT OUTER JOIN belong_chg_mst AS BCM ON
        USC.belong_chg_id = BCM.belong_chg_id AND
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
WHERE
    USC.user_id = {0} AND
    USC.history_no = {1}
ORDER BY
    list_no
";

$cmnSql['GET_SUB_JOB_HISTORY_DATA'] = "
select
    list_no,
    job_id AS sub_job_id
FROM
    user_sub_job_history
WHERE
    user_id = {0} AND
    history_no = {1}
ORDER BY
    list_no
";

$cmnSql['GET_SUB_POST_HISTORY_DATA'] = "
select
    list_no,
    post_id AS sub_post_id
FROM
    user_sub_post_history
WHERE
    user_id = {0} AND
    history_no = {1}
ORDER BY
    list_no
";

$cmnSql['GET_SUB_STAFF_ID_HISTORY_DATA'] = "
select
    list_no,
    staff_id AS sub_staff_id
FROM
    user_sub_staff_id_history
WHERE
    user_id = {0} AND
    history_no = {1}
ORDER BY
    list_no
";

$cmnSql['GET_BASE_HISTORY_LIST'] = "
SELECT
    history_no,
    TO_CHAR(history_time, 'YYYY/MM/DD HH24:MI') AS history_time,
    update_id AS history_user_id
FROM
    user_history
WHERE
    user_id = {0} AND
    (history_type = 'base' or history_type is null)
ORDER BY
    history_no desc
";

?>