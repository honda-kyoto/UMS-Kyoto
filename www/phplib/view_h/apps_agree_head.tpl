<script type="text/javascript">
<!--
function agreeApp()
{
	if (!confirm("この申請を承認します。よろしいですか？"))
	{
		return;
	}
	formSubmit('agree');
}

function rejectApp()
{
	if (!confirm("この申請を却下します。よろしいですか？"))
	{
		return;
	}
	formSubmit('reject');
}

function returnList()
{
	formSubmit('return', 'apps_req.php');
}

function agreeAppWless(id)
{
	if (!confirm("この申請を承認します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('vlan_id').value = id;
	formSubmit('agree');
}

function rejectAppWless(id)
{
	if (!confirm("この申請を却下します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('vlan_id').value = id;
	formSubmit('reject');
}

//-->
</script>
