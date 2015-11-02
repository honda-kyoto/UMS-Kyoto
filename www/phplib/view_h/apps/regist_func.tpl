<?php include("view_h/apps/vlan_func.tpl") ?>

function searchAppUser()
{
	window.open('about:blank', 'schAppUser', 'directories=no,menubar=no,width=1024,height=600,resizeble=no,status=no,scrollbars=yes');
	formSubmit('init', 'apps_user_search.php', 'schAppUser');
}