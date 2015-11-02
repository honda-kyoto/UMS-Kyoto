<?php
/**********************************************************
* File         : common_sql.inc.php
* Authors      : mie tsutsui
* Date         : 2011.07.27
* Last Update  : 2011.07.27
* Copyright    :
***********************************************************/

$cmnSql = array();

$cmnSql['GET_VLAN_RIDGE_ARY'] = "SELECT vlan_ridge_id, vlan_ridge_name FROM vlan_ridge_mst WHERE del_flg = '0' ORDER BY disp_num";
$cmnSql['GET_VLAN_FLOOR_ARY'] = "SELECT vlan_floor_id, vlan_floor_name FROM vlan_floor_mst WHERE del_flg = '0' AND vlan_ridge_id = {0} ORDER BY disp_num";
$cmnSql['GET_VLAN_ROOM_ARY'] = "SELECT vlan_room_id, vlan_room_name FROM vlan_room_mst WHERE del_flg = '0' AND vlan_floor_id = {0} ORDER BY disp_num";
$cmnSql['GET_VLAN_ARY'] = "SELECT vlan_id, admin_name FROM vlan_mst WHERE del_flg = '0' AND vlan_room_id = {0} ORDER BY disp_num";
$cmnSql['GET_APP_TYPE_ARY'] = "SELECT app_type_id, app_type_name FROM app_type_mst WHERE del_flg = '0' ORDER BY disp_num";


$cmnSql['GET_BELONG_CLASS_ARY'] = "SELECT belong_class_id, belong_class_name FROM belong_class_mst WHERE del_flg = '0' ORDER BY disp_num";

$cmnSql['GET_BELONG_DIV_ARY_ALL'] = "SELECT belong_div_id, belong_div_name FROM belong_div_mst WHERE del_flg = '0' ORDER BY disp_num";
$cmnSql['GET_BELONG_DIV_ARY'] = "SELECT belong_div_id, belong_div_name FROM belong_div_mst WHERE del_flg = '0' AND belong_class_id = {0} ORDER BY disp_num";

$cmnSql['GET_BELONG_DEP_ARY_ALL'] = "SELECT belong_dep_id, belong_dep_name FROM belong_dep_mst WHERE del_flg = '0' ORDER BY disp_num";
$cmnSql['GET_BELONG_DEP_ARY'] = "SELECT belong_dep_id, belong_dep_name FROM belong_dep_mst WHERE del_flg = '0' AND belong_div_id = {0} ORDER BY disp_num";

$cmnSql['GET_BELONG_SEC_ARY_ALL'] = "SELECT belong_sec_id, belong_sec_name FROM belong_sec_mst WHERE del_flg = '0' ORDER BY disp_num";
$cmnSql['GET_BELONG_SEC_ARY'] = "SELECT belong_sec_id, belong_sec_name FROM belong_sec_mst WHERE del_flg = '0' AND belong_dep_id = {0} ORDER BY disp_num";

$cmnSql['GET_BELONG_CHG_ARY_ALL'] = "SELECT belong_chg_id, belong_chg_name FROM belong_chg_mst WHERE del_flg = '0' ORDER BY disp_num";
$cmnSql['GET_BELONG_CHG_ARY'] = "SELECT belong_chg_id, belong_chg_name FROM belong_chg_mst WHERE del_flg = '0' AND belong_sec_id = {0} ORDER BY disp_num";

$cmnSql['GET_JOB_ARY'] = "SELECT job_id, job_name FROM job_mst WHERE del_flg = '0' ORDER BY disp_num";
$cmnSql['GET_POST_ARY'] = "SELECT post_id, post_name FROM post_mst WHERE del_flg = '0' ORDER BY disp_num";
$cmnSql['GET_CARD_ARY'] = "SELECT C.card_id, C.card_name, C.card_uid, C.num FROM (SELECT row_number() OVER () AS num , * FROM (SELECT * FROM card_mst ORDER BY card_name) AS t) AS C WHERE num < 50";
$cmnSql['GET_TAG_ARY'] = "SELECT C.tag_id, C.tag_name, C.tag_uid, C.num FROM (SELECT row_number() OVER () AS num , * FROM (SELECT * FROM tag_mst ORDER BY tag_name) AS t) AS C WHERE num < 50";

$cmnSql['GET_USER_TYPE_ARY'] = "SELECT user_type_id, user_type_name FROM user_type_mst ORDER BY disp_num";
$cmnSql['GET_USER_ROLE_ARY'] = "SELECT user_role_id, user_role_name FROM user_role_mst ORDER BY disp_num";

$cmnSql['GET_WARD_ARY_ALL'] = "SELECT wardcode,wardname FROM his_ward_mst ORDER BY wardstatus, wardseq";
$cmnSql['GET_WARD_ARY'] = "SELECT wardcode,wardname FROM his_ward_mst WHERE wardstatus = {0} ORDER BY wardseq";

$cmnSql['GET_PROFESSION_ARY_ALL'] = "SELECT professioncode,professionname FROM his_profession_mst ORDER BY professioncode";
$cmnSql['GET_PROFESSION_ARY'] = "SELECT professioncode,professionname FROM his_profession_mst WHERE professionstatus = {0} ORDER BY professioncode";

$cmnSql['GET_GRADE_ARY'] = "SELECT gradecode,gradename FROM his_grade_mst ORDER BY gradecode";

$cmnSql['GET_DEPT_ARY_ALL'] = "SELECT deptcode,deptname FROM his_dept_mst ORDER BY deptseq";
$cmnSql['GET_DEPT_ARY'] = "SELECT deptcode,deptname FROM his_dept_mst WHERE deptstatus = {0} ORDER BY deptseq";

$cmnSql['GET_DEPTGROUP_ARY_ALL'] = "
SELECT
    DG.deptgroupcode,
    DG.deptgroupname
FROM
    his_deptgroup_mst AS DG,
    his_dept_mst AS DP
WHERE
    DG.deptcode = DP.deptcode
ORDER BY
    DP.deptseq,
    DG.deptgroupseq
";
$cmnSql['GET_DEPTGROUP_ARY'] = "
SELECT
    deptgroupcode,
    deptgroupname
FROM
    his_deptgroup_mst
WHERE
    deptcode = '{0}'
ORDER BY
    deptgroupseq
";

$cmnSql['EXISTS_MAIL_ACC'] = "
SELECT
    user_id
FROM
    (
        SELECT user_id, mail_acc, start_date, end_date FROM user_mst
        UNION ALL
        SELECT user_id, mail_acc, start_date, end_date FROM user_ncvc_reserve WHERE complete_flg = '0'
        UNION ALL
        SELECT user_id, mail_acc, now()::date, now()::date FROM user_invalid_acc WHERE invalid_flg = '1' AND del_flg = '0'
    ) AS USR
WHERE
    mail_acc = '{0}' AND
    start_date <= now()::date AND
    COALESCE(end_date, now()::date) >= (now() + '-6 months')::date
    {COND}
LIMIT 1
";

$cmnSql['EXISTS_OLDMAIL_ACC'] = "
SELECT
    user_id
FROM
    oldmail_list_tbl
WHERE
    oldmail_addr ~ '^[^@]+@([^\\\\.]+\\\\.)*" . substr(USER_MAIL_DOMAIN, 1) . "$' AND
    substring(oldmail_addr from 1 for position('@' in oldmail_addr)-1) = '{0}'
LIMIT 1
";

$cmnSql['EXISTS_MLIST_ACC'] = "
SELECT
    mlist_id
FROM
    (
        SELECT mlist_id, mlist_acc, del_flg FROM mlist_head_tbl
        UNION ALL
        SELECT mlist_id, mlist_acc, del_flg FROM mlist_head_entry WHERE entry_status = '0'
    ) AS MHT
WHERE
    mlist_acc = '{0}' AND
    del_flg = '0'
    {COND}
LIMIT 1
";

$cmnSql['EXISTS_LOGIN_ID'] = "SELECT user_id FROM user_mst WHERE login_id = '{0}' AND start_date <= now()::date AND COALESCE(end_date, now()::date) >= now()::date AND retire_flg = '0' {COND}";

$cmnSql['GET_USER_SENON_HEAD'] = "
SELECT
    sendon_type
FROM
    sendon_head_tbl
WHERE
    del_flg = '0' AND
	user_id = {0}
";

$cmnSql['GET_USER_SENON_LIST'] = "
SELECT
    list_no,
    sendon_addr
FROM
    sendon_list_tbl
WHERE
    del_flg = '0' AND
    user_id = {0}
ORDER BY
    list_no
";

$cmnSql['GET_USER_OLDMAIL_LIST'] = "
SELECT
    list_no,
    oldmail_addr
FROM
    oldmail_list_tbl
WHERE
    del_flg = '0' AND
    user_id = {0}
    {COND}
ORDER BY
    list_no
";

$cmnSql['GET_USER_WIRELESS_ID'] = "SELECT wireless_id FROM user_wireless_id WHERE del_flg = '0' AND user_id = {0}";

$cmnSql['GET_USER_DATA'] = "
SELECT
    UM.staff_id,
    UM.staffcode,
    UM.login_id,
    UM.login_passwd,
    UM.kanjisei,
    UM.kanjimei,
    UM.kanasei,
    UM.kanamei,
    UM.eijisei,
    UM.eijimei,
    UM.kyusei,
    UM.sex,
    UM.birthday,
    UM.mail_disused_flg,
    UM.mail_acc,
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
    TO_CHAR(UM.start_date, 'YYYY/MM/DD') AS start_date,
    TO_CHAR(UM.end_date, 'YYYY/MM/DD') AS end_date,
    UM.note,
    UM.retire_flg,
    UM.garoon_disused_flg,
    UM.mlist_disused_flg,
    UM.vdi_user_flg,
    UM.ftrans_user_flg,
    UM.ftrans_user_kbn,
    UHT.staffcode,
    HWM.wardstatus,
    UHT.wardcode,
    HPM.professionstatus,
    UHT.professioncode,
    UHT.gradecode,
    UHT.kananame,
    UHT.kanjiname,
    UHT.password,
    TO_CHAR(UHT.validstartdate, 'YYYY/MM/DD') AS validstartdate,
    TO_CHAR(UHT.validenddate, 'YYYY/MM/DD') AS validenddate,
    HDM.deptstatus,
    UHT.deptcode,
    UHT.appcode,
    UHT.deptgroupcode,
    TO_CHAR(UHT.send_date, 'YYYY/MM/DD') AS send_date
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
    LEFT OUTER JOIN user_his_tbl AS UHT ON
        UM.user_id = UHT.user_id AND
        UHT.list_no = 0 AND
        UHT.del_flg = '0'
    LEFT OUTER JOIN his_ward_mst AS HWM ON
        UHT.wardcode = HWM.wardcode
    LEFT OUTER JOIN his_profession_mst AS HPM ON
        UHT.professioncode = HPM.professioncode
    LEFT OUTER JOIN his_dept_mst AS HDM ON
        UHT.deptcode = HDM.deptcode
WHERE
    UM.user_id = {0}
";

$cmnSql['GET_MLIST_ACC'] = "
SELECT
    mlist_acc
FROM
    mlist_head_tbl
WHERE
    mlist_id = {0}
";

$cmnSql['UPDATE_USER_ENTRY_FLG'] = "
UPDATE
    user_entry_tbl
SET
    {COL} = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['CMN_EXISTS_USER_ROLE'] = "SELECT del_flg FROM user_role_tbl WHERE user_id = {0} AND role_id = {1}";
$cmnSql['CMN_INSERT_USER_ROLE'] = "INSERT INTO user_role_tbl (user_id, role_id, make_id, update_id) VALUES ({0}, {1}, {MAKE_ID}, {UPDATE_ID})";
$cmnSql['CMN_UPDATE_USER_ROLE'] = "UPDATE user_role_tbl SET del_flg = '0' WHERE user_id = {0} AND role_id = {1}";

$cmnSql['GET_BELONGS_BY_CHG'] = "
SELECT
    BLM.belong_class_id,
    BLM.belong_class_name,
    BVM.belong_div_id,
    BVM.belong_div_name,
    BDM.belong_dep_id,
    BDM.belong_dep_name,
    BSM.belong_sec_id,
    BSM.belong_sec_name,
    BCM.belong_chg_id,
    BCM.belong_chg_name
FROM
    belong_chg_mst AS BCM
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
WHERE
        BCM.belong_chg_id = {0} AND
        BCM.del_flg = '0'
";

$cmnSql['GET_BELONGS_BY_SEC'] = "
SELECT
    BLM.belong_class_id,
    BLM.belong_class_name,
    BVM.belong_div_id,
    BVM.belong_div_name,
    BDM.belong_dep_id,
    BDM.belong_dep_name,
    BSM.belong_sec_id,
    BSM.belong_sec_name
FROM
    belong_sec_mst AS BSM
    LEFT OUTER JOIN belong_dep_mst AS BDM ON
        BSM.belong_dep_id = BDM.belong_dep_id AND
        BDM.del_flg = '0'
    LEFT OUTER JOIN belong_div_mst AS BVM ON
        BDM.belong_div_id = BVM.belong_div_id AND
        BVM.del_flg = '0'
    LEFT OUTER JOIN belong_class_mst AS BLM ON
        BVM.belong_class_id = BLM.belong_class_id AND
        BLM.del_flg = '0'
WHERE
        BSM.belong_sec_id = {0} AND
        BSM.del_flg = '0'
";

$cmnSql['GET_BELONG_BY_DEP'] = "
SELECT
    BLM.belong_class_id,
    BLM.belong_class_name,
    BVM.belong_div_id,
    BVM.belong_div_name,
    BDM.belong_dep_id,
    BDM.belong_dep_name
FROM
    belong_dep_mst AS BDM
    LEFT OUTER JOIN belong_div_mst AS BVM ON
        BDM.belong_div_id = BVM.belong_div_id AND
        BVM.del_flg = '0'
    LEFT OUTER JOIN belong_class_mst AS BLM ON
        BVM.belong_class_id = BLM.belong_class_id AND
        BLM.del_flg = '0'
WHERE
        BDM.belong_dep_id = {0} AND
        BDM.del_flg = '0'
";

$cmnSql['GET_BELONGS_BY_DIV'] = "
SELECT
    BLM.belong_class_id,
    BLM.belong_class_name,
    BVM.belong_div_id,
    BVM.belong_div_name
FROM
    belong_div_mst AS BVM
    LEFT OUTER JOIN belong_class_mst AS BLM ON
        BVM.belong_class_id = BLM.belong_class_id AND
        BLM.del_flg = '0'
WHERE
        BVM.belong_div_id = {0} AND
        BVM.del_flg = '0'
";

$cmnSql['GET_BELONGS_BY_CLASS'] = "
SELECT
    belong_class_id,
    belong_class_name
FROM
    belong_class_mst
WHERE
    belong_class_id = {0} AND
    del_flg = '0'
";

$cmnSql['GET_USER_TYPE_ID'] = "
SELECT
    role_id
FROM
    user_role_tbl AS URT
WHERE
    del_flg = '0' AND
    exists (SELECT
                *
            FROM
                user_type_mst
            WHERE
                URT.role_id = user_type_id
            ) AND
    user_id = {0}
";

$cmnSql['GET_VLAN_NAME'] = "SELECT vlan_name FROM vlan_mst WHERE vlan_id = {0}";

$cmnSql['GET_VLAN_ADMIN_ID'] = "
SELECT
    user_id
FROM
    vlan_admin_list
WHERE
    del_flg = '0' AND
    vlan_id = {0}
";

$cmnSql['GET_VLAN_AREA_NAME'] = "
SELECT
    VDM.vlan_ridge_id,
    VDM.vlan_ridge_name,
    VFM.vlan_floor_id,
    VFM.vlan_floor_name,
    VRM.vlan_room_id,
    VRM.vlan_room_name,
    VLM.admin_name,
    VLM.vlan_name
FROM
    vlan_mst AS VLM,
    vlan_room_mst AS VRM,
    vlan_floor_mst AS VFM,
    vlan_ridge_mst AS VDM
WHERE
    VLM.vlan_room_id = VRM.vlan_room_id AND
    VRM.vlan_floor_id = VFM.vlan_floor_id AND
    VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
    VLM.del_flg = '0' AND
    VRM.del_flg = '0' AND
    VFM.del_flg = '0' AND
    VDM.del_flg = '0' AND
    VLM.vlan_id = {0}
";

$cmnSql['GET_VLAN_ROOM_NAME'] = "
SELECT
    VDM.vlan_ridge_id,
    VDM.vlan_ridge_name,
    VFM.vlan_floor_id,
    VFM.vlan_floor_name,
    VRM.vlan_room_id,
    VRM.vlan_room_name
FROM
    vlan_room_mst AS VRM,
    vlan_floor_mst AS VFM,
    vlan_ridge_mst AS VDM
WHERE
    VRM.vlan_floor_id = VFM.vlan_floor_id AND
    VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
    VRM.del_flg = '0' AND
    VFM.del_flg = '0' AND
    VDM.del_flg = '0' AND
    VRM.vlan_room_id = {0}
";

$cmnSql['GET_VLAN_FLOOR_NAME'] = "
SELECT
    VDM.vlan_ridge_id,
    VDM.vlan_ridge_name,
    VFM.vlan_floor_id,
    VFM.vlan_floor_name
FROM
    vlan_floor_mst AS VFM,
    vlan_ridge_mst AS VDM
WHERE
    VFM.vlan_ridge_id = VDM.vlan_ridge_id AND
    VFM.del_flg = '0' AND
    VDM.del_flg = '0' AND
    VFM.vlan_floor_id = {0}
";

$cmnSql['HAS_ADMIN_ACT_TYPE'] = "
SELECT
    count(*)
FROM
    user_mst UM,
    user_role_tbl UR,
    role_menu_mst RM,
    menu_mst MM
WHERE
    UM.user_id = UR.user_id AND
    UR.role_id = RM.role_id AND
    RM.menu_id = MM.menu_id AND
    UM.user_id = {0} AND
    UR.del_flg = '0' AND
    RM.act_type = '1' AND
    MM.script_name = '{1}'
";

$cmnSql['GET_APP_TYPE_KBNS'] = "SELECT wire_kbn, ip_kbn FROM app_type_mst WHERE app_type_id = {0}";

$cmnSql['GET_WIRELESS_ID_LIST'] = "
SELECT
    wireless_id
FROM
    app_head_tbl AS APP
WHERE
    del_flg = '0' AND
    app_user_id = {0}
UNION
SELECT
    wireless_id
FROM
    user_wireless_id
WHERE
    del_flg = '0' AND
    user_id = {0}
";



$cmnSql['UPDATE_MAILERR_COMPLETE_FLG'] = "UPDATE user_mail_errlog SET complete_flg = '1' WHERE login_id = '{0}' AND complete_flg = '0'";

$cmnSql['EXISTS_MAILERR_USER'] = "SELECT log_cd FROM user_mail_errlog WHERE user_id = {0} AND kbn = '{1}' AND complete_flg = '0'";

$cmnSql['INSERT_MAILERR'] = "INSERT INTO user_mail_errlog (log_cd, log_time, user_id, login_id, kbn) VALUES ('{0}', now(), {1}, '{2}', '{3}')";

$cmnSql['EXISTS_VLAN_ADMIN_LIST'] = "SELECT COUNT(user_id) FROM vlan_admin_list WHERE del_flg = '0' AND user_id = {0}";

$cmnSql['EXISTS_USER_AD_ERR'] = "SELECT log_cd FROM user_ad_errlog WHERE user_id = {0} AND complete_flg = '0'";

$cmnSql['INSERT_USER_AD_ERR'] = "INSERT INTO user_ad_errlog (log_cd, log_time, user_id) VALUES ('{0}', now(), {1})";


$cmnSql['GET_TERMINAL_DEVICE_CNT'] = "
SELECT
    COUNT(terminal_id)
FROM
    sbc_terminal_device_tbl
WHERE
    device_id = {0}
";

$cmnSql['GET_USER_DEVICE_CNT'] = "
SELECT
    COUNT(user_id)
FROM
    sbc_user_device_tbl
WHERE
    device_id = {0}
";

$cmnSql['GET_AD_REL_USER_DATA'] = "
SELECT
    UM.login_id,
    UM.kanjisei,
    UM.kanjimei,
    UM.kanjisei || ' ' || UM.kanjimei as kanjiname,
    UM.kanasei,
    UM.kanamei,
    UM.kanasei || ' ' || UM.kanamei as kananame,
    UM.login_passwd,
    UM.mail_acc || case when UM.mail_acc is not null then '" . USER_MAIL_DOMAIN . "' else '' end as mail_addr,
    PM.post_name,
    JM.job_name,
    UM.naisen,
    UM.pbno,
    bc.belong_class_name||bv.belong_div_name||bp.belong_dep_name||bs.belong_sec_name||bg.belong_chg_name as belong_name
FROM
    user_mst as UM
	left outer join post_mst as PM on UM.post_id = PM.post_id and PM.del_flg = '0'
	left outer join belong_chg_mst bg ON um.belong_chg_id = bg.belong_chg_id AND bg.del_flg::text = '0'::text
	left outer join belong_sec_mst bs ON bg.belong_sec_id = bs.belong_sec_id AND bs.del_flg::text = '0'::text
    left outer join belong_dep_mst bp ON bs.belong_dep_id = bp.belong_dep_id AND bp.del_flg::text = '0'::text
 	left outer join belong_div_mst bv ON bp.belong_div_id = bv.belong_div_id AND bv.del_flg::text = '0'::text
	left outer join belong_class_mst bc ON bv.belong_class_id = bc.belong_class_id AND bc.del_flg::text = '0'::text
 	left outer join job_mst as JM on UM.job_id = JM.job_id and JM.del_flg = '0'
WHERE
    UM.user_id = {0}
ORDER BY
    UM.login_id
";

?>