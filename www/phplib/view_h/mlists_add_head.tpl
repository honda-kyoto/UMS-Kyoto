<script type="text/javascript">
<!--

function addMlist()
{
	if (!confirm("この内容で登録します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add', 'mlists_add.php');
}

function resetMlist()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'mlists_add.php');
}

<?php include("view_h/mlists/regist_func.tpl") ?>

//-->
</script>
