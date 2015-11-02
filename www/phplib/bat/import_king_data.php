<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");

$dir = getcwd();

$oMgr = new common_mgr();

$sql = "
SELECT
  UM.user_id,
  UM.login_id,
  UM.kanjisei || ' ' || UM.kanjimei AS kanjiname,
  UM.login_passwd,
  UM.kanasei || ' ' || UM.kanamei AS kananame,
  TO_CHAR(UM.birthday, 'YYYYMMDD') AS birthday,
  UM.sex
FROM
    user_mst AS UM
";

$aryRet = $oMgr->oDb->getAll($sql);

if (is_array($aryRet))
{
	foreach ($aryRet AS $user_id => $data)
	{
		print_r($data['kanjiname']."|".$data['sex']."|".$data['birthday']."\n");
	}
}




exit;


?>


