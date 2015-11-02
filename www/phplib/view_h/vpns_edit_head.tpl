<script type="text/javascript">
<!--

function editVpn()
{
	if (!confirm("この内容で更新します。よろしいですか？"))
	{
		return;
	}
	formSubmit('update', 'vpns_edit.php');
}

function resetVpn()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'vpns_edit.php');
}

function returnList()
{
	formSubmit('return', 'vpns_search.php');
}



<?php include("view_h/vpns/regist_func.tpl") ?>

//-->
</script>
