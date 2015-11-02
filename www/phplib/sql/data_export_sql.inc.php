<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_TARGET_LIST'] = "SELECT user_role_id, user_role_name FROM user_role_mst {COND} ORDER BY disp_num";
$cmnSql['GET_TARGET_DATA'] = "SELECT user_role_name, mode_name FROM user_role_mst WHERE user_role_id = {0}";

$cmnSql['GET_LIBRARY_OUTPUT_DATA'] = "SELECT * FROM library_user_view WHERE  to_date(validenddate, 'YYYYMMDD') >= now()::date";

$cmnSql['GET_ATTEND_OUTPUT_DATA'] = "SELECT * FROM garoon_user_view WHERE staffcode != '' AND staffcode IS NOT NULL";

$cmnSql['GET_OPERATOR_OUTPUT_DATA'] = "SELECT * FROM user_operator_view";

$cmnSql['GET_ELEARNING_OUTPUT_DATA'] = "
SELECT
    UM.login_id,
    UM.kanasei,
    UM.kanamei,
    UM.kanjisei,
    UM.kanjimei,
    BLM.belong_class_name,
    BVM.belong_div_name,
    BDM.belong_dep_name,
    BSM.belong_sec_name,
    BCM.belong_chg_name,
    JBM.job_name,
    PTM.post_name,
    UM.mail_acc,
    TO_CHAR(UM.start_date, 'YYYY/MM/DD') AS start_date,
    TO_CHAR(UM.end_date, 'YYYY/MM/DD') AS end_date
FROM
    user_mst AS UM
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
    LEFT OUTER JOIN belong_class_mst AS BLM ON
        BVM.belong_class_id = BLM.belong_class_id AND
        BLM.del_flg = '0'
    LEFT OUTER JOIN job_mst AS JBM ON
        UM.job_id = JBM.job_id AND
        JBM.del_flg = '0'
    LEFT OUTER JOIN post_mst AS PTM ON
        UM.post_id = PTM.post_id AND
        PTM.del_flg = '0'
WHERE
    UM.login_id is not null
";

$cmnSql['GET_USER_CARD_OUTPUT_DATA'] = "
SELECT
    UC.ident_code,
    UM.kanjisei,
    UM.kanjimei,
    UM.kanasei,
    UM.kanamei,
    UM.eijimei,
    UM.eijisei,
    TO_CHAR(UM.birthday, 'YYYYMMDD') AS birthday,
    UM.sex,
    TO_CHAR(UC.first_issue_date, 'YYYYMMDD') AS first_issue_date,
    COALESCE(TO_CHAR(UM.end_date, 'YYYYMMDD'), '20990331') AS end_date,
    UC.issue_cnt,
    BL.last_name AS belong_name,
    PM.post_name,
    JM.job_name
FROM
    user_card_head_tbl AS UC
    INNER JOIN user_mst AS UM ON
        UC.user_id = UM.user_id
        {JOUKIN_KBN}
    INNER JOIN garoon_belong_last_id AS BL ON
        UM.belong_chg_id = BL.belong_chg_id
    LEFT OUTER JOIN post_mst AS PM ON
        UM.post_id = PM.post_id AND
        PM.del_flg = '0'
    LEFT OUTER JOIN job_mst AS JM ON
        UM.job_id = JM.job_id AND
        JM.del_flg = '0'
ORDER BY
    UC.first_issue_date
";

$cmnSql['GET_USER_KAKENHI_OUTPUT_DATA'] = "
select
USM.login_id,
USM.kanjisei,
USM.kanjimei,
USM.kanasei,
USM.kanamei,
USM.sex,
BLM.belong_class_name,
BVM.belong_div_name,
BDM.belong_dep_name,
BSM.belong_sec_name,
BCM.belong_chg_name,
JBM.job_name,
PTM.post_name,
TO_CHAR(USM.start_date, 'YYYYMMDD') AS start_date,
COALESCE(TO_CHAR(USM.end_date, 'YYYYMMDD'), '2099/12/31') AS end_date
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
LEFT OUTER JOIN job_mst AS JBM ON
USM.job_id = JBM.job_id AND
JBM.del_flg = '0'
LEFT OUTER JOIN post_mst AS PTM ON
USM.post_id = PTM.post_id AND
PTM.del_flg = '0'
";

?>