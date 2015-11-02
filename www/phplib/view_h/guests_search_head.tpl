<script src="js/prototype.js"></script>
<script type="text/javascript" src="js/jkl-calendar.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--
var cal_etd = new JKL.Calendar("caldiv_etd","mainForm","entry_date");
cal_etd.setStyle( "frame_color", "#3333CC" );
cal_etd.setStyle( "typestr", "yyyy/mm/dd" );

function sort(order)
{
	document.mainForm.order.value = order;
	formSubmit('sort', 'guests_search.php');
}

function search()
{
	formSubmit('search', 'guests_search.php');
}

function clearCond()
{
	document.getElementById('guest_name').value = "";
	document.getElementById('company_name').value = "";
	document.getElementById('mac_addr').value = "";
	document.getElementById('entry_date').value = "";
	document.getElementById('all_data_flg').checked = false;
}

function guestsDetail(id)
{
	document.getElementById('guest_id').value = id;
	formSubmit('view', 'guests_detail.php');
}

function guestsDelete(id)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('guest_id').value = id;
	formSubmit('delete', 'guests_search.php');
}

function printGuests()
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
				params += 'guest_id['+count+']='+encodeURIComponent(document.mainForm.elements['checked_id[]'][i].value);
				count ++;
			}
		}
	} else {
		// チェックボックスが１つのとき（リストが１件のとき）
		if (document.mainForm.elements['checked_id[]'].checked) {
			params += 'guest_id[0]='+encodeURIComponent(document.mainForm.elements['checked_id[]'].value);
		}
	}

	if (params == "") {
		alert("対象データが選択されていません");
		return;
	}
	
	new Ajax.Request('ajax/printGuests.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							if (request.responseText == '1')
							{
								openGuestsPrintpage();
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

function openGuestsPrintpage()
{
	if (confirm("ゲストID通知書印刷画面を表示しますか？"))
	{
		window.open("print_guests.html", "pwPrint", 'directories=no,menubar=no,width=800,resizeble=no,status=no,scrollbars=yes');
		formSubmit("view", "print_guests.html", "pwPrint");
	}
}

//-->
</script>
