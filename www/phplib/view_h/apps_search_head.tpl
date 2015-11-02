<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
<!--
jQuery.noConflict();
//-->
</script>
<script src="js/jquery.ie-select-width.js"></script>
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
	document.getElementById('app_user_name').value = "";
	
	var len = document.mainForm.elements.length;
	for (var i = 0 ; i < len ; i++)
	{
		name = document.mainForm.elements[i].name;
		name = name.substring(0,16);

		if (name == "entry_kbn_status")
		{
			document.mainForm.elements[i].checked = false;
		}
	}
}

function appsEdit(id)
{
	document.getElementById('app_id').value = id;
	formSubmit('input', 'apps_edit.php');
}
function appsDelete(id)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('app_id').value = id;
	formSubmit('delete', 'apps_search.php');
}

function appsPending(id, no)
{
	document.getElementById('app_id').value = id;
	document.getElementById('entry_no').value = no;
	formSubmit('view', 'apps_pending.php');
}

function appsDetail(id)
{
	document.getElementById('app_id').value = id;
	formSubmit('view', 'apps_detail.php');
}

function appsDelEntry(id)
{
	if (!confirm("削除申請します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('app_id').value = id;
	formSubmit('delEntry');
}

//-->
</script>
