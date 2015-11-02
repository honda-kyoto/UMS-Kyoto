<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['EXISTS_STAFF_ID'] = "SELECT user_id FROM user_mst WHERE staff_id = '{0}' AND start_date <= now()::date AND COALESCE(end_date, now()::date) >= now()::date AND retire_flg = '0' {COND}";

$cmnSql['EXISTS_STAFFCODE'] = "SELECT user_id FROM user_his_tbl WHERE staffcode = '{0}' AND del_flg = '0' AND validstartdate <= now()::date AND validenddate >= now()::date {COND}";


$cmnSql['URC_INSERT_USER_MST'] = "
INSERT INTO user_mst (
user_id,
staff_id,
login_id,
login_passwd,
login_passwd_update_date,
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
mail_disused_flg,
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
garoon_disused_flg,
mlist_disused_flg,
vdi_user_flg,
ftrans_user_flg,
ftrans_user_kbn,
staff_id_flg,
make_id,
update_id
)
VALUES
(
{USER_ID},
{STAFF_ID},
{LOGIN_ID},
{LOGIN_PASSWD},
now()::date,
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
{MAIL_DISUSED_FLG},
{MAIL_ACC},
{BELONG_CHG_ID},
{POST_ID},
{JOB_ID},
{NAISEN},
{PBNO},
{JOUKIN_KBN},
{START_DATE},
{END_DATE},
{NOTE},
{GAROON_DISUSED_FLG},
{MLIST_DISUSED_FLG},
{VDI_USER_FLG},
{FTRANS_USER_FLG},
{FTRANS_USER_KBN},
{STAFF_ID_FLG},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['URC_INSERT_SUB_BELONG_CHG'] = "
INSERT INTO user_sub_chg_tbl (
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

$cmnSql['URC_INSERT_SUB_JOB'] = "
INSERT INTO user_sub_job_tbl (
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

$cmnSql['URC_INSERT_SUB_POST'] = "
INSERT INTO user_sub_post_tbl (
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

$cmnSql['URC_INSERT_SUB_STAFF_ID'] = "
INSERT INTO user_sub_staff_id_tbl (
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

$cmnSql['URC_INSERT_USER_ENTRY'] = "
INSERT INTO user_entry_tbl (
user_id,
make_id,
update_id
)
VALUES
(
{USER_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['URC_INSERT_USER_ROLE'] = "
INSERT INTO user_role_tbl (
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

$cmnSql['INSERT_USER_HIS'] = "
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

$cmnSql['GET_COND_CHANGED_MLIST_ID'] = "
SELECT
    mlist_id
FROM
    mlist_auto_cond_list
WHERE
    del_flg='0'
    {COND}
";

$cmnSql['URC_EXISTS_OLDMAIL_ADDR'] = "SELECT oldmail_addr FROM oldmail_list_tbl WHERE user_id = {0} AND oldmail_addr = '{1}' AND del_flg = '0'";

$cmnSql['INSERT_HIDDEN_ADDR'] = "
INSERT INTO oldmail_list_tbl (
user_id,
list_no,
oldmail_addr,
hidden_flg,
make_id,
update_id
)
VALUES
 (
{0},
(SELECT COALESCE(MAX(list_no), 0) + 1 FROM oldmail_list_tbl WHERE user_id = {0}),
'{1}',
'1',
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['GET_CTRL_ID'] = "SELECT ctrl_id FROM user_ctrl_mst WHERE mode_name = '{0}'";

$cmnSql['INSERT_EDIT_LOG'] = "
INSERT INTO user_edit_log (
user_id,
log_kbn,
ctrl_id,
log_user_id,
reserve_flg,
reflect_date
)
VALUES
(
{USER_ID},
{LOG_KBN},
{CTRL_ID},
{LOG_USER_ID},
{RESERVE_FLG},
{REFLECT_DATE}
)
";

$cmnSql['GET_SG_NAME'] = "SELECT sg_name FROM belong_chg_mst WHERE belong_chg_id = {0}";

?>