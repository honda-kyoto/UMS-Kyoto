<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_USER_MAIL_ACC'] = "
SELECT
    mail_acc
FROM
    user_mst
WHERE
    user_id = {0}
";

$cmnSql['CHECK_CURRENT_PASSWD'] = "SELECT user_id FROM user_mst WHERE user_id = {0} AND login_passwd = '{1}'";

$cmnSql['EXISTS_SENDON_ADDR'] = "SELECT list_no FROM sendon_list_tbl WHERE user_id = {0} AND sendon_addr = '{1}' AND del_flg = '0'";

$cmnSql['EXISTS_USER_SENON_HEAD'] = "
SELECT
    sendon_type
FROM
    sendon_head_tbl
WHERE
	user_id = {0}
";

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

$cmnSql['UPDATE_PASSWD'] = "
UPDATE
    user_mst
SET
    login_passwd = {LOGIN_PASSWD},
    login_passwd_update_date = now()::date,
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    user_id = {0}
";

$cmnSql['GET_USERS_APP_LIST'] = "
SELECT
    AHT.app_id,
    ATM.app_type_name,
    AHT.app_name,
    AHT.vlan_room_id,
    AHT.mac_addr,
    AHT.ip_addr,
    AHT.app_user_id,
    AHT.wireless_id
FROM
    app_head_tbl AS AHT,
    app_type_mst AS ATM
WHERE
    AHT.app_type_id = ATM.app_type_id AND
    AHT.del_flg='0' AND
    ATM.del_flg='0' AND
    (ATM.wire_kbn = {WIRE_KBN_WLESS} OR (ATM.wire_kbn = {WIRE_KBN_FREE} AND AHT.wire_kbn = {WIRE_KBN_WLESS})) AND
    AHT.app_user_id = {0} AND
    NOT EXISTS (SELECT
                    *
                FROM
                    app_head_entry AS AHE
                WHERE
                    AHT.app_id = app_id AND
                    del_flg = '0' AND
                    entry_status IN ({ENTRY_STATUS_ENTRY}, {ENTRY_STATUS_REJECT}) AND
                    EXISTS (SELECT
                                *
                            FROM
                                (SELECT
                                    app_id,
                                    MAX(entry_no) AS entry_no
                                FROM
                                    app_head_entry
                                WHERE
                                    del_flg = '0'
                                GROUP BY
                                    app_id
                                ) AS LST
                            WHERE
                                AHE.app_id = app_id AND
                                AHE.entry_no = entry_no
                            )
                )
ORDER BY
    ATM.disp_num,
    AHT.app_id
";

$cmnSql['GET_APP_LIST_DATA'] = "
SELECT
    vlan_id,
    busy_flg
FROM
    app_list_tbl
WHERE
    del_flg='0' AND
    app_id = {0}
ORDER BY
    vlan_id
";

$cmnSql['UPDATE_VLAN_BUSY_FLG'] = "UPDATE app_list_tbl SET busy_flg = '{2}', update_time = now(), update_id = {UPDATE_ID} WHERE app_id = {0} AND vlan_id = {1}";

$cmnSql['EXISTS_USER_SALARY_PASSWD'] = "
SELECT
    MAX(history_no) AS history_no
FROM
    user_salary_tbl
WHERE
	user_id = {0}
";

$cmnSql['INSERT_USER_SALARY_PASSWD'] = "
INSERT INTO user_salary_tbl (
    user_id,
    history_no,
    salary_passwd,
    make_id,
    update_id
) VALUES (
    {0},
    {HISTORY_NO},
    {SALARY_PASSWD},
    {0},
    {0}
)
";

$cmnSql['GET_JOUKIN_KBN'] = "
SELECT
    joukin_kbn
FROM
    user_mst
WHERE
    user_id = {0}
";


$cmnSql['EXISTS_WIRELESS_AD_ERR'] = "SELECT log_cd FROM wireless_ad_errlog WHERE user_id = {0} AND wireless_id = '{1}' AND complete_flg = '0'";

$cmnSql['INSERT_WIRELESS_AD_ERR'] = "INSERT INTO wireless_ad_errlog (log_cd, log_time, user_id, wireless_id) VALUES ('{0}', now(), {1}, '{2}')";

?>