<script src="js/prototype.js"></script>
<script type="text/javascript">
<!--


function returnList()
{
	formSubmit('return', 'guests_search.php');
}

function deleteGuest()
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	formSubmit('delete');
}

function viewPasswd()
{
	var guest_id = document.getElementById("guest_id").value;

	new Ajax.Updater('passwd','ajax/viewGuestPasswd.php',
					{
						asynchronous: true,
						evalScripts: true,
						onSuccess: function(request) {
							document.getElementById('passwd_view').style.display = "none";
							document.getElementById('passwd_hide').style.display = "";
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'guest_id='+encodeURIComponent(guest_id)
					});

}

function hidePasswd()
{
	document.getElementById('passwd').innerHTML = "********";
	document.getElementById('passwd_view').style.display = "";
	document.getElementById('passwd_hide').style.display = "none";
}

//-->
</script>
