<script type="text/javascript" src="js/jkl-calendar.js" charset="UTF-8"></script>
<script type="text/javascript">
<!--
var cal_ifd = new JKL.Calendar("caldiv_ifd","mainForm","issue_from");
cal_ifd.setStyle( "frame_color", "#3333CC" );
cal_ifd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_itd = new JKL.Calendar("caldiv_itd","mainForm","issue_to");
cal_itd.setStyle( "frame_color", "#3333CC" );
cal_itd.setStyle( "typestr", "yyyy/mm/dd" );

function sort(order)
{
	document.mainForm.order.value = order;
	formSubmit('sort', 'user_card_issue_list.php');
}

function search()
{
	formSubmit('search', 'user_card_issue_list.php');
}

function clearCond()
{
	document.getElementById('issue_from').value = "";
	document.getElementById('issue_to').value = "";
	document.getElementById('key_number').value = "";

	for (i = 0 ; i< document.mainForm.search_option.length ; i++)
	{
		if (document.mainForm.search_option[i].value == '0')
		{
			document.mainForm.search_option[i].checked = true;
		}
	}

	for (i = 0 ; i< document.mainForm.data_type.length ; i++)
	{
		if (document.mainForm.data_type[i].value == '0')
		{
			document.mainForm.data_type[i].checked = true;
		}
	}

}

function outputCardData()
{
	if (!confirm("ダウンロードします。よろしいですか？"))
	{
		return;
	}
	formSubmit('outputData', 'user_card_issue_list.php');
}

function usersEdit(id)
{
	document.getElementById('user_id').value = id;
	formSubmit('init', 'users_detail.php');
}


//-->
</script>
