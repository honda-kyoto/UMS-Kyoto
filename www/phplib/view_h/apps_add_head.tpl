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
//-->
</script>
<script src="js/jquery.ie-select-width.js"></script>
<script type="text/javascript">
<!--

jQuery(document).ready(function() {

	jQuery('#sticky').cluetip({sticky: true, closePosition: 'title', arrows: true });
	jQuery('a.vlanmng').cluetip({splitTitle: '|'});
});

<?php if ($mgr->wire_kbn == WIRE_KBN_WLESS) { ?>

jQuery(function (){
	jQuery('select#wl_vlan_ridge_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#wl_vlan_floor_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#wl_vlan_room_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#wl_vlan_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});
});

<?php } ?>

function addApp()
{
	if (!confirm("この内容で登録します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add', 'apps_add.php');
}

function resetApp()
{
	if (!confirm("入力内容を全てリセットします。よろしいですか？"))
	{
		return;
	}
	formSubmit('input', 'apps_add.php');
}

function joinWirelessVlan()
{
	formSubmit('join', 'apps_add.php');
}

function defectWirelessVlan(vlan_id)
{
	document.getElementById('target_vlan_id').value = vlan_id;
	formSubmit('defect', 'apps_add.php');
}

<?php include("view_h/apps/regist_func.tpl") ?>

//-->
</script>
