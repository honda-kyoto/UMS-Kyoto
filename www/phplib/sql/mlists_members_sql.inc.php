<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/mlists_regist_common_sql.inc.php");

$cmnSql['GETLIST'] = "
SELECT
    mail_addr,
    member_name,
    sender_flg,
    recipient_flg
FROM
    mlist_members_{TYPE}
WHERE
    mlist_id = {0} AND
    del_flg='0'
ORDER BY
    update_time DESC
";

$cmnSql['GETMEMBER'] = "
SELECT
    sender_flg,
    recipient_flg
FROM
    mlist_members_list
WHERE
    mlist_id = {0} AND
    mail_addr = '{1}' AND
    del_flg='0'
";

$cmnSql['EXISTS_MAIL_ADDR'] = "SELECT mail_addr FROM mlist_members_list WHERE mlist_id = {0} AND mail_addr = '{1}' AND del_flg = '0'";

$cmnSql['GET_SENDER_KBN'] = "
SELECT
    sender_kbn
FROM
    mlist_head_tbl
WHERE
    mlist_id = {0}
";

$cmnSql['GET_MLIST_KBN'] = "
SELECT
    mlist_kbn
FROM
    mlist_head_tbl
WHERE
    mlist_id = {0}
";

$cmnSql['EXISTS_MLIST_MEMBER'] = "
SELECT
    mail_addr
FROM
    mlist_members_{TYPE}
WHERE
    mlist_id = {MLIST_ID} AND
    mail_addr = {MAIL_ADDR}
";

$cmnSql['INSERT_MLIST_MEMBER'] = "
INSERT INTO mlist_members_{TYPE} (
mlist_id,
mail_addr,
member_name,
sender_flg,
recipient_flg,
make_id,
update_id
) VALUES (
{MLIST_ID},
{MAIL_ADDR},
{MEMBER_NAME},
{SENDER_FLG},
{RECIPIENT_FLG},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['UPDATE_MLIST_MEMBER'] = "
UPDATE
    mlist_members_{TYPE}
SET
    member_name = {MEMBER_NAME},
    sender_flg = {SENDER_FLG},
    recipient_flg = {RECIPIENT_FLG},
    del_flg = '0',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    mlist_id = {MLIST_ID} AND
    mail_addr = {MAIL_ADDR}
";


$cmnSql['DELETE_MLIST_MEMBER'] = "
UPDATE
    mlist_members_{TYPE}
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    mlist_id = {0} AND
    mail_addr = '{1}'
";

$cmnSql['INSERT_MLIST_AUTO_COND'] = "
INSERT INTO mlist_auto_cond_{TYPE} (
mlist_id,
list_no,
post_id,
job_id,
joukin_kbn,
belong_class_id,
belong_div_id,
belong_dep_id,
belong_sec_id,
belong_chg_id,
make_id,
update_id
)
VALUES
(
{MLIST_ID},
(SELECT COALESCE(MAX(list_no), 0) + 1 FROM mlist_auto_cond_{TYPE} WHERE mlist_id = {MLIST_ID}),
{POST_ID},
{JOB_ID},
{JOUKIN_KBN},
{BELONG_CLASS_ID},
{BELONG_DIV_ID},
{BELONG_DEP_ID},
{BELONG_SEC_ID},
{BELONG_CHG_ID},
{MAKE_ID},
{UPDATE_ID}
)
";

$cmnSql['EXISTS_MLIST_AUTO_SET'] = "SELECT mlist_id FROM mlist_auto_set_{TYPE} WHERE mlist_id = {0} AND del_flg = '0'";

$cmnSql['COPY_MLIST_AUTO_SET'] = "INSERT INTO mlist_auto_set_work SELECT * FROM mlist_auto_set_tbl WHERE mlist_id = {0}";
$cmnSql['COPY_MLIST_AUTO_COND'] = "INSERT INTO mlist_auto_cond_work SELECT * FROM mlist_auto_cond_list WHERE mlist_id = {0}";
$cmnSql['COPY_MLIST_MEMBERS'] = "INSERT INTO mlist_members_work SELECT * FROM mlist_members_list WHERE mlist_id = {0}";

$cmnSql['COMMIT_MLIST_AUTO_SET'] = "INSERT INTO mlist_auto_set_tbl SELECT * FROM mlist_auto_set_work WHERE mlist_id = {0}";
$cmnSql['COMMIT_MLIST_AUTO_COND'] = "INSERT INTO mlist_auto_cond_list SELECT * FROM mlist_auto_cond_work WHERE mlist_id = {0}";
$cmnSql['COMMIT_MLIST_MEMBERS'] = "INSERT INTO mlist_members_list SELECT * FROM mlist_members_work WHERE mlist_id = {0}";

$cmnSql['TRUNCATE_MLIST_MEMBERS'] = "DELETE FROM mlist_members_{TYPE} WHERE mlist_id = {0}";
$cmnSql['TRUNCATE_MLIST_AUTO_COND'] = "DELETE FROM mlist_auto_cond_{TYPE} WHERE mlist_id = {0}";
$cmnSql['TRUNCATE_MLIST_AUTO_SET_TYPE'] = "DELETE FROM mlist_auto_set_{TYPE} WHERE mlist_id = {0}";

$cmnSql['INSERT_MLIST_AUTO_SET'] = "INSERT INTO mlist_auto_set_{TYPE} (mlist_id, sender_set_type, make_id, update_id) VALUES ({0}, '{1}', {MAKE_ID}, {UPDATE_ID})";

$cmnSql['CLEAR_MLIST_AUTO_COND'] = "UPDATE mlist_auto_cond_{TYPE} SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {MLIST_ID}";
$cmnSql['DELETE_MLIST_AUTO_COND'] = "UPDATE mlist_auto_cond_{TYPE} SET del_flg = '1', update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {MLIST_ID} AND list_no = {LIST_NO}";

$cmnSql['UPDATE_SENDER_SET_TYPE'] = "UPDATE mlist_auto_set_{TYPE} SET sender_set_type = {SENDER_SET_TYPE}, update_time = now(), update_id = {UPDATE_ID} WHERE mlist_id = {MLIST_ID}";

$cmnSql['EXISTS_AUTO_COND_LIST'] = "
SELECT
    list_no,
    post_id,
    job_id,
    joukin_kbn,
    belong_class_id,
    belong_div_id,
    belong_dep_id,
    belong_sec_id,
    belong_chg_id
FROM
    mlist_auto_cond_{TYPE}
WHERE
    mlist_id = {0} AND
    del_flg='0'
ORDER BY
    list_no
";

$cmnSql['GET_AUTO_SET_TYPE'] = "SELECT sender_set_type FROM mlist_auto_set_{TYPE} WHERE mlist_id = {0} AND del_flg = '0'";

$cmnSql['GET_AUTO_COND_LIST'] = "
SELECT
    list_no,
    post_id,
    job_id,
    joukin_kbn,
    belong_class_id,
    belong_div_id,
    belong_dep_id,
    belong_sec_id,
    belong_chg_id
FROM
    mlist_auto_cond_{TYPE}
WHERE
    mlist_id = {0} AND
    del_flg='0'
ORDER BY
    list_no
";



$cmnSql['SEARCH_MLIST_AUTO_MEMBERS'] = "
SELECT DISTINCT
    UM.login_id,
    UM.mail_acc,
    UM.kanjisei,
    UM.kanjimei,
    UM.belong_chg_id
FROM
    user_mst AS UM
WHERE
    UM.login_id != '' AND
    UM.mlist_disused_flg = '0' AND
    UM.mail_disused_flg = '0' AND
    UM.start_date <= now()::date AND
    COALESCE(UM.end_date, now()::date) >= now()::date
    {COND}
";


?>