<script type="text/javascript">
<!--

function deleteVlanAdmin(no)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.list_no.value = no;
		formSubmit('delete', 'vlan_admin_list.php');
	}
}

function returnVlan()
{
	formSubmit('return', 'vlan_mst.php');
}



function searchAdminUser()
{
	window.open('about:blank', 'schAdmin', 'directories=no,menubar=no,width=1024,height=600,resizeble=no,status=no,scrollbars=yes');
	formSubmit('init', 'vlans_admin_search.php', 'schAdmin');
}

//-->
</script>
