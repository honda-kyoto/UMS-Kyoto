<script src="js/prototype.js"></script>
<link rel="stylesheet" href="css/jquery.cluetip.css" type="text/css" />
<script src="js/jquery-1.7.1.min.js"></script>
<script src ="js/themeswitchertool.js"></script>
<script src="js/jquery.hoverIntent.js"></script>
<script src="js/jquery.bgiframe.min.js"></script>
<script src="js/jquery.cluetip.js"></script>
<script type="text/javascript" src="js/jkl-calendar.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--
jQuery.noConflict();
//-->
</script>
<script src="js/jquery.ie-select-width.js"></script>
<script type="text/javascript">
<!--

jQuery(document).ready(function() {

  jQuery('a.login_id_str').cluetip({splitTitle: '|'});
});

var cal_sd = new JKL.Calendar("caldiv_sd","mainForm","start_date");
cal_sd.setStyle( "frame_color", "#3333CC" );
cal_sd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_ed = new JKL.Calendar("caldiv_ed","mainForm","end_date");
cal_ed.setStyle( "frame_color", "#3333CC" );
cal_ed.setStyle( "typestr", "yyyy/mm/dd" );
var cal_sdd = new JKL.Calendar("caldiv_sdd","mainForm","send_date");
cal_sdd.setStyle( "frame_color", "#3333CC" );
cal_sdd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_vsd = new JKL.Calendar("caldiv_vsd","mainForm","validstartdate");
cal_vsd.setStyle( "frame_color", "#3333CC" );
cal_vsd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_ved = new JKL.Calendar("caldiv_ved","mainForm","validenddate");
cal_ved.setStyle( "frame_color", "#3333CC" );
cal_ved.setStyle( "typestr", "yyyy/mm/dd" );

var sub_cal_sdd = new Array();
var sub_cal_vsd = new Array();
var sub_cal_ved = new Array();

function editUser()
{
	if (!confirm("この内容で更新します。よろしいですか？"))
	{
		return;
	}
	formSubmit('update', 'users_edit.php');
}

function resetUser()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'users_edit.php');
}

function returnList()
{
	formSubmit('return', 'users_search.php');
}

function reissuePassword(col_name, key)
{
	var user_id = document.getElementById('user_id').value;
	var passwd = "";
	var pwObj;
	var list_no;
	switch (arguments.length)
	{
		case 1:
			list_no = "0";
			break;
		case 2:
			list_no = key;
			if (key == "0")
			{
				pwObj = document.getElementById('password');
			}
			else
			{
				pwObj = document.getElementById('sub_'+key+'_password');
			}
			passwd = pwObj.value;
			if (passwd == "")
			{
				alert("パスワードを入力してください。");
				return;
			}
			break;
		default:
			break;
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
									pwObj.value = "";
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
						parameters: 'col_name='+encodeURIComponent(col_name)+'&list_no='+encodeURIComponent(list_no)+'&user_id='+encodeURIComponent(user_id)+'&passwd='+encodeURIComponent(passwd)
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

function printJunhisID(key)
{
	var user_id = document.getElementById('user_id').value;
	var list_no = key;
	
	new Ajax.Request('ajax/printJunhis.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							if (request.responseText == '1')
							{
								openJunhisIDPrintpage();
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
						parameters: 'list_no='+encodeURIComponent(list_no)+'&user_id='+encodeURIComponent(user_id)
					});
}

function openJunhisIDPrintpage()
{
	if (confirm("JUNHIS（電子カルテシステム）ID通知書印刷画面を表示しますか？"))
	{
		window.open("print_junhis.html", "pwPrint", 'directories=no,menubar=no,width=800,resizeble=no,status=no,scrollbars=yes');
		formSubmit("view", "print_junhis.html", "pwPrint");
	}
}

function printNcvcID()
{
	var user_id = document.getElementById('user_id').value;
	
	new Ajax.Request('ajax/printNcvcId.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							if (request.responseText == '1')
							{
								openNcvcIDPrintpage();
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
						parameters: 'user_id='+encodeURIComponent(user_id)
					});
}

function openNcvcIDPrintpage()
{
	if (confirm("NCVCネット統合ID通知書印刷画面を表示しますか？"))
	{
		window.open("print_ncvcid.html", "pwPrint", 'directories=no,menubar=no,width=800,resizeble=no,status=no,scrollbars=yes');
		formSubmit("view", "print_ncvcid.html", "pwPrint");
	}
}
<?php include("view_h/users/regist_func.tpl") ?>

//-->
</script>
