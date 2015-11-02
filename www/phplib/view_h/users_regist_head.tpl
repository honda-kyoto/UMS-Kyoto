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

function addUser()
{
	if (!confirm("この内容で登録します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add');
}

function resetUser()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input');
}

<?php include($mgr->getScriptDirName()."/users/regist_func.tpl") ?>

//-->
</script>
