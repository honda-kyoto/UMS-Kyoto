<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/users_regist_common_sql.inc.php");

$cmnSql['INSERT_USER_MST'] = "
INSERT INTO user_mst (
user_id,
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
retire_flg,
garoon_disused_flg,
mlist_disused_flg,
vdi_user_flg,
ftrans_user_flg,
ftrans_user_kbn,
make_id,
update_id
)
VALUES
(
{USER_ID},
{STAFF_ID},
{STAFFCODE},
{LOGIN_ID},
{LOGIN_PASSWD},
{KANJISEI},
{KANJIMEI},
{KANASEI},
{KANAMEI},
{EIJISEI},
{EIJIMEI},
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
{RETIRE_FLG},
{GAROON_DISUSED_FLG},
{MLIST_DISUSED_FLG},
{VDI_USER_FLG},
{FTRANS_USER_FLG},
{FTRANS_USER_KBN},
{MAKE_ID},
{UPDATE_ID}
)
";


?>