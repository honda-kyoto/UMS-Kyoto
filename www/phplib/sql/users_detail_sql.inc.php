<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/users_regist_common_sql.inc.php");


$cmnSql['GET_USER_DEFAULT_CTRL'] = "
SELECT
    UCM.mode_name,
    RTC.disp_type
FROM
    user_role_tbl AS URT,
    user_type_ctrl_tbl AS RTC,
    user_ctrl_mst AS UCM
WHERE
    URT.role_id = RTC.user_type_id AND
    RTC.ctrl_id = UCM.ctrl_id AND
    URT.user_id = {0} AND
    URT.role_id < 10 AND
    URT.del_flg = '0' AND
    RTC.default_flg = '1'
";

$cmnSql['GET_USER_CTRL_LIST'] = "
SELECT
    UCM.ctrl_id,
    UCM.mode_name,
    UCM.ctrl_name,
    RTC.disp_type
FROM
    user_role_tbl AS URT,
    user_type_ctrl_tbl AS RTC,
    user_ctrl_mst AS UCM
WHERE
    URT.role_id = RTC.user_type_id AND
    RTC.ctrl_id = UCM.ctrl_id AND
    URT.user_id = {0} AND
    URT.role_id < 10 AND
    URT.del_flg = '0'
order by
    UCM.disp_num
";

$cmnSql['GET_USER_BASE_DATA'] = "
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
    {MAIL_ACC},
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
    {REFLECT_DATE_COL}
FROM
    user_{TBL} AS UM
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
    UM.user_id = {0}
    {REFLECT_DATE_CND}
";


$cmnSql['GET_USER_NCVC_DATA'] = "
SELECT
    UM.login_id,
    UM.login_passwd,
    UM.eijisei,
    UM.eijimei,
    UM.mail_disused_flg,
    UM.mail_acc,
    TO_CHAR(UM.start_date, 'YYYY/MM/DD') AS start_date,
    TO_CHAR(UM.end_date, 'YYYY/MM/DD') AS end_date,
    CASE WHEN COALESCE(UM.end_date, now()::date) < (now() + '-6 months')::date THEN '1' ELSE '0' END AS mail_over_flg,
    UM.garoon_disused_flg,
    UM.mlist_disused_flg,
    UM.vdi_user_flg,
    UM.ftrans_user_flg,
    UM.ftrans_user_kbn
    {REFLECT_DATE_COL}
FROM
    user_{TBL} AS UM
WHERE
    UM.user_id = {0}
    {REFLECT_DATE_CND}
";

$cmnSql['EXISTS_USER_SENON_HEAD'] = "
SELECT
    sendon_type
FROM
    sendon_head_tbl
WHERE
	user_id = {0}
";

$cmnSql['EXISTS_SENDON_ADDR'] = "SELECT list_no FROM sendon_list_tbl WHERE user_id = {0} AND sendon_addr = '{1}' AND del_flg = '0'";

$cmnSql['INSERT_SENDON_TYPE'] = "INSERT INTO sendon_head_tbl (user_id, sendon_type, make_id, update_id) VALUES ({0}, {SENDON_TYPE}, {MAKE_ID}, {UPDATE_ID});";

$cmnSql['UPDATE_SENDON_TYPE'] = "UPDATE sendon_head_tbl SET sendon_type = {SENDON_TYPE}, del_flg = '0', update_time = now(), update_id = {UPDATE_ID} WHERE user_id = {0}";

$cmnSql['INSERT_SENDON_ADDR'] = "
INSERT INTO sendon_list_tbl (
user_id,
list_no,
sendon_addr,
make_id,
update_id
) VALUES (
{0},
(SELECT COALESCE(MAX(list_no), 0) + 1 FROM sendon_list_tbl WHERE user_id = {0}),
{SENDON_ADDR},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['DELETE_SENDON_ADDR'] = "UPDATE sendon_list_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE user_id = {0} AND list_no = {1}";

$cmnSql['EXISTS_OLDMAIL_ADDR'] = "SELECT list_no FROM oldmail_list_tbl WHERE user_id = {0} AND oldmail_addr = '{1}' AND del_flg = '0'";

$cmnSql['INSERT_OLDMAIL_ADDR'] = "
INSERT INTO oldmail_list_tbl (
user_id,
list_no,
oldmail_addr,
make_id,
update_id
) VALUES (
{0},
(SELECT COALESCE(MAX(list_no), 0) + 1 FROM oldmail_list_tbl WHERE user_id = {0}),
{OLDMAIL_ADDR},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['DELETE_OLDMAIL_ADDR'] = "UPDATE oldmail_list_tbl SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE user_id = {0} AND list_no = {1}";


$cmnSql['GET_MAIL_REISSUE_FLG'] = "
SELECT
    mail_reissue_flg
FROM
    user_mst
WHERE
    user_id = {0}
";


$cmnSql['GET_INVALID_ACC_DATA'] = "
SELECT
    mail_acc,
    invalid_flg,
    exception_note
FROM
    user_invalid_acc
WHERE
    user_id = {0} {COND}
";

$cmnSql['DELETE_INVALID_ACC'] = "UPDATE user_invalid_acc SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE user_id = {0}";

$cmnSql['INSERT_INVALID_ACC_FLG'] = "
INSERT INTO user_invalid_acc (
user_id,
mail_acc,
invalid_flg,
make_id,
update_id
)
VALUES
 (
{0},
(SELECT mail_acc FROM user_mst WHERE user_id = {0}),
'1',
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_INVALID_ACC_FLG'] = "
UPDATE
    user_invalid_acc
SET
    mail_acc = (SELECT mail_acc FROM user_mst WHERE user_id = {0}),
    invalid_flg = '1',
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['INSERT_INVALID_ACC_EXC'] = "
INSERT INTO user_invalid_acc (
user_id,
exception_note,
invalid_flg,
make_id,
update_id
)
VALUES
 (
{0},
'{1}',
'0',
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_INVALID_ACC_EXC'] = "
UPDATE
    user_invalid_acc
SET
    exception_note = '{1}',
    invalid_flg = '0',
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['SET_HIS_RETIRE_DATE'] = "
UPDATE
    user_his_tbl
SET
    validenddate = {VALIDENDDATE},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0} AND
    list_no = {1}
";

$cmnSql['USER_HIS_LOCK'] = "SELECT * FROM user_his_tbl WHERE user_id = {0} FOR UPDATE";
$cmnSql['GET_USER_HIS_LIST_NO'] = "SELECT COALESCE(MAX(list_no), -1) + 1 FROM user_his_tbl WHERE user_id = {0} AND del_flg = '0'";


$cmnSql['GET_USER_HIS_DATA'] = "
SELECT
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
    TO_CHAR(UHT.send_date, 'YYYY/MM/DD') AS send_date,
    UHT.update_id
    {HISTORY_COLUMNS}
FROM
    user_his_{TBL} AS UHT
        LEFT OUTER JOIN his_ward_mst AS HWM ON
            UHT.wardcode = HWM.wardcode
        LEFT OUTER JOIN his_profession_mst AS HPM ON
            UHT.professioncode = HPM.professioncode
        LEFT OUTER JOIN his_dept_mst AS HDM ON
            UHT.deptcode = HDM.deptcode
WHERE
    UHT.del_flg = '0' AND
    UHT.user_id = {0} AND
    UHT.list_no = {1}
";

$cmnSql['GET_HIS_HISTORY_LIST'] = "
SELECT
    history_no,
    his_history_kbn,
    history_note,
    TO_CHAR(send_date, 'YYYY/MM/DD') AS history_date,
    TO_CHAR(validstartdate, 'YYYY/MM/DD') AS validstartdate,
    TO_CHAR(validenddate, 'YYYY/MM/DD') AS validenddate,
    update_id AS history_user_id
FROM
    user_his_history_list
WHERE
    del_flg = '0' AND
    user_id = {0} AND
    list_no = {1}
ORDER BY
    history_no DESC
";

$cmnSql['GET_USER_HIS_HISTORY_DATA'] = "
SELECT
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
    TO_CHAR(UHT.send_date, 'YYYY/MM/DD') AS send_date,
    UHT.update_id,
    UHT.his_history_kbn,
    UHT.history_note
FROM
    user_his_history_list AS UHT
        LEFT OUTER JOIN his_ward_mst AS HWM ON
            UHT.wardcode = HWM.wardcode
        LEFT OUTER JOIN his_profession_mst AS HPM ON
            UHT.professioncode = HPM.professioncode
        LEFT OUTER JOIN his_dept_mst AS HDM ON
            UHT.deptcode = HDM.deptcode
WHERE
    UHT.del_flg = '0' AND
    UHT.user_id = {0} AND
    UHT.list_no = {1} AND
    UHT.history_no = {2}
";

$cmnSql['DELETE_USER_HIS_RESERVE'] = "UPDATE user_his_reserve SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE user_id = {0} AND list_no = {1}";

$cmnSql['EXISTS_SALARY_PASSWD'] = "SELECT history_no FROM user_salary_tbl WHERE del_flg = '0' AND user_id = {0} LIMIT 1";

$cmnSql['INSERT_USER_SALARY_PASSWD'] = "
INSERT INTO user_salary_tbl (
    user_id,
    history_no,
    salary_passwd,
    make_id,
    update_id
) VALUES (
    {0},
    (SELECT COALESCE(MAX(history_no), 0) + 1 FROM user_salary_tbl WHERE user_id = {0}),
    '{1}',
    {MAKE_ID},
    {UPDATE_ID}
)
";

$cmnSql['GET_HIS_VALID_LIST_NO'] = "
SELECT
    list_no
FROM
    user_his_tbl
WHERE
    del_flg = '0' AND
    user_id = {0} AND
    validenddate >= now()::date
ORDER BY
    list_no
";

$cmnSql['GET_HIS_LIST_NO'] = "
SELECT
    list_no
FROM
    (
        SELECT
            list_no
        FROM
            user_his_tbl
        WHERE
            del_flg = '0' AND
            user_id = {0}
        UNION
        SELECT
            list_no
        FROM
            user_his_reserve
        WHERE
            del_flg = '0' AND
            user_id = {0}
    ) AS list
ORDER BY
    list_no
";

$cmnSql['GET_KYOTO_CARD_LIST_NO'] = "SELECT list_no FROM kyoto_user_card_tbl WHERE user_id = {0} ORDER BY list_no";

$cmnSql['GET_SUB_BELONG_DATA'] = "
SELECT
    USC.list_no,
    USC.belong_chg_id AS sub_belong_chg_id,
    BCM.belong_sec_id AS sub_belong_sec_id,
    BSM.belong_dep_id AS sub_belong_dep_id,
    BDM.belong_div_id AS sub_belong_div_id,
    BVM.belong_class_id AS sub_belong_class_id
FROM
    user_sub_chg_{TBL} AS USC
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
    user_sub_job_{TBL}
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
    user_sub_post_{TBL}
WHERE
    user_id = {0} AND
    del_flg = '0'
ORDER BY
    list_no
";

$cmnSql['GET_SUB_STAFF_ID_DATA'] = "
select
    list_no,
    staff_id AS sub_staff_id
FROM
    user_sub_staff_id_{TBL}
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
    user_role_{TBL}
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
login_passwd_update_date,
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
ftrans_user_flg,
ftrans_user_kbn,
update_id,
history_type,
kanjisei_real,
kanjimei_real,
kanasei_real,
kanamei_real,
retire_date,
staff_id_flg
)
SELECT
    user_id,
    {1} AS history_no,
    staff_id,
    staffcode,
    login_id,
    login_passwd,
    login_passwd_update_date,
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
    ftrans_user_flg,
    ftrans_user_kbn,
    update_id,
    '{2}' AS history_type,
    kanjisei_real,
    kanjimei_real,
    kanasei_real,
    kanamei_real,
    retire_date,
    staff_id_flg
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

$cmnSql['MAKE_HISTORY_SUB_STAFF_ID'] = "
INSERT INTO user_sub_staff_id_history
(
user_id,
history_no,
list_no,
staff_id
)
SELECT
    user_id,
    {1} AS history_no,
    list_no,
    staff_id
FROM
    user_sub_staff_id_tbl
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
password_update_date,
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
    password_update_date,
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
    ftrans_user_flg = {FTRANS_USER_FLG},
    ftrans_user_kbn = {FTRANS_USER_KBN},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['EXISTS_USER_BASE_RESERVE'] = "SELECT user_id FROM user_base_reserve WHERE user_id = {0}";

$cmnSql['INSERT_USER_BASE_RESERVE'] = "
INSERT INTO user_base_reserve (
    user_id,
    staff_id,
    staff_id_flg,
    kanjisei,
    kanjimei,
    kanasei,
    kanamei,
    eijisei,
    eijimei,
    kanjisei_real,
    kanjimei_real,
    kanasei_real,
    kanamei_real,
    kyusei,
    sex,
    birthday,
    belong_chg_id,
    post_id,
    job_id,
    naisen,
    pbno,
    joukin_kbn,
    note,
    retire_date,
    make_id,
    update_id,
    reflect_date
)
VALUES
(
    {USER_ID},
    {STAFF_ID},
    {STAFF_ID_FLG},
    {KANJISEI},
    {KANJIMEI},
    {KANASEI},
    {KANAMEI},
    {EIJISEI},
    {EIJIMEI},
    {KANJISEI_REAL},
    {KANJIMEI_REAL},
    {KANASEI_REAL},
    {KANAMEI_REAL},
    {KYUSEI},
    {SEX},
    {BIRTHDAY},
    {BELONG_CHG_ID},
    {POST_ID},
    {JOB_ID},
    {NAISEN},
    {PBNO},
    {JOUKIN_KBN},
    {NOTE},
    {RETIRE_DATE},
    {MAKE_ID},
    {UPDATE_ID},
    {REFLECT_DATE}
)
";

$cmnSql['UPDATE_USER_BASE_DATA'] = "
UPDATE
    user_{TAB}
SET
    staff_id = {STAFF_ID},
    staff_id_flg = {STAFF_ID_FLG},
    kanjisei = {KANJISEI},
    kanjimei = {KANJIMEI},
    kanasei = {KANASEI},
    kanamei = {KANAMEI},
    eijisei = {EIJISEI},
    eijimei = {EIJIMEI},
    kanjisei_real = {KANJISEI_REAL},
    kanjimei_real = {KANJIMEI_REAL},
    kanasei_real = {KANASEI_REAL},
    kanamei_real = {KANAMEI_REAL},
    kyusei = {KYUSEI},
    sex = {SEX},
    birthday = {BIRTHDAY},
    belong_chg_id = {BELONG_CHG_ID},
    post_id = {POST_ID},
    job_id = {JOB_ID},
    naisen = {NAISEN},
    pbno = {PBNO},
    joukin_kbn = {JOUKIN_KBN},
    note = {NOTE},
    retire_date = {RETIRE_DATE},
    update_time = now(),
    update_id = {UPDATE_ID}
    {REFLECT_DATE_COL}
WHERE
    user_id = {USER_ID}
";

$cmnSql['DELETE_USER_BASE_RESERVE'] = "
UPDATE
    user_base_reserve
SET
    complete_flg = '9',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['DELETE_USER_NCVC_RESERVE'] = "
UPDATE
    user_ncvc_reserve
SET
    complete_flg = '9',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['EXISTS_USER_NCVC_RESERVE'] = "SELECT user_id FROM user_ncvc_reserve WHERE user_id = {0}";

$cmnSql['INSERT_USER_NCVC_RESERVE'] = "
INSERT INTO user_ncvc_reserve
(
    user_id,
    login_id,
    login_passwd,
    eijisei,
    eijimei,
    mail_disused_flg,
    mail_acc,
    start_date,
    end_date,
    garoon_disused_flg,
    mlist_disused_flg,
    vdi_user_flg,
    ftrans_user_flg,
    ftrans_user_kbn,
    make_id,
    update_id,
    reflect_date
)
VALUES
(
    {USER_ID},
    {LOGIN_ID},
    {LOGIN_PASSWD},
    {EIJISEI},
    {EIJIMEI},
    {MAIL_DISUSED_FLG},
    {MAIL_ACC},
    {START_DATE},
    {END_DATE},
    {GAROON_DISUSED_FLG},
    {MLIST_DISUSED_FLG},
    {VDI_USER_FLG},
    {FTRANS_USER_FLG},
    {FTRANS_USER_KBN},
    {MAKE_ID},
    {UPDATE_ID},
    {REFLECT_DATE}
)
";

$cmnSql['UPDATE_USER_NCVC_DATA'] = "
UPDATE
    user_{TAB}
SET
    login_id = {LOGIN_ID},
    login_passwd = {LOGIN_PASSWD},
    login_passwd_update_date = now()::date,
    eijisei = {EIJISEI},
    eijimei = {EIJIMEI},
    mail_disused_flg = {MAIL_DISUSED_FLG},
    mail_acc = {MAIL_ACC},
    start_date = {START_DATE},
    end_date = {END_DATE},
    garoon_disused_flg = {GAROON_DISUSED_FLG},
    mlist_disused_flg = {MLIST_DISUSED_FLG},
    vdi_user_flg = {VDI_USER_FLG},
    ftrans_user_flg = {FTRANS_USER_FLG},
    ftrans_user_kbn = {FTRANS_USER_KBN},
    update_time = now(),
    update_id = {UPDATE_ID}
    {REFLECT_DATE_COL}
WHERE
    user_id = {USER_ID}
";

$cmnSql['UPDATE_MAIL_REISSUE_FLG'] = "
UPDATE
    user_mst
SET
    mail_reissue_flg = {MAIL_REISSUE_FLG},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['DELETE_USER_SUB_BELONG_CHG'] = "
UPDATE
    user_sub_chg_{TAB}
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
    user_sub_chg_{TAB}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['INSERT_SUB_BELONG_CHG'] = "
INSERT INTO user_sub_chg_{TAB} (
user_id,
list_no,
belong_chg_id,
make_id,
update_id
)
VALUES
(
{USER_ID},
{LIST_NO},
{BELONG_CHG_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_SUB_BELONG_CHG'] = "
UPDATE
    user_sub_chg_{TAB}
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
    user_sub_job_{TAB}
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
    user_sub_job_{TAB}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['INSERT_SUB_JOB'] = "
INSERT INTO user_sub_job_{TAB} (
user_id,
list_no,
job_id,
make_id,
update_id
)
VALUES
(
{USER_ID},
{LIST_NO},
{JOB_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_SUB_JOB'] = "
UPDATE
    user_sub_job_{TAB}
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
    user_sub_post_{TAB}
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
    user_sub_post_{TAB}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['INSERT_SUB_POST'] = "
INSERT INTO user_sub_post_{TAB} (
user_id,
list_no,
post_id,
make_id,
update_id
)
VALUES
(
{USER_ID},
{LIST_NO},
{POST_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_SUB_POST'] = "
UPDATE
    user_sub_post_{TAB}
SET
    post_id = {POST_ID},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['DELETE_USER_SUB_STAFF_ID'] = "
UPDATE
    user_sub_staff_id_{TAB}
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID}
";

$cmnSql['EXISTS_SUB_STAFF_ID'] = "
SELECT
    list_no
FROM
    user_sub_staff_id_{TAB}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['INSERT_SUB_STAFF_ID'] = "
INSERT INTO user_sub_staff_id_{TAB} (
user_id,
list_no,
staff_id,
make_id,
update_id
)
VALUES
(
{USER_ID},
{LIST_NO},
{STAFF_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_SUB_STAFF_ID'] = "
UPDATE
    user_sub_staff_id_{TAB}
SET
    staff_id = {STAFF_ID},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['DELETE_USER_ROLE_DATA'] = "
UPDATE
    user_role_{TAB}
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
    user_role_{TAB}
WHERE
    user_id = {USER_ID} AND
    role_id = {ROLE_ID}
";

$cmnSql['INSERT_USER_ROLE'] = "
INSERT INTO user_role_{TAB} (
user_id,
role_id,
make_id,
update_id
)
VALUES
(
{USER_ID},
{ROLE_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_USER_ROLE'] = "
UPDATE
    user_role_{TAB}
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
    user_his_{TBL}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['UPDATE_USER_HIS_TBL'] = "
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

$cmnSql['UPDATE_USER_HIS_RESERVE'] = "
UPDATE
    user_his_reserve
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
    history_note = {HISTORY_NOTE},
    his_history_kbn = {HIS_HISTORY_KBN},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO}
";

$cmnSql['INSERT_USER_HIS_TBL'] = "
INSERT INTO user_his_tbl (
user_id,
list_no,
staffcode,
wardcode,
professioncode,
gradecode,
kananame,
kanjiname,
password,
password_update_date,
validstartdate,
validenddate,
deptcode,
appcode,
deptgroupcode,
send_date,
make_id,
update_id
)
VALUES
(
{USER_ID},
{LIST_NO},
{STAFFCODE},
{WARDCODE},
{PROFESSIONCODE},
{GRADECODE},
{KANANAME},
{KANJINAME},
{PASSWORD},
now()::date,
{VALIDSTARTDATE},
{VALIDENDDATE},
{DEPTCODE},
{APPCODE},
{DEPTGROUPCODE},
{SEND_DATE},
{MAKE_ID},
{UPDATE_ID}
)
";
$cmnSql['INSERT_USER_HIS_RESERVE'] = "
INSERT INTO user_his_reserve (
user_id,
list_no,
staffcode,
wardcode,
professioncode,
gradecode,
kananame,
kanjiname,
password,
password_update_date,
validstartdate,
validenddate,
deptcode,
appcode,
deptgroupcode,
send_date,
history_note,
his_history_kbn,
make_id,
update_id
)
VALUES
(
{USER_ID},
{LIST_NO},
{STAFFCODE},
{WARDCODE},
{PROFESSIONCODE},
{GRADECODE},
{KANANAME},
{KANJINAME},
{PASSWORD},
now()::date,
{VALIDSTARTDATE},
{VALIDENDDATE},
{DEPTCODE},
{APPCODE},
{DEPTGROUPCODE},
{SEND_DATE},
{HISTORY_NOTE},
{HIS_HISTORY_KBN},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['EXISTS_HIS_HISTORY_BASE'] = "SELECT history_no FROM user_his_history_list WHERE user_id = {0} AND list_no = {1} AND history_no = 0";

$cmnSql['INSERT_HIS_HISTORY_LIST'] = "
INSERT INTO user_his_history_list (
user_id,
list_no,
history_no,
his_history_kbn,
history_note,
staffcode,
wardcode,
professioncode,
gradecode,
kananame,
kanjiname,
validstartdate,
validenddate,
deptcode,
appcode,
deptgroupcode,
send_date,
make_id,
update_id
)
SELECT
  user_id,
  list_no,
  (SELECT COALESCE(MAX(history_no), 0) + 1 FROM user_his_history_list WHERE user_id = {0} AND list_no = {1}),
  {HIS_HISTORY_KBN},
  {HISTORY_NOTE},
  staffcode,
  wardcode,
  professioncode,
  gradecode,
  kananame,
  kanjiname,
  validstartdate,
  {VALIDENDDATE},
  deptcode,
  appcode,
  deptgroupcode,
  send_date,
  make_id,
  update_id
FROM
  user_his_tbl
WHERE
  user_id = {0} AND
  list_no = {1}
";

$cmnSql['UPDATE_USER_HIS_HISTORY_DATA'] = "
UPDATE
    user_his_history_list
SET
    history_note = {HISTORY_NOTE},
    his_history_kbn = {HIS_HISTORY_KBN},
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {USER_ID} AND
    list_no = {LIST_NO} AND
    history_no = {HISTORY_NO}
";

$cmnSql['UPDATE_LOGIN_PASSWD'] = "
UPDATE
    user_mst
SET
    login_passwd = '{1}',
    login_passwd_update_date = now()::date,
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
    password_update_date = now()::date,
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

$cmnSql['GET_USER_CARD_DATA'] = "
SELECT
    list_no,
    ident_code,
    issue_cnt,
    TO_CHAR(first_issue_date, 'YYYY/MM/DD') AS first_issue_date,
    TO_CHAR(last_issue_date, 'YYYY/MM/DD') AS last_issue_date,
    status,
    disuse_kbn
FROM
    user_card_head_tbl
WHERE
    user_id = {0} AND
    del_flg='0'
ORDER BY
    list_no
";


$cmnSql['INSERT_USER_CARD_HEAD'] = "
INSERT INTO user_card_head_tbl (
user_id,
list_no,
ident_code,
make_id,
update_id
)
VALUES
(
{0},
{1},
'{2}',
{MAKE_ID},
{UPDATE_ID}
)
";


$cmnSql['INSERT_USER_CARD_LIST_FIRST'] = "
INSERT INTO user_card_list_tbl (
user_id,
list_no,
make_id,
update_id
)
VALUES
(
{0},
{1},
{MAKE_ID},
{UPDATE_ID}
)
";


$cmnSql['INSERT_USER_CARD_DATA'] = "
INSERT INTO user_card_tbl (
user_id,
list_no,
card_no,
make_id,
update_id
)
VALUES
(
{0},
(SELECT COALESCE(MAX(list_no), 0) + 1 FROM user_card_tbl WHERE user_id = {0}),
'{1}',
{MAKE_ID},
{UPDATE_ID}
)
";


$cmnSql['UPDATE_USER_CARD_ISSUE_CNT'] = "
UPDATE
    user_card_head_tbl
SET
    issue_cnt = issue_cnt + 1,
    last_issue_date = now()::date,
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0} AND
    list_no = {1}
";


$cmnSql['INSERT_USER_CARD_LIST_REISSUE'] = "
INSERT INTO user_card_list_tbl (
user_id,
list_no,
issue_cnt,
reissue_kbn,
make_id,
update_id
)
VALUES
(
{0},
{1},
(SELECT issue_cnt FROM user_card_head_tbl WHERE user_id = {0} AND list_no = {1}),
'{2}',
{MAKE_ID},
{UPDATE_ID}
)
";


$cmnSql['UPDATE_USER_CARD_DATA'] = "
UPDATE
    user_card_head_tbl
SET
    status = '9',
    disuse_kbn = '{2}',
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0} AND
    list_no = {1}
";

$cmnSql['EXISTS_CARD_NO'] = "SELECT card_no FROM user_card_tbl WHERE card_no = '{0}' AND del_flg = '0'";


$cmnSql['UPDATE_HIS_LIST_NO'] = "UPDATE {TABLE} SET list_no = {2} WHERE user_id = {0} AND list_no = {1}";
$cmnSql['SHIFT_HIS_LIST_NO'] = "UPDATE {TABLE} SET list_no = list_no + 1 WHERE user_id = {0} AND list_no >= 0 AND list_no < {1}";



$cmnSql['GET_KYOTO_CARD_DATA'] = "
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


$cmnSql['KYOTO_CARD_LOCK'] = "SELECT * FROM kyoto_user_card_tbl WHERE user_id = {0} FOR UPDATE";
$cmnSql['GET_KYOTO_CARD_NEW_LIST_NO'] = "SELECT COALESCE(MAX(list_no), -1) + 1 FROM kyoto_user_card_tbl WHERE user_id = {0}";


$cmnSql['INSERT_KYOTO_CARD_DATA'] = "
INSERT INTO kyoto_user_card_tbl (
user_id,
list_no,
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
start_date,
end_date,
suspend_flg,
del_flg,
make_id,
update_id
)
VALUES
(
{0},
{1},
{CARD_TYPE},
{PERMISSION_1},
{PERMISSION_2},
{PERMISSION_3},
{PERMISSION_4},
{PERMISSION_5},
{PERMISSION_6},
{PERMISSION_7},
{PERMISSION_8},
{PERMISSION_9},
{PERMISSION_10},
{PERMISSION_11},
{PERMISSION_12},
{PERMISSION_13},
{PERMISSION_14},
{PERMISSION_15},
{PERMISSION_16},
{PERMISSION_17},
{PERMISSION_18},
{PERMISSION_19},
{PERMISSION_20},
{PERMISSION_21},
{PERMISSION_22},
{PERMISSION_23},
{PERMISSION_24},
{PERMISSION_25},
{PERMISSION_26},
{PERMISSION_27},
{PERMISSION_28},
{PERMISSION_29},
{PERMISSION_30},
{PERMISSION_31},
{PERMISSION_32},
{PERMISSION_33},
{PERMISSION_34},
{PERMISSION_35},
{PERMISSION_36},
{PERMISSION_37},
{PERMISSION_38},
{PERMISSION_39},
{PERMISSION_40},
{BELONG_INFO_1},
{BELONG_INFO_2},
{BELONG_INFO_3},
{BELONG_INFO_4},
{KEY_NUMBER},
{UID},
{REISSUE_FLG},
{START_DATE},
{END_DATE},
{SUSPEND_FLG},
{DEL_FLG},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_KYOTO_CARD_DATA'] = "
UPDATE
  kyoto_user_card_tbl
SET
  card_type = {CARD_TYPE},
  permission_1 = {PERMISSION_1},
  permission_2 = {PERMISSION_2},
  permission_3 = {PERMISSION_3},
  permission_4 = {PERMISSION_4},
  permission_5 = {PERMISSION_5},
  permission_6 = {PERMISSION_6},
  permission_7 = {PERMISSION_7},
  permission_8 = {PERMISSION_8},
  permission_9 = {PERMISSION_9},
  permission_10 = {PERMISSION_10},
  permission_11 = {PERMISSION_11},
  permission_12 = {PERMISSION_12},
  permission_13 = {PERMISSION_13},
  permission_14 = {PERMISSION_14},
  permission_15 = {PERMISSION_15},
  permission_16 = {PERMISSION_16},
  permission_17 = {PERMISSION_17},
  permission_18 = {PERMISSION_18},
  permission_19 = {PERMISSION_19},
  permission_20 = {PERMISSION_20},
  permission_21 = {PERMISSION_21},
  permission_22 = {PERMISSION_22},
  permission_23 = {PERMISSION_23},
  permission_24 = {PERMISSION_24},
  permission_25 = {PERMISSION_25},
  permission_26 = {PERMISSION_26},
  permission_27 = {PERMISSION_27},
  permission_28 = {PERMISSION_28},
  permission_29 = {PERMISSION_29},
  permission_30 = {PERMISSION_30},
  permission_31 = {PERMISSION_31},
  permission_32 = {PERMISSION_32},
  permission_33 = {PERMISSION_33},
  permission_34 = {PERMISSION_34},
  permission_35 = {PERMISSION_35},
  permission_36 = {PERMISSION_36},
  permission_37 = {PERMISSION_37},
  permission_38 = {PERMISSION_38},
  permission_39 = {PERMISSION_39},
  permission_40 = {PERMISSION_40},
  belong_info_1 = {BELONG_INFO_1},
  belong_info_2 = {BELONG_INFO_2},
  belong_info_3 = {BELONG_INFO_3},
  belong_info_4 = {BELONG_INFO_4},
  key_number = {KEY_NUMBER},
  uid = {UID},
  reissue_flg = {REISSUE_FLG},
  start_date = {START_DATE},
  end_date = {END_DATE},
  suspend_flg = {SUSPEND_FLG},
  del_flg = {DEL_FLG},
  update_time = now(),
  update_id = {UPDATE_ID}
WHERE
  user_id = {0} AND
  list_no = {1}
";

$cmnSql['UPDATE_KYOTO_CARD_LIST_NO'] = "UPDATE kyoto_user_card_tbl SET list_no = {2} WHERE user_id = {0} AND list_no = {1}";

$cmnSql['INSERT_KYOTO_CARD_DATA_RESERVE'] = "
INSERT INTO kyoto_user_card_reserve (
user_id,
list_no,
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
start_date,
end_date,
suspend_flg,
del_flg,
make_id,
update_id,
send_date
)
VALUES
(
{0},
{1},
{CARD_TYPE},
{PERMISSION_1},
{PERMISSION_2},
{PERMISSION_3},
{PERMISSION_4},
{PERMISSION_5},
{PERMISSION_6},
{PERMISSION_7},
{PERMISSION_8},
{PERMISSION_9},
{PERMISSION_10},
{PERMISSION_11},
{PERMISSION_12},
{PERMISSION_13},
{PERMISSION_14},
{PERMISSION_15},
{PERMISSION_16},
{PERMISSION_17},
{PERMISSION_18},
{PERMISSION_19},
{PERMISSION_20},
{PERMISSION_21},
{PERMISSION_22},
{PERMISSION_23},
{PERMISSION_24},
{PERMISSION_25},
{PERMISSION_26},
{PERMISSION_27},
{PERMISSION_28},
{PERMISSION_29},
{PERMISSION_30},
{PERMISSION_31},
{PERMISSION_32},
{PERMISSION_33},
{PERMISSION_34},
{PERMISSION_35},
{PERMISSION_36},
{PERMISSION_37},
{PERMISSION_38},
{PERMISSION_39},
{PERMISSION_40},
{BELONG_INFO_1},
{BELONG_INFO_2},
{BELONG_INFO_3},
{BELONG_INFO_4},
{KEY_NUMBER},
{UID},
{REISSUE_FLG},
{START_DATE},
{END_DATE},
{SUSPEND_FLG},
{DEL_FLG},
{MAKE_ID},
{UPDATE_ID},
{SEND_DATE}
)
";

$cmnSql['UPDATE_KYOTO_CARD_DATA_RESERVE'] = "
UPDATE
  kyoto_user_card_reserve
SET
  card_type = {CARD_TYPE},
  permission_1 = {PERMISSION_1},
  permission_2 = {PERMISSION_2},
  permission_3 = {PERMISSION_3},
  permission_4 = {PERMISSION_4},
  permission_5 = {PERMISSION_5},
  permission_6 = {PERMISSION_6},
  permission_7 = {PERMISSION_7},
  permission_8 = {PERMISSION_8},
  permission_9 = {PERMISSION_9},
  permission_10 = {PERMISSION_10},
  permission_11 = {PERMISSION_11},
  permission_12 = {PERMISSION_12},
  permission_13 = {PERMISSION_13},
  permission_14 = {PERMISSION_14},
  permission_15 = {PERMISSION_15},
  permission_16 = {PERMISSION_16},
  permission_17 = {PERMISSION_17},
  permission_18 = {PERMISSION_18},
  permission_19 = {PERMISSION_19},
  permission_20 = {PERMISSION_20},
  permission_21 = {PERMISSION_21},
  permission_22 = {PERMISSION_22},
  permission_23 = {PERMISSION_23},
  permission_24 = {PERMISSION_24},
  permission_25 = {PERMISSION_25},
  permission_26 = {PERMISSION_26},
  permission_27 = {PERMISSION_27},
  permission_28 = {PERMISSION_28},
  permission_29 = {PERMISSION_29},
  permission_30 = {PERMISSION_30},
  permission_31 = {PERMISSION_31},
  permission_32 = {PERMISSION_32},
  permission_33 = {PERMISSION_33},
  permission_34 = {PERMISSION_34},
  permission_35 = {PERMISSION_35},
  permission_36 = {PERMISSION_36},
  permission_37 = {PERMISSION_37},
  permission_38 = {PERMISSION_38},
  permission_39 = {PERMISSION_39},
  permission_40 = {PERMISSION_40},
  belong_info_1 = {BELONG_INFO_1},
  belong_info_2 = {BELONG_INFO_2},
  belong_info_3 = {BELONG_INFO_3},
  belong_info_4 = {BELONG_INFO_4},
  key_number = {KEY_NUMBER},
  uid = {UID},
  reissue_flg = {REISSUE_FLG},
  start_date = {START_DATE},
  end_date = {END_DATE},
  suspend_flg = {SUSPEND_FLG},
  del_flg = {DEL_FLG},
  update_time = now(),
  update_id = {UPDATE_ID},
  send_date = {SEND_DATE}
WHERE
  user_id = {0} AND
  list_no = {1}
";

$cmnSql['GET_KYOTO_CARD_RESERVE'] = "SELECT * FROM kyoto_user_card_reserve WHERE user_id = {0} FOR UPDATE";

?>