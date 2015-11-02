<link rel="stylesheet" href="css/jquery.cluetip.css" type="text/css" />
<script src="js/jquery-1.7.1.min.js"></script>
<script src ="js/themeswitchertool.js"></script>
<script src="js/jquery.hoverIntent.js"></script>
<script src="js/jquery.bgiframe.min.js"></script>
<script src="js/jquery.cluetip.js"></script>
<script src="js/jquery.ie-select-width.js"></script>
<script>
<!--

<?php if ($mgr->display_mode == 'base') { ?>

$(document).ready(function() {

  $('a.mailsave').cluetip({splitTitle: '|'});
});


function typeChange(obj)
{
	if (!confirm("転送設定を変更します。よろしいですか？"))
	{
		obj.checked = !obj.checked;
		return;
	}
	formSubmit('change');
}

function addSendonAddr()
{
	if (!confirm("転送先を追加します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add');
}

function delSendonAddr(no)
{
	if (!confirm("転送先を削除します。よろしいですか？"))
	{
		return;
	}
	document.mainForm.list_no.value = no;
	formSubmit('delete');
}

function passwdChange()
{
	if (!confirm("パスワードを変更します。よろしいですか？"))
	{
		return;
	}
	formSubmit('passwd');
}
<?php } else if ($mgr->display_mode == 'wireless') { ?>

jQuery(function (){

<?php   if (is_array($mgr->output['app_name']) && count($mgr->output['app_name']) > 0) { ?>
<?php     foreach ($mgr->output['app_name'] AS $app_id => $dummy) { ?>

		jQuery('select#vlan_id_<?php echo $app_id ?>').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});
<?php     } ?>
<?php   } ?>

});

function changeWirelessVlan(app_id)
{
	if (!confirm("使用VLANを変更します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('app_id').value = app_id;
	formSubmit('vlan');
}
<?php } else if ($mgr->display_mode == 'salary') { ?>

function passwdChange()
{
	if (!confirm("パスワードを変更します。よろしいですか？"))
	{
		return;
	}
	formSubmit('passwdSalary');
}

<?php } ?>

//-->
</script>
