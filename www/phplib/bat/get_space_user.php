<?php
set_include_path('.:/usr/share/pear:/var/www/phplib');

require_once("mgr/common_mgr.class.php");


$oMgr = new common_mgr();

$sql = "select user_id,kanjisei,kanjimei,substr(kanjimei,0, position(' ' in kanjimei)) as sei ,substr(kanjimei,position(' ' in kanjimei)) as mei from user_mst where kanjimei like '% %'";

$aryRet = $oMgr->oDb->getAll($sql);



if (is_array($aryRet))
{
	foreach ($aryRet AS $data)
	{
		print_r("user:".$data['sei'].$data['mei']."\n");
		
		$sql = "update user_mst set kanjisei = '".$data['sei']."' ,kanjimei = '".$data['mei']."' where user_id = ".$data['user_id'];
		$ret = $oMgr->oDb->query($sql);
//print_r("sql:".$sql."\n");
		if (!$ret)
		{
			echo "DBの更新に失敗しました。\n";
			continue;
		}
		echo "DBの更新が正常に終了しました。\n";
	}
}

exit;


?>
