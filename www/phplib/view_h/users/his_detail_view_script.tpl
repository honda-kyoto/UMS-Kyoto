
function shiftHisTab(no)
{
	document.getElementById('list_no').value = no;
	document.getElementById('edit_mode').value = "";
	formSubmit('view', 'users_detail.php');
}


function editModeChange(mode)
{
	document.getElementById('edit_mode').value = mode;
	formSubmit('view', 'users_detail.php');
}

function historyEdit(history_no)
{
	document.getElementById('history_no').value = history_no;
	editModeChange('history');
}

function editModeReset()
{
	document.getElementById('edit_mode').value = "";
	formSubmit('view', 'users_detail.php');
}
