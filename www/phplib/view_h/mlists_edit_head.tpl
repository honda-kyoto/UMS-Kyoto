<script type="text/javascript">
<!--

function editMlist()
{
	if (!confirm("この内容で更新します。よろしいですか？"))
	{
		return;
	}
	formSubmit('update', 'mlists_edit.php');
}

function resetMlist()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'mlists_edit.php');
}

function returnList()
{
	formSubmit('return', 'mlists_search.php');
}



<?php include("view_h/mlists/regist_func.tpl") ?>

//-->
</script>
