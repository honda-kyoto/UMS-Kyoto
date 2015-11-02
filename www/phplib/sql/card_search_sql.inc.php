<?php
/**********************************************************
* File         : sql.inc.php
* Authors      : kazuyoshi shibuta
* Date         : 2015.06.04
* Last Update  : 2015.06.04
* Copyright    :
***********************************************************/
require_once("sql/common_sql.inc.php");


$cmnSql['GETCOUNT'] = "
SELECT
    COUNT(card_1) AS list_count
FROM
    card_system_tbl
{COND}
";

$cmnSql['GETLIST'] = "
SELECT
  case 
    when card_39 = '20' then SUBSTR( card_6, 12)
    when card_39 = '50' then SUBSTR( card_6, 12)
    when card_39 = '70' then SUBSTR( card_6, 11)
    ELSE card_6
    END as card_id,
    card_40 as card_uid,
    card_8 as card_name,
    card_9 as card_name_kana,
    card_11 as card_birth,
    card_10 as card_sex,
    card_59 as card_biko1,
    card_60 as card_biko2,
    card_61 as card_biko3,
    card_39 as card_tag
FROM
    card_system_tbl
{COND}
ORDER BY
    {ORDER} {DESC}
LIMIT {LIMIT} OFFSET {OFFSET}
";

?>