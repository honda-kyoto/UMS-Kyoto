
jQuery(function (){
	jQuery('select#belong_class_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#belong_div_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#belong_dep_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#belong_sec_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});

		jQuery('select#belong_chg_id').ieSelectWidth
		({
			containerClassName : 'select-container',
			overlayClassName : 'select-overlay'
		});
});

function belongClassChange(id, prefix)
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

	new Ajax.Updater(pfx+"belongClassJs",'ajax/belongClassChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'belong_class_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)
					});
}

function belongDivChange(id, prefix)
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

	new Ajax.Updater(pfx+"belongDivJs",'ajax/belongDivChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'belong_div_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)
					});
}

function belongDepChange(id, prefix)
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

	new Ajax.Updater(pfx+"belongDepJs",'ajax/belongDepChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'belong_dep_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)
					});
}

function belongSecChange(id, prefix)
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

	new Ajax.Updater(pfx+"belongSecJs",'ajax/belongSecChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'belong_sec_id='+encodeURIComponent(id)+'&prefix='+encodeURIComponent(pfx)
					});
}
