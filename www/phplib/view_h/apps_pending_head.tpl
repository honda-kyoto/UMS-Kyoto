<script type="text/javascript">
<!--
function cancelApp()
{
	if (!confirm("この申請を取り消します。よろしいですか？"))
	{
		return;
	}
	formSubmit('cancel');
}

function retryApp()
{
	formSubmit('retry', 'apps_entry.php');
}

function returnList()
{
	formSubmit('return', 'apps_search.php');
}

//-->
</script>
