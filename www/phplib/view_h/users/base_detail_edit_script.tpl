
var cal_rd = new JKL.Calendar("caldiv_rd","mainForm","retire_date");
cal_rd.setStyle( "frame_color", "#3333CC" );
cal_rd.setStyle( "typestr", "yyyy/mm/dd" );

var cal_rfd = new JKL.Calendar("caldiv_rfd","mainForm","reflect_date");
cal_rfd.setStyle( "frame_color", "#3333CC" );
cal_rfd.setStyle( "typestr", "yyyy/mm/dd" );

function changeReserveFlg()
{
	document.getElementById('reflect_date').disabled = !document.getElementById('reserve_flg').checked;
}

function showReserveData()
{
	document.getElementById('edit_mode').value = "reserve";
	formSubmit('input', 'users_detail.php');
}


function deleteReserveData()
{
	if (!confirm("予約を取り消します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('edit_mode').value = "";
	formSubmit('delBaseReserve', 'users_detail.php');
}

function editModeReset()
{
	if (!confirm("入力されたデータは反映されません。よろしいですか？"))
	{
		return;
	}
	document.getElementById('reserve_flg').value = "";
	document.getElementById('reflect_date').value = "";
	document.getElementById('edit_mode').value = "";
	formSubmit('input', 'users_detail.php');
}

function showHistoryList()
{
	javascript:window.open('about:blank', 'baseHistory', 'width=1024, height=600, menubar=no, toolbar=no, scrollbars=yes');

	formSubmit('init', 'base_history.php', 'baseHistory');
}