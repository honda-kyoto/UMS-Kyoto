<script type="text/javascript">
<!--
function agreeMlist()
{
	if (!confirm("この申請を承認します。よろしいですか？"))
	{
		return;
	}
	formSubmit('agree');
}

function rejectMlist()
{
	if (!confirm("この申請を却下します。よろしいですか？"))
	{
		return;
	}
	formSubmit('reject');
}

function returnList()
{
	formSubmit('return', 'mlists_req.php');
}

//-->
</script>
