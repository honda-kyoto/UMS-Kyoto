<?php
/**********************************************************
* File         : mlists_auto_members.php
* Authors      : mie tsutsui
* Date         : 2013.01.10
* Last Update  : 2013.01.10
* Copyright    :
***********************************************************/
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");


$oMgr = new common_mgr();

$sql = "
SELECT
    mlist_id
FROM
    mlist_auto_cond_list
WHERE
    del_flg='0'
";

$aryId = $oMgr->oDb->getCol($sql);

if (is_array($aryId))
{
	foreach ($aryId AS $mlist_id)
	{
		$oMgr->relationAutoMembers($mlist_id);
	}
}

exit;


?>
