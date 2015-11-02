<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");


$cmnSql['INSERT_GUEST_HEAD'] = "
INSERT INTO guest_head_tbl (
guest_id,
guest_name,
company_name,
belong_name,
telno,
mac_addr,
wireless_id,
password,
usage,
note,
make_id,
update_id
)
VALUES
(
{GUEST_ID},
{GUEST_NAME},
{COMPANY_NAME},
{BELONG_NAME},
{TELNO},
{MAC_ADDR},
{WIRELESS_ID},
{PASSWORD},
{USAGE},
{NOTE},
{MAKE_ID},
{UPDATE_ID}
)
";


?>