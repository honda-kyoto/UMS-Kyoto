
var cal_sdd = new JKL.Calendar("caldiv_sdd","mainForm","send_date");
cal_sdd.setStyle( "frame_color", "#3333CC" );
cal_sdd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_vsd = new JKL.Calendar("caldiv_vsd","mainForm","validstartdate");
cal_vsd.setStyle( "frame_color", "#3333CC" );
cal_vsd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_ved = new JKL.Calendar("caldiv_ved","mainForm","validenddate");
cal_ved.setStyle( "frame_color", "#3333CC" );
cal_ved.setStyle( "typestr", "yyyy/mm/dd" );
var cal_rd = new JKL.Calendar("caldiv_rd","mainForm","retire_date");
cal_rd.setStyle( "frame_color", "#3333CC" );
cal_rd.setStyle( "typestr", "yyyy/mm/dd" );


function shiftHisTab(no)
{
	document.getElementById('list_no').value = no;
	document.getElementById('edit_mode').value = "";
	formSubmit('input', 'users_detail.php');
}

function reissuePassword(list_no)
{
	var user_id = document.getElementById('user_id').value;
	var passwd = document.getElementById('password').value;

	var pwd_chk = true;
	if (list_no != "0")
	{
		if (document.getElementById('copy_main_passwd').checked)
		{
			passwd = "";
		}
		pwd_chk = !document.getElementById('copy_main_passwd').checked;
	}

	if (passwd == "" && pwd_chk)
	{
		alert("パスワードを入力してください。");
		return;
	}

	if (!confirm("パスワードを再発行します。\n「OK」を押すと自動的にパスワードが再発行され元には戻せません。\n本当によろしいですか？"))
	{
		return;
	}


	new Ajax.Request('ajax/reissuePassword.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							if (request.responseText == '1')
							{
								if (passwd != "")
								{
									document.getElementById('password').value = "";
								}
								printPassword();
							}
							else
							{
								alert("処理に失敗しました");
							}
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'list_no='+encodeURIComponent(list_no)+'&user_id='+encodeURIComponent(user_id)+'&passwd='+encodeURIComponent(passwd)
					});
}

function printPassword()
{
	if (confirm("パスワードを再発行しました。\n再発行証明書印刷画面を表示しますか？"))
	{
		window.open("print_password.html", "pwPrint", 'directories=no,menubar=no,width=800,resizeble=no,status=no,scrollbars=yes');
		formSubmit("view", "print_password.html", "pwPrint");
	}
}

function editModeChange(mode)
{
	document.getElementById('edit_mode').value = mode;
	formSubmit('input', 'users_detail.php');
}

function historyEdit(history_no)
{
	document.getElementById('history_no').value = history_no;
	editModeChange('history');
}

function editModeReset()
{
	if (!confirm("入力されたデータは反映されません。よろしいですか？"))
	{
		return;
	}
	document.getElementById('edit_mode').value = "";
	formSubmit('input', 'users_detail.php');
}

function deleteReserveData()
{
	if (!confirm("予約を取り消します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('edit_mode').value = "";
	formSubmit('delReserve', 'users_detail.php');
}

function changeImmediateFlg()
{
	document.getElementById('send_date').disabled = !document.getElementById('immediate_flg_2').checked;
}

function changeMainData()
{
	if (!confirm("このデータをメインに変更します。よろしいですか？"))
	{
		return;
	}

	formSubmit('changeMain', 'users_detail.php');
}

function setToday(id)
{
	obj = document.getElementById(id);
	var nowdate = new Date();
	var year = String(nowdate.getFullYear()); // 年
	var mon  = String(nowdate.getMonth() + 1); // 月
	var date = String(nowdate.getDate()); // 日

	if (mon.length < 2)
	{
		mon = "0" + mon;
	}
	if (date.length < 2)
	{
		date = "0" + date;
	}

	obj.value = year + "/" + mon + "/" + date;
}

function retireUser()
{
	if (!confirm("退職日を設定します。よろしいですか？"))
	{
		return;
	}

	formSubmit('retireHis', 'users_detail.php');

}
