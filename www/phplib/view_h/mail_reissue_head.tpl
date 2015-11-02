<script type="text/javascript">
<!--

function updateMailAcc()
{
	var acc = document.getElementById('mail_acc').value;
	if (!confirm("メールアカウント：「"+acc+"」\nこの内容で再設定します。よろしいですか？"))
	{
		return;
	}
	formSubmit('update');
}

function escapeReissue()
{
	if (!confirm("メールアカウントを変更せずにこの画面を終了します。よろしいですか？"))
	{
		return;
	}
	formSubmit('escape');
}

//-->
</script>
