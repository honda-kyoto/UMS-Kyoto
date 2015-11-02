<link rel="stylesheet" type="text/css" href="css/tooltip.css" media="all" />
<script src="js/tooltip.js"></script>
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


jQuery(document).ready(function() {
	jQuery("input[name=right]").click(function() {
		move("select_id", "device_id");
	});
	jQuery("input[name=left]").click(function() {
		move("device_id", "select_id");
	});
	function move(_this, target) {
		jQuery("select[id=" + _this + "] option:selected").each(function() {
			jQuery("select[id=" + target + "]").append(jQuery(this).clone());
			jQuery(this).remove();
		});
	}
});

function moveUpElement() {
  var selectbox = document.getElementById('device_id');
  var option_list = selectbox.getElementsByTagName('option');
  for (var i = 0; i < option_list.length; i++) {
    if (option_list[i].selected) {
      if (i > 0 && !option_list[i-1].selected) {
        selectbox.insertBefore(option_list[i], option_list[i-1]);
      }
    }
  }
  selectbox.focus();
}

function moveDownElement() {
  var selectbox = document.getElementById('device_id');
  var option_list = selectbox.getElementsByTagName('option');
  for (var i = option_list.length-1; i >= 0; i--) {
    if (option_list[i].selected) {
      if (i < option_list.length-1 && !option_list[i+1].selected) {
        selectbox.insertBefore(option_list[i+1], option_list[i]);
      }
    }
  }
  selectbox.focus();
}
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



function setDevice()
{
	if (!confirm("割り付けを行います。よろしいですか？"))
	{
		return;
	}
	jQuery('select[id="device_id"] option').each(function() {
jQuery(this).attr('selected', 'selected');
});
	formSubmit('update');
}

function resetDevice()
{
	if (!confirm("入力内容をすべてリセットします。よろしいですか？"))
	{
		return;
	}
	document.getElementById('vlan_ridge_id').selectedIndex = 0;
	document.getElementById('vlan_floor_id').selectedIndex = 0;
	document.getElementById('vlan_room_id').selectedIndex = 0;
	jQuery('select[id="device_id"] option').each(function() {
jQuery(this).attr('selected', false);
});
	formSubmit('input');
}

//-->
</script>
