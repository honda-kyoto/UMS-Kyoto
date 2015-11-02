<script src="js/prototype.js"></script>
<script type="text/javascript">
<!--

function outputData(id)
{
    document.getElementById( "listTab" ).className = " AjaxTableDisable";
    document.getElementById( "nowLoad" ).className = "AjaxTableEnable";
	new Ajax.Request('ajax/dataExport.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							if (request.responseText != '')
							{
								document.getElementById( "listTab" ).className = "listTab";
								document.getElementById( "nowLoad" ).className = "AjaxTableDisable";
								downloadData(id, request.responseText);
							}
							else
							{
								alert("処理に失敗しました");
							}
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'user_role_id='+encodeURIComponent(id)
					});
}

function downloadData(id, file)
{
	document.getElementById('user_role_id').value = id;
	document.getElementById('file_name').value = file;
	formSubmit('download');
}

//-->
</script>
