<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/vpns_members_regist_common_sql.inc.php");

$cmnSql['UPDATE_VPN_MEMBERS_LIST'] = "
UPDATE
    vpn_members_list
SET
    mail_addr   = {MAIL_ADDR},
    passwd      = {PASSWD},
    kanjiname   = {KANJINAME},
    kananame    = {KANANAME},
    company     = {COMPANY},
    contact     = {CONTACT},
    expiry_date = {EXPIRY_DATE},
    note        = {NOTE},
    update_time = now(),
    update_id   = {UPDATE_ID}
WHERE
    vpn_id      = {VPN_ID}      AND
    vpn_user_id = {VPN_USER_ID}
";

?>