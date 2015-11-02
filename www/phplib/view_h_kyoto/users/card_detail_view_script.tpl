//honda
<?php include("kyoto_card_code/card_detail_code_script.tpl"); ?>

function shiftCardTab(no)
{
	document.getElementById('list_no').value = no;
	formSubmit('view', 'users_detail.php');
}
