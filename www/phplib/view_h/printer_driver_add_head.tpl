<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
<!--
jQuery.noConflict();
//-->
</script>
<script src="js/jquery.ie-select-width.js"></script>
<script type="text/javascript">
<!--


jQuery(function (){
	jQuery('select#vlan_ridge_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#vlan_floor_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#vlan_room_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});
});

function vlanRidgeChange(id, prefix)
{
	var pfx;
	switch (arguments.length)
	{
		case 1:
			pfx = "";
			break;
		case 2:
			pfx = prefix;
			break;
		default:
			break;
	}

	new Ajax.Updater(pfx+"vlanRidgeJs",'ajax/vlanRidgeChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'vlan_ridge_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)+'&vdi_mode=1'
					});
}


function vlanFloorChange(id, prefix)
{
	var pfx;
	switch (arguments.length)
	{
		case 1:
			pfx = "";
			break;
		case 2:
			pfx = prefix;
			break;
		default:
			break;
	}

	new Ajax.Updater(pfx+"vlanFloorJs",'ajax/vlanFloorChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'vlan_floor_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)+'&vdi_mode=1'
					});
}

function searchPrinter()
{
	formSubmit('search');
}

function printerSelect()
{
	formSubmit('select');
}

function setDriverName()
{
	if (!confirm("割り当てを行います。よろしいですか？"))
	{
		return;
	}
	formSubmit('update');
}

function resetDriver()
{
	if (!confirm("入力内容をすべてリセットします。よろしいですか？"))
	{
		return;
	}
	document.getElementById('vlan_ridge_id').selectedIndex = 0;
	document.getElementById('vlan_floor_id').selectedIndex = 0;
	document.getElementById('vlan_room_id').selectedIndex = 0;
	document.getElementById('unallocated_only').checked = true;
	document.getElementById('app_id').selectedIndex = 0;
	document.getElementById('driver_name').selectedIndex = 0;
	formSubmit('input');
}

//-->
</script>
