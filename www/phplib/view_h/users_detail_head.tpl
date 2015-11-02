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

<?php include($mgr->getScriptFileName("/users/".$mgr->getRequestData('ctrl_mode_name')."_detail_".$mgr->getRequestData('disp_type_name')."_script.tpl")) ?>

function shiftTab(mode, ctrl_mode_name)
{
	if (document.getElementById('edit_mode'))
	{
		document.getElementById('edit_mode').value = "";
	}
	if (document.getElementById('list_no'))
	{
		document.getElementById('list_no').value = "";
	}

	document.getElementById('ctrl_mode_name').value = ctrl_mode_name;
	formSubmit(mode, 'users_detail.php');
}

function editUser()
{
	if (!confirm("この内容で更新します。よろしいですか？"))
	{
		return;
	}
	formSubmit('update', 'users_detail.php');
}

function resetUser()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'users_detail.php');
}

function returnList()
{
	formSubmit('return', 'users_search.php');
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
	if (confirm("<?php echo MAIN_SYSTEM_NAME ?>ネット統合ID通知書印刷画面を表示しますか？"))
	{
		window.open("print_ncvcid.html", "pwPrint", 'directories=no,menubar=no,width=800,resizeble=no,status=no,scrollbars=yes');
		formSubmit("view", "print_ncvcid.html", "pwPrint");
	}
}
<?php include($mgr->getScriptDirName()."/users/regist_func.tpl") ?>

//-->
</script>
