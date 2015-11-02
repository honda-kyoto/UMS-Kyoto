<script src="js/prototype.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/apps/vlan_func.tpl") ?>

function sort(order)
{
	document.mainForm.order.value = order;
	formSubmit('sort');
}

function search()
{
	formSubmit('search');
}

function clearCond()
{
	document.getElementById('app_type_id').selectedIndex = 0;
	document.getElementById('vlan_ridge_id').selectedIndex = 0;
	document.getElementById('vlan_floor_id').selectedIndex = 0;
	document.getElementById('vlan_room_id').selectedIndex = 0;
	document.getElementById('vlan_id').selectedIndex = 0;
	document.getElementById('vlan_floor_id').disabled = true;
	document.getElementById('vlan_room_id').disabled = true;
	document.getElementById('vlan_id').disabled = true;
	document.getElementById('mac_addr').value = "";
	document.getElementById('ip_addr').value = "";
	document.getElementById('app_name').value = "";
	document.getElementById('entry_user_name').value = "";
	
}


function appsAgree(id, no)
{
	document.getElementById('app_id').value = id;
	document.getElementById('entry_no').value = no;
	formSubmit('view', 'apps_agree.php');
}

//-->
</script>
