
function addCardNo(no, code)
{
	if (!confirm("入退室カードを発行します。よろしいですか？"))
	{
		return;
	}
	document.mainForm.list_no.value = no;
	document.mainForm.ident_code.value = code;
	formSubmit('cardAdd');
}

function stopCard(no)
{
	var sel_id = "disuse_kbn_" + no;
	if (document.getElementById(sel_id).value == "")
	{
		alert("停止理由を選択してください。");
		return;
	}
	if (!confirm("入退室カードを停止します。よろしいですか？"))
	{
		return;
	}
	document.mainForm.list_no.value = no;
	formSubmit('cardStop');
}

function reissueCard(no)
{
	var sel_id = "reissue_kbn_" + no;
	if (document.getElementById(sel_id).value == "")
	{
		alert("再発行理由を選択してください。");
		return;
	}
	if (!confirm("入退室カードを再発行します。よろしいですか？"))
	{
		return;
	}
	document.mainForm.list_no.value = no;
	formSubmit('cardReissue');
}