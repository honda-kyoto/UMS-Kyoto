<script type="text/javascript">
<!--

function sort(order)
{
	document.mainForm.order.value = order;
	formSubmit('sort');
}

function search()
{
	formSubmit('search');
}

function clearCond()
{
	document.getElementById('king_name').value = "";
	document.getElementById('king_name_kana').value = "";

}

function memberList(id)
{
	document.getElementById('mlist_id').value = id;
	formSubmit('list', 'mlists_members.php');
}

function mlistsEdit(id)
{
	document.getElementById('mlist_id').value = id;
	formSubmit('input', 'mlists_edit.php');
}

function mlistsDelete(id)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('mlist_id').value = id;
	formSubmit('delete');
}


function mlistsPending(id, no)
{
	document.getElementById('mlist_id').value = id;
	document.getElementById('entry_no').value = no;
	formSubmit('view', 'mlists_pending.php');
}

function mlistsRevise(id)
{
	document.getElementById('mlist_id').value = id;
	formSubmit('retry', 'mlists_entry.php');
}

function mlistsDelEntry(id)
{
	if (!confirm("削除申請します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('mlist_id').value = id;
	formSubmit('delEntry');
}

//-->
</script>
