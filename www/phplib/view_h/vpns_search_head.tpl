<script src="js/prototype.js"></script>
<script type="text/javascript">
<!--

function sort(order)
{
	document.mainForm.order.value = order;
	formSubmit('sort', 'vpns_search.php');
}

function search()
{
	formSubmit('search', 'vpns_search.php');
}

function clearCond()
{
	document.getElementById('vpn_kbn').selectedIndex = 0;
	document.getElementById('vpn_name').value = "";
	document.getElementById('group_name').value = "";
}

function memberList(id)
{
	document.getElementById('vpn_id').value = id;
	formSubmit('list', 'vpns_members.php');
}

function vpnsEdit(id)
{
	document.getElementById('vpn_id').value = id;
	formSubmit('input', 'vpns_edit.php');
}

function vpnsDelete(id)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('vpn_id').value = id;
	formSubmit('delete', 'vpns_search.php');
}

//-->
</script>
