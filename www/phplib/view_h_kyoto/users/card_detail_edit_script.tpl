//honda追加
<?php include("kyoto_card_code/card_detail_code_script.tpl"); ?>

var cal_sd = new JKL.Calendar("caldiv_sd","mainForm","start_date");
cal_sd.setStyle( "frame_color", "#3333CC" );
cal_sd.setStyle( "typestr", "yyyy/mm/dd" );
var cal_ed = new JKL.Calendar("caldiv_ed","mainForm","end_date");
cal_ed.setStyle( "frame_color", "#3333CC" );
cal_ed.setStyle( "typestr", "yyyy/mm/dd" );


function setKyotoCardData()
{
	if (!confirm("この内容で更新します。よろしいですか？"))
	{
		return;
	}

	formSubmit('updateKyotoCard');
}

function shiftCardTab(no)
{
	document.getElementById('list_no').value = no;
	formSubmit('input', 'users_detail.php');
}


function changeMainData()
{
	if (!confirm("このデータをメインに変更します。よろしいですか？"))
	{
		return;
	}

	formSubmit('changeKyotoCardMain', 'users_detail.php');
}
