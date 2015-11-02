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

}


function mlistsAgree(id, no)
{
	document.getElementById('mlist_id').value = id;
	document.getElementById('entry_no').value = no;
	formSubmit('view', 'mlists_agree.php');
}

//-->
</script>
