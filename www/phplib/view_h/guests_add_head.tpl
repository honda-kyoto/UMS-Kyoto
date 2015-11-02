<script src="js/prototype.js"></script>
<link rel="stylesheet" href="css/jquery.cluetip.css" type="text/css" />
<script src="js/jquery-1.7.1.min.js"></script>
<script src ="js/themeswitchertool.js"></script>
<script src="js/jquery.hoverIntent.js"></script>
<script src="js/jquery.bgiframe.min.js"></script>
<script src="js/jquery.cluetip.js"></script>
<script type="text/javascript">
<!--
jQuery.noConflict();

jQuery(document).ready(function() {

	jQuery('#sticky').cluetip({sticky: true, closePosition: 'title', arrows: true });
});


function addGuest()
{
	if (!confirm("この内容で登録します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add', 'guests_add.php');
}

function resetGuest()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'guests_add.php');
}

//-->
</script>
