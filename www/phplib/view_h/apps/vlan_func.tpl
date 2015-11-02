

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

		jQuery('select#vlan_id').ieSelectWidth
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
						parameters: 'vlan_ridge_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)
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
						parameters: 'vlan_floor_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)
					});
}

function vlanRoomChange(id, prefix)
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

	new Ajax.Updater(pfx+"vlanRoomJs",'ajax/vlanRoomChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'vlan_room_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)
					});
}
