<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/vpns_regist_common_sql.inc.php");

$cmnSql['GETLIST'] = "
SELECT
    vpn_user_id,
    mail_addr,
    passwd,
    kanjiname,
    kananame,
    company,
    contact,
    note,
    TO_CHAR(expiry_date, 'YYYY/MM/DD') AS expiry_date
FROM
    vpn_members_list
WHERE
    vpn_id = {0} AND
    del_flg='0'
ORDER BY
    update_time DESC
";


//$cmnSql['EXISTS_MAIL_ADDR'] = "SELECT mail_addr FROM vpn_members_list WHERE vpn_id = {0} AND mail_addr = '{1}' AND del_flg = '0'";

$cmnSql['VPN_USER_ID_LOCK'] = "SELECT * FROM vpn_members_list WHERE vpn_id = {0} FOR UPDATE";
$cmnSql['GET_VPN_USER_ID_LOCK'] = "SELECT COALESCE(CAST(SUBSTR(MAX(vpn_user_id), 3) as integer), 0) + 1 FROM vpn_members_list WHERE vpn_id = {0}";

$cmnSql['INSERT_VPN_MEMBER'] = "
INSERT INTO vpn_members_list (
vpn_id,
vpn_user_id,
mail_addr,
passwd,
kanjiname,
kananame,
company,
contact,
expiry_date,
note,
make_id,
update_id
)
VALUES
(
{VPN_ID},
{VPN_USER_ID},
{MAIL_ADDR},
{PASSWD},
{KANJINAME},
{KANANAME},
{COMPANY},
{CONTACT},
{EXPIRY_DATE},
{NOTE},
{MAKE_ID},
{UPDATE_ID}
)
";


$cmnSql['DELETE_VPN_MEMBER'] = "
UPDATE
    vpn_members_list
SET
    del_flg = '1',
    update_time = now(),
    update_id = {UPDATE_ID}
WHERE
    vpn_id = {0} AND
    vpn_user_id = '{1}'
";

$cmnSql['GET_PRINT_MEMBER'] = "
SELECT
    VML.vpn_user_id,
    VML.passwd,
    VML.kanjiname,
    VML.kananame,
    VML.company,
    VML.contact,
    TO_CHAR(expiry_date, 'YYYY年FMMM月FMDD日') AS limit_period,
    VML.note
FROM
    vpn_members_list AS VML
WHERE
    VML.vpn_id = {0} AND
    VML.vpn_user_id = '{1}' AND 
    VML.del_flg='0'
";

$cmnSql['UPDATE_VPN_MEMBERS_EXPIRY'] = "
UPDATE
    vpn_members_list
SET
    expiry_date = {EXPIRY_DATE},
    update_time = now(),
    update_id   = {UPDATE_ID}
WHERE
    vpn_id      = {VPN_ID}      AND
    vpn_user_id = {VPN_USER_ID}
";

$cmnSql['GET_VPN_MEMBERS_DATA'] = "
SELECT
    VML.vpn_user_id,
    VML.mail_addr,
    VML.passwd,
    VML.kanjiname,
    VML.kananame,
    VML.company,
    VML.contact,
    VML.note,
    TO_CHAR(VML.expiry_date, 'YYYY/MM/DD') AS expiry_date
FROM
    vpn_members_list VML
WHERE
    VML.del_flg = '0' AND
    VML.vpn_id = {0} AND
    VML.vpn_user_id = '{1}'
";
?>