<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/users_regist_common_sql.inc.php");

$cmnSql['GET_SUB_BELONG_DATA'] = "
SELECT
    USC.list_no,
    USC.belong_chg_id AS sub_belong_chg_id,
    BCM.belong_sec_id AS sub_belong_sec_id,
    BSM.belong_dep_id AS sub_belong_dep_id,
    BDM.belong_div_id AS sub_belong_div_id,
    BVM.belong_class_id AS sub_belong_class_id
FROM
    user_sub_chg_tbl AS USC
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
    USC.del_flg = '0'
ORDER BY
    list_no
";

$cmnSql['GET_SUB_JOB_DATA'] = "
select
    list_no,
    job_id AS sub_job_id
FROM
    user_sub_job_tbl
WHERE
    user_id = {0} AND
    del_flg = '0'
ORDER BY
    list_no
";

$cmnSql['GET_SUB_POST_DATA'] = "
select
    list_no,
    post_id AS sub_post_id
FROM
    user_sub_post_tbl
WHERE
    user_id = {0} AND
    del_flg = '0'
ORDER BY
    list_no
";

$cmnSql['GET_SUB_HIS_DATA'] = "
SELECT
    UHT.list_no,
    UHT.staffcode AS sub_staffcode,
    HWM.wardstatus AS sub_wardstatus,
    UHT.wardcode AS sub_wardcode,
    HPM.professionstatus AS sub_professionstatus,
    UHT.professioncode AS sub_professioncode,
    UHT.gradecode AS sub_gradecode,
    UHT.kananame AS sub_kananame,
    UHT.kanjiname AS sub_kanjiname,
    UHT.password AS sub_password,
    TO_CHAR(UHT.validstartdate, 'YYYY/MM/DD') AS sub_validstartdate,
    TO_CHAR(UHT.validenddate, 'YYYY/MM/DD') AS sub_validenddate,
    HDM.deptstatus AS sub_deptstatus,
    UHT.deptcode AS sub_deptcode,
    UHT.appcode AS sub_appcode,
    UHT.deptgroupcode AS sub_deptgroupcode,
    TO_CHAR(UHT.send_date, 'YYYY/MM/DD') AS sub_send_date
FROM
    user_his_tbl AS UHT
        LEFT OUTER JOIN his_ward_mst AS HWM ON
            UHT.wardcode = HWM.wardcode
        LEFT OUTER JOIN his_profession_mst AS HPM ON
            UHT.professioncode = HPM.professioncode
        LEFT OUTER JOIN his_dept_mst AS HDM ON
            UHT.deptcode = HDM.deptcode
WHERE
    UHT.list_no != 0 AND
    UHT.del_flg = '0' AND
    UHT.user_id = {0}
ORDER BY
    UHT.list_no
";

$cmnSql['GET_ROLE_DATA'] = "
SELECT
    role_id
FROM
    user_role_tbl
WHERE
    del_flg = '0' AND
    role_id < 100 AND
    user_id = {0}
 ORDER BY
    role_id
";

$cmnSql['HISTORY_LOCK'] = "SELECT * FROM user_history WHERE user_id = {0} FOR UPDATE";
$cmnSql['GET_HISTORY_NO'] = "SELECT COALESCE(MAX(history_no), 0) + 1 FROM user_history WHERE user_id = {0}";

$cmnSql['MAKE_HISTORY_USER_MST'] = "
INSERT INTO user_history  (
user_id,
history_no,
staff_id,
staffcode,
login_id,
login_passwd,
kanjisei,
kanjimei,
kanasei,
kanamei,
eijisei,
eijimei,
kyusei,
sex,
birthday,
mail_acc,
belong_chg_id,
post_id,
job_id,
naisen,
pbno,
joukin_kbn,
start_date,
end_date,
note,
retire_flg,
mail_disused_flg,
garoon_disused_flg,
mlist_disused_flg,
vdi_user_flg,
update_id
)
SELECT
    user_id,
    {1} AS history_no,
    staff_id,
    staffcode,
    login_id,
    login_passwd,
    kanjisei,
    kanjimei,
    kanasei,
    kanamei,
    eijisei,
    eijimei,
    kyusei,
    sex,
    birthday,
    mail_acc,
    belong_chg_id,
    post_id,
    job_id,
    naisen,
    pbno,
    joukin_kbn,
    start_date,
    end_date,
    note,
    retire_flg,
    mail_disused_flg,
    garoon_disused_flg,
    mlist_disused_flg,
    vdi_user_flg,
    update_id
FROM
    user_mst
WHERE
    user_id = {0}
";

$cmnSql['MAKE_HISTORY_SUB_CHG'] = "
INSERT INTO user_sub_chg_history
(
user_id,
history_no,
list_no,
belong_chg_id
)
SELECT
    user_id,
    {1} AS history_no,
    list_no,
    belong_chg_id
FROM
    user_sub_chg_tbl
WHERE
    del_flg = '0' AND
    user_id = {0}
";

$cmnSql['MAKE_HISTORY_SUB_JOB'] = "
INSERT INTO user_sub_job_history
(
user_id,
history_no,
list_no,
job_id
)
SELECT
    user_id,
    {1} AS history_no,
    list_no,
    job_id
FROM
    user_sub_job_tbl
WHERE
    del_flg = '0' AND
    user_id = {0}
";

$cmnSql['MAKE_HISTORY_SUB_POST'] = "
INSERT INTO user_sub_post_history
(
user_id,
history_no,
list_no,
post_id
)
SELECT
    user_id,
    {1} AS history_no,
    list_no,
    post_id
FROM
    user_sub_post_tbl
WHERE
    del_flg = '0' AND
    user_id = {0}
";

$cmnSql['MAKE_HISTORY_USER_HIS'] = "
INSERT INTO user_his_history
(
user_id,
history_no,
list_no,
staffcode,
wardcode,
professioncode,
gradecode,
kananame,
kanjiname,
password,
validstartdate,
validenddate,
deptcode,
appcode,
deptgroupcode,
send_date
)
SELECT
    user_id,
    {1} AS history_no,
    list_no,
    staffcode,
    wardcode,
    professioncode,
    gradecode,
    kananame,
    kanjiname,
    password,
    validstartdate,
    validenddate,
    deptcode,
    appcode,
    deptgroupcode,
    send_date
FROM
    user_his_tbl
WHERE
    del_flg = '0' AND
    user_id = {0}
";

$cmnSql['MAKE_HISTORY_USER_ROLE'] = "
INSERT INTO user_role_history
(
user_id,
history_no,
role_id
)
SELECT
    user_id,
    {1} AS history_no,
    role_id
FROM
    user_role_tbl
WHERE
    del_flg = '0' AND
    user_id = {0}
";

$cmnSql['UPDATE_USER_MST'] = "
UPDATE
    user_mst
SET
    staff_id = {STAFF_ID},
    staffcode = {STAFFCODE},
    login_id = {LOGIN_ID},
    kanjisei = {KANJISEI},
    kanjimei = {KANJIMEI},
    kanasei = {KANASEI},
    kanamei = {KANAMEI},
    eijisei = {EIJISEI},
    eijimei = {EIJIMEI},
    kyusei = {KYUSEI},
    sex = {SEX},
    birthday = {BIRTHDAY},
    mail_disused_flg = {MAIL_DISUSED_FLG},
    mail_acc = {MAIL_ACC},
    belong_chg_id = {BELONG_CHG_ID},
    post_id = {POST_ID},
    job_id = {JOB_ID},
    naisen = {NAISEN},
    pbno = {PBNO},
    joukin_kbn = {JOUKIN_KBN},
    start_date = {START_DATE},
    end_date = {END_DATE},
    note = {NOTE},
    retire_flg = {RETIRE_FLG},
    garoon_disused_flg = {GAROON_DISUSED_FLG},
    mlist_disused_flg = {MLIST_DISUSED_FLG},
    vdi_user_flg = {VDI_USER_FLG},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['UPDATE_USER_MST_WITH_PASSWD'] = "
UPDATE
    user_mst
SET
    staff_id = {STAFF_ID},
    staffcode = {STAFFCODE},
    login_id = {LOGIN_ID},
    login_passwd = {LOGIN_PASSWD},
    kanjisei = {KANJISEI},
    kanjimei = {KANJIMEI},
    kanasei = {KANASEI},
    kanamei = {KANAMEI},
    eijisei = {EIJISEI},
    eijimei = {EIJIMEI},
    kyusei = {KYUSEI},
    sex = {SEX},
    birthday = {BIRTHDAY},
    mail_disused_flg = {MAIL_DISUSED_FLG},
    mail_acc = {MAIL_ACC},
    belong_chg_id = {BELONG_CHG_ID},
    post_id = {POST_ID},
    job_id = {JOB_ID},
    naisen = {NAISEN},
    pbno = {PBNO},
    joukin_kbn = {JOUKIN_KBN},
    start_date = {START_DATE},
    end_date = {END_DATE},
    note = {NOTE},
    retire_flg = {RETIRE_FLG},
    garoon_disused_flg = {GAROON_DISUSED_FLG},
    mlist_disused_flg = {MLIST_DISUSED_FLG},
    vdi_user_flg = {VDI_USER_FLG},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['DELETE_USER_SUB_BELONG_CHG'] = "
UPDATE
    user_sub_chg_tbl
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['EXISTS_SUB_BELONG_CHG'] = "
SELECT
    list_no
FROM
    user_sub_chg_tbl
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['UPDATE_SUB_BELONG_CHG'] = "
UPDATE
    user_sub_chg_tbl
SET
    belong_chg_id = {BELONG_CHG_ID},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['DELETE_USER_SUB_JOB'] = "
UPDATE
    user_sub_job_tbl
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['EXISTS_SUB_JOB'] = "
SELECT
    list_no
FROM
    user_sub_job_tbl
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['UPDATE_SUB_JOB'] = "
UPDATE
    user_sub_job_tbl
SET
    job_id = {JOB_ID},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['DELETE_USER_SUB_POST'] = "
UPDATE
    user_sub_post_tbl
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['EXISTS_SUB_POST'] = "
SELECT
    list_no
FROM
    user_sub_post_tbl
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['UPDATE_SUB_POST'] = "
UPDATE
    user_sub_post_tbl
SET
    post_id = {POST_ID},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['DELETE_USER_ROLE_DATA'] = "
UPDATE
    user_role_tbl
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    role_id < 100
";

$cmnSql['EXISTS_USER_ROLE'] = "
SELECT
    role_id
FROM
    user_role_tbl
WHERE
    user_id = {USER_ID} AND
    role_id = {ROLE_ID}
";

$cmnSql['UPDATE_USER_ROLE'] = "
UPDATE
    user_role_tbl
SET
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    role_id = {ROLE_ID}
";

$cmnSql['DELETE_USER_HIS'] = "
UPDATE
    user_his_tbl
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['EXISTS_USER_HIS'] = "
SELECT
    list_no
FROM
    user_his_tbl
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['UPDATE_USER_HIS'] = "
UPDATE
    user_his_tbl
SET
    staffcode = {STAFFCODE},
    wardcode = {WARDCODE},
    professioncode = {PROFESSIONCODE},
    gradecode = {GRADECODE},
    kananame = {KANANAME},
    kanjiname = {KANJINAME},
    validstartdate = {VALIDSTARTDATE},
    validenddate = {VALIDENDDATE},
    deptcode = {DEPTCODE},
    appcode = {APPCODE},
    deptgroupcode = {DEPTGROUPCODE},
    send_date = {SEND_DATE},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";


$cmnSql['UPDATE_LOGIN_PASSWD'] = "
UPDATE
    user_mst
SET
    login_passwd = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['UPDATE_HIS_PASSWORD'] = "
UPDATE
    user_his_tbl
SET
    password = '{1}',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0} AND
    list_no = {2}
";

$cmnSql['DELETE_HIDDEN_ADDR'] = "
UPDATE
    oldmail_list_tbl
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0} AND
    oldmail_addr = '{1}' AND
    hidden_flg = '1'
";

$cmnSql['GET_NCVC_ID_DATA'] = "
SELECT
    UM.login_id                       AS login_id,
    UM.login_passwd                   AS login_passwd,
    UM.kanjisei || '　' || UM.kanjimei AS kanjiname,
    BDM.belong_dep_name               AS belong_dep_name,
    BSM.belong_sec_name               AS belong_sec_name,
    BCM.belong_chg_name               AS belong_chg_name,
    BVM.belong_div_name               AS belong_div_name,
    BLM.belong_class_name             AS belong_class_name,
    JOB.job_name                      AS job_name
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
    LEFT OUTER JOIN job_mst AS JOB ON
        JOB.job_id  = UM.job_id AND
        JOB.del_flg = '0'
WHERE
    UM.user_id = {0}
";

$cmnSql['GET_JUN_HIS_DATA'] = "
SELECT
    UHT.list_no,
    UHT.staffcode  AS staffcode,
    HWM.wardstatus AS wardstatus,
    HWM.wardname   AS wardname,
    UHT.kanjiname  AS kanjiname,
    UHT.password   AS password
FROM
    user_his_tbl AS UHT
        LEFT OUTER JOIN his_ward_mst AS HWM ON
            UHT.wardcode = HWM.wardcode
WHERE
    UHT.del_flg = '0' AND
    UHT.user_id = {0} AND
    UHT.list_no = {1}
";
?>