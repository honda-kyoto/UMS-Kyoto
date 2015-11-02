<script type="text/javascript">
<!--

function returnList()
{
	formSubmit('return', 'apps_search.php');
}

function changeUseSbcFlg()
{
	if (!confirm("仮想環境での利用を変更します。よろしいですか？"))
	{
		return;
	}
	formSubmit('changeSbcFlg', 'apps_detail.php');
}

function appsRevise()
{
	formSubmit('retry', 'apps_entry.php');
}
//-->
</script>
