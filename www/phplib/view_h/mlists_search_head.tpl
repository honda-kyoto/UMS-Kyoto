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
	document.getElementById('mlist_name').value = "";
	document.getElementById('mlist_acc').value = "";
	document.getElementById('mlist_kbn').selectedIndex = 0;

	var len = document.mainForm.elements.length;
	for (var i = 0 ; i < len ; i++)
	{
		name = document.mainForm.elements[i].name;
		name = name.substring(0,16);

		if (name == "entry_kbn_status")
		{
			document.mainForm.elements[i].checked = false;
		}
	}
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
