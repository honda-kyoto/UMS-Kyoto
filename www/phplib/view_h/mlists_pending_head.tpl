<script type="text/javascript">
<!--
function cancelMlist()
{
	if (!confirm("この申請を取り消します。よろしいですか？"))
	{
		return;
	}
	formSubmit('cancel');
}

function retryMlist()
{
	formSubmit('retry', 'mlists_entry.php');
}

function returnList()
{
	formSubmit('return', 'mlists_search.php');
}

//-->
</script>
