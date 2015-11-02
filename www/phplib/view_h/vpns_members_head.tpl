<script src="js/prototype.js"></script>
<script type="text/javascript" src="js/jkl-calendar.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--
var cal_expiry = new JKL.Calendar("caldiv_expiry","mainForm","expiry_date");
cal_expiry.setStyle( "frame_color", "#3333CC" );
cal_expiry.setStyle( "typestr", "yyyy/mm/dd" );

var cal_updexpiry = new JKL.Calendar("caldiv_updexpiry","mainForm","update_expiry_date");
cal_updexpiry.setStyle( "frame_color", "#3333CC" );
cal_updexpiry.setStyle( "typestr", "yyyy/mm/dd" );

function addMember()
{
	if (!confirm("追加します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add', 'vpns_members.php');
}

function editMembers(vpn_user_id)
{
	document.getElementById('vpn_user_id').value = vpn_user_id;
	formSubmit('input', 'vpns_members_edit.php');
}

function deleteMember(vpn_user_id)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('vpn_user_id').value = vpn_user_id;
	formSubmit('delete', 'vpns_members.php');
}

function returnList()
{
	formSubmit('return', 'vpns_search.php');
}


function viewPasswd(id)
{
	var pwdSpn = "passwd_" + id;
	var viewA = "passwd_view_" + id;
	var hideA = "passwd_hide_" + id;

	var vpn_id = document.getElementById("vpn_id").value;

	new Ajax.Updater(pwdSpn,'ajax/viewVpnPasswd.php',
					{
						asynchronous: true,
						evalScripts: true,
						onSuccess: function(request) {
							document.getElementById(viewA).style.display = "none";
							document.getElementById(hideA).style.display = "";
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'vpn_id='+encodeURIComponent(vpn_id)+'&vpn_user_id='+encodeURIComponent(id)
					});

}

function hidePasswd(id)
{
	var pwdSpn = "passwd_" + id;
	var viewA = "passwd_view_" + id;
	var hideA = "passwd_hide_" + id;

	document.getElementById(pwdSpn).innerHTML = "********";
	document.getElementById(viewA).style.display = "";
	document.getElementById(hideA).style.display = "none";
}

function printVpns()
{
	var params = "";

	// チェックされたcheckboxの数をチェック
	if (document.mainForm.elements['checked_id[]'].length) {
		count = 0;
		for (var i=0; i<document.mainForm.elements['checked_id[]'].length; i++) {
			if (document.mainForm.elements['checked_id[]'][i].checked) {
				if (count > 0) {
					params += '&';
				}
				params += 'vpn_user_id['+count+']='+encodeURIComponent(document.mainForm.elements['checked_id[]'][i].value);
				count ++;
			}
		}
	} else {
		// チェックボックスが１つのとき（リストが１件のとき）
		if (document.mainForm.elements['checked_id[]'].checked) {
			params += 'vpn_user_id[0]='+encodeURIComponent(document.mainForm.elements['checked_id[]'].value);
		}
	}

	if (params == "") {
		alert("対象データが選択されていません");
		return;
	}

	params += '&vpn_id='+encodeURIComponent(document.mainForm.vpn_id.value);
	
	new Ajax.Request('ajax/printVpns.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							if (request.responseText == '1')
							{
								openVpnsPrintpage();
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
						parameters: params
					});
}

function openVpnsPrintpage()
{
	if (confirm("VPN接続サービスID通知書印刷画面を表示しますか？"))
	{
		window.open("print_vpns.html", "pwPrint", 'directories=no,menubar=no,width=800,resizeble=no,status=no,scrollbars=yes');
		formSubmit("view", "print_vpns.html", "pwPrint");
	}
}

function updateExpiry()
{
	var params = "";

	// チェックされたcheckboxの数をチェック
	if (document.mainForm.elements['checked_id[]'].length) {
		count = 0;
		for (var i=0; i<document.mainForm.elements['checked_id[]'].length; i++) {
			if (document.mainForm.elements['checked_id[]'][i].checked) {
				if (count > 0) {
					params += '&';
				}
				params += 'vpn_user_id['+count+']='+encodeURIComponent(document.mainForm.elements['checked_id[]'][i].value);
				count ++;
			}
		}
	} else {
		// チェックボックスが１つのとき（リストが１件のとき）
		if (document.mainForm.elements['checked_id[]'].checked) {
			params += 'vpn_user_id[0]='+encodeURIComponent(document.mainForm.elements['checked_id[]'].value);
		}
	}

	if (params == "") {
		alert("対象データが選択されていません");
		return;
	}

	if (!confirm("有効期限を一括更新します。よろしいですか？"))
	{
		return;
	}
	
	formSubmit('update', 'vpns_members.php');
}

//-->
</script>
