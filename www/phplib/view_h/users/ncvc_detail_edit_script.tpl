
jQuery(document).ready(function() {

  jQuery('a.login_id_str').cluetip({splitTitle: '|'});
});

var cal_sd = new JKL.Calendar("caldiv_sd","mainForm","start_date");
cal_sd.setStyle( "frame_color", "#3333CC" );
cal_sd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_ed = new JKL.Calendar("caldiv_ed","mainForm","end_date");
cal_ed.setStyle( "frame_color", "#3333CC" );
cal_ed.setStyle( "typestr", "yyyy/mm/dd" );

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
	formSubmit('delNcvcReserve', 'users_detail.php');
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

function printNcvcPassword()
{
	if (confirm("パスワード再発行証明書印刷画面を表示しますか？"))
	{
		window.open("print_password.html", "pwPrint", 'directories=no,menubar=no,width=800,resizeble=no,status=no,scrollbars=yes');
		formSubmit("view", "print_password.html", "pwPrint");
	}
}


function showHistoryList()
{
	javascript:window.open('about:blank', 'ncvcHistory', 'width=1024, height=600, menubar=no, toolbar=no, scrollbars=yes');

	formSubmit('init', 'ncvc_history.php', 'ncvcHistory');
}