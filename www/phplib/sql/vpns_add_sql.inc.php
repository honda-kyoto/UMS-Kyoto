<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/vpns_regist_common_sql.inc.php");


$cmnSql['INSERT_VPN_HEAD'] = "
INSERT INTO vpn_head_tbl (
vpn_id,
vpn_kbn,
vpn_name,
group_name,
group_code,
usage,
note,
make_id,
update_id
)
VALUES
(
{VPN_ID},
{VPN_KBN},
{VPN_NAME},
{GROUP_NAME},
{GROUP_CODE},
{USAGE},
{NOTE},
{MAKE_ID},
{UPDATE_ID}
)
";


?>