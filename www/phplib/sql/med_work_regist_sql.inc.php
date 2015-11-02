<?php
/**
 * med_work_regist_sql.inc.php
 * 
 * @author      hiroyuki honda
 * @date        2015-10-21
 * @copyright   
 * @version     1.0.0
 */

require_once("sql/common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(UM.user_id) AS list_count
FROM
    user_mst AS UM
{COND}
";

$cmnSql['GETLIST'] = "
SELECT
    UM.user_id,
    UM.login_id,
    UM.staff_id,
    UM.kanjisei,
    UM.kanjimei,
    UM.kanasei,
    UM.kanamei,
    BCM.belong_chg_name,
    BSM.belong_sec_name,
    BDM.belong_dep_name,
    BVM.belong_div_name,
    BLM.belong_class_name,
    PSM.post_name,
    JBM.job_name,
    TO_CHAR(UM.end_date, 'YYYY/MM/DD') AS end_date,
    UM.retire_flg,
    CASE WHEN UM.start_date > now()::date THEN '1' ELSE '0' END AS until_flg,
    CASE WHEN COALESCE(UM.end_date, now()::date) < now()::date THEN '1' ELSE '0' END AS over_flg
FROM
    user_mst AS UM
    LEFT OUTER JOIN kyoto_user_card_tbl AS CARD ON
        UM.user_id = CARD.user_id AND
        CARD.list_no = (select MIN(list_no) from kyoto_user_card_tbl where UM.user_id = user_id and del_flg = '0' ) AND
        CARD.del_flg = '0'
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
    {ORDER} {DESC} , UM.staff_id desc
LIMIT {LIMIT} OFFSET {OFFSET}
";

?>
