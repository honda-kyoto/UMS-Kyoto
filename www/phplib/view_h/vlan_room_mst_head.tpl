<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/msts/dispnum_common.tpl") ?>

function insertRoom()
{
	if (confirm("追加します。よろしいですか？"))
	{
		formSubmit('insert', 'vlan_room_mst.php');
	}
}

function updateRoom(id)
{
	if (confirm("更新します。よろしいですか？"))
	{
		document.mainForm.vlan_room_id.value = id;
		formSubmit('update', 'vlan_room_mst.php');
	}
}

function deleteRoom(id)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.vlan_room_id.value = id;
		formSubmit('delete', 'vlan_room_mst.php');
	}
}

function mstEditAll()
{
	if (confirm("全て更新します。よろしいですか？"))
	{
		formSubmit('editall', 'vlan_room_mst.php');
	}
}

function goVlanMst(id)
{
	document.mainForm.vlan_room_id.value = id;
	formSubmit('init', 'vlan_mst.php');
}

function returnFloor()
{
	formSubmit('return', 'vlan_floor_mst.php');
}

//-->
</script>
