<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(UM.user_id) AS list_count
FROM
    kyoto_user_card_tbl AS KUC
    INNER JOIN user_mst AS UM ON
        KUC.user_id = UM.user_id 
    INNER JOIN (
        SELECT
            UM.user_id,
            CASE
                WHEN BDM.belong_dep_name != '' THEN BDM.belong_dep_name
                WHEN BSM.belong_sec_name != '' THEN BSM.belong_sec_name
                WHEN BCM.belong_chg_name != '' THEN BCM.belong_chg_name
                WHEN BVM.belong_div_name != '' THEN BVM.belong_div_name
                WHEN BLM.belong_class_name != '' THEN BLM.belong_class_name
            END AS sort_belong_name
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
    ) SRT ON SRT.user_id = UM.user_id
{COND}
";

$cmnSql['GETLIST'] = "
SELECT
    UM.user_id,
    KUC.list_no,
    CASE WHEN KUC.card_type = '1' then substr(KUC.key_number, 12) ELSE substr(KUC.key_number, 11) END AS key_number,
    KUC.uid,
    KUC.belong_info_1,
    KUC.belong_info_2,
    KUC.belong_info_3,
    KUC.belong_info_4,
    KUC.reissue_flg,
    KUC.suspend_flg,
    KUC.update_id,
    KUC.del_flg,
    UM.kanjisei,
    UM.kanjimei,
    BCM.belong_chg_name,
    BSM.belong_sec_name,
    BDM.belong_dep_name,
    BVM.belong_div_name,
    BLM.belong_class_name,
    PSM.post_name,
    JBM.job_name,
    TO_CHAR(KUC.update_time, 'YYYY/MM/DD HH24:MI:SS') AS issue_time,
    KUC.update_time AS update_time,
    CASE WHEN KUC.make_time = KUC.update_time THEN '新規' ELSE '更新' END AS data_type_name
FROM
    kyoto_user_card_tbl AS KUC
    INNER JOIN user_mst AS UM ON
        KUC.user_id = UM.user_id
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
    LEFT OUTER JOIN post_mst AS PSM ON
        UM.post_id = PSM.post_id AND
        PSM.del_flg = '0'
    LEFT OUTER JOIN job_mst AS JBM ON
        UM.job_id = JBM.job_id AND
        JBM.del_flg = '0'
    INNER JOIN (
        SELECT
            UM.user_id,
            CASE
                WHEN BDM.belong_dep_name != '' THEN BDM.belong_dep_name
                WHEN BSM.belong_sec_name != '' THEN BSM.belong_sec_name
                WHEN BCM.belong_chg_name != '' THEN BCM.belong_chg_name
                WHEN BVM.belong_div_name != '' THEN BVM.belong_div_name
                WHEN BLM.belong_class_name != '' THEN BLM.belong_class_name
            END AS sort_belong_name
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
    ) SRT ON SRT.user_id = UM.user_id
{COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";


$cmnSql['GET_OUTPUT_DATA'] = "
SELECT
    card_type,
    permission_1,
    permission_2,
    permission_3,
    permission_4,
    permission_5,
    permission_6,
    permission_7,
    permission_8,
    permission_9,
    permission_10,
    permission_11,
    permission_12,
    permission_13,
    permission_14,
    permission_15,
    permission_16,
    permission_17,
    permission_18,
    permission_19,
    permission_20,
    permission_21,
    permission_22,
    permission_23,
    permission_24,
    permission_25,
    permission_26,
    permission_27,
    permission_28,
    permission_29,
    permission_30,
    permission_31,
    permission_32,
    permission_33,
    permission_34,
    permission_35,
    permission_36,
    permission_37,
    permission_38,
    permission_39,
    permission_40,
    belong_info_1,
    belong_info_2,
    belong_info_3,
    belong_info_4,
    key_number,
    uid,
    reissue_flg,
    TO_CHAR(start_date, 'YYYY/MM/DD') AS start_date,
    TO_CHAR(end_date, 'YYYY/MM/DD') AS end_date,
    suspend_flg,
    del_flg
FROM
    kyoto_user_card_tbl
WHERE
    user_id = {0} AND
    list_no = {1}
";


?>
