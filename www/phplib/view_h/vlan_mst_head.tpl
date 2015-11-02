<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/msts/dispnum_common.tpl") ?>

function insertVlan()
{
	if (confirm("追加します。よろしいですか？"))
	{
		formSubmit('insert', 'vlan_mst.php');
	}
}

function updateVlan(id)
{
	if (confirm("更新します。よろしいですか？"))
	{
		document.mainForm.vlan_id.value = id;
		formSubmit('update', 'vlan_mst.php');
	}
}

function deleteVlan(id)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.vlan_id.value = id;
		formSubmit('delete', 'vlan_mst.php');
	}
}

function mstEditAll()
{
	if (confirm("全て更新します。よろしいですか？"))
	{
		formSubmit('editall', 'vlan_mst.php');
	}
}

function goAdminList(id)
{
	document.mainForm.vlan_id.value = id;
	formSubmit('init', 'vlan_admin_list.php');
}

function returnRoom()
{
	formSubmit('return', 'vlan_room_mst.php');
}

//-->
</script>
