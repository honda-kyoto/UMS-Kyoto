<script type="text/javascript">
<!--
function entryMlist()
{
	if (!confirm("この内容で申請します。よろしいですか？"))
	{
		return;
	}
	formSubmit('entry', 'mlists_entry.php');
}

function resetMlist()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'mlists_entry.php');
}

function returnPendingMlist()
{
	formSubmit('view', 'mlists_pending.php');
}

function returnList()
{
	formSubmit('return', 'mlists_search.php');
}

<?php include("view_h/mlists/regist_func.tpl") ?>

//-->
</script>
