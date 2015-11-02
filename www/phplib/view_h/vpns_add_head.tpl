<script type="text/javascript">
<!--

function addVpn()
{
	if (!confirm("この内容で登録します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add', 'vpns_add.php');
}

function resetVpn()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'vpns_add.php');
}

<?php include("view_h/vpns/regist_func.tpl") ?>

//-->
</script>
