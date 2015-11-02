<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/msts/dispnum_common.tpl") ?>

function insertFloor()
{
	if (confirm("追加します。よろしいですか？"))
	{
		formSubmit('insert', 'vlan_floor_mst.php');
	}
}

function updateFloor(id)
{
	if (confirm("更新します。よろしいですか？"))
	{
		document.mainForm.vlan_floor_id.value = id;
		formSubmit('update', 'vlan_floor_mst.php');
	}
}

function deleteFloor(id)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.vlan_floor_id.value = id;
		formSubmit('delete', 'vlan_floor_mst.php');
	}
}

function mstEditAll()
{
	if (confirm("全て更新します。よろしいですか？"))
	{
		formSubmit('editall', 'vlan_floor_mst.php');
	}
}

function goRoomMst(id)
{
	document.mainForm.vlan_floor_id.value = id;
	formSubmit('init', 'vlan_room_mst.php');
}

function returnRidge()
{
	formSubmit('return', 'vlan_ridge_mst.php');
}

//-->
</script>
