<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : sumio imoto
* Date         : 2013.06.20
* Last Update  : 2013.06.20
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");

$cmnSql['GET_VPN_MEMBERS_DATA'] = "
SELECT
    VHT.vpn_kbn,
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
    vpn_head_tbl VHT,
    vpn_members_list VML
WHERE
    VHT.del_flg = '0' AND
    VML.del_flg = '0' AND
    VHT.vpn_id = VML.vpn_id AND
    VML.vpn_id = {0} AND
    VML.vpn_user_id = '{1}'
";

?>