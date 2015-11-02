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

var cal_expiry = new JKL.Calendar("caldiv_expiry","mainForm","expiry_date");
cal_expiry.setStyle( "frame_color", "#3333CC" );
cal_expiry.setStyle( "typestr", "yyyy/mm/dd" );

function editMember()
{
	if (!confirm("この内容で更新します。よろしいですか？"))
	{
		return;
	}
	formSubmit('edit', 'vpns_members_edit.php');
}

function resetMember()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'vpns_members_edit.php');
}

function returnList()
{
	document.getElementById('mail_addr').value = "";
	document.getElementById('passwd').value = "";
	document.getElementById('kanjiname').value = "";
	document.getElementById('kananame').value = "";
	document.getElementById('company').value = "";
	document.getElementById('contact').value = "";
	document.getElementById('expiry_date').value = "";
	document.getElementById('note').value = "";
	formSubmit('list', 'vpns_members.php');
}

//-->
</script>
