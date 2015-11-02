
function removeAdminList(obj)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}

	// tbody要素に指定したIDを取得し、変数「tbody」に代入
	var tbody=document.getElementById("adminList");
	// objの親の親のノードを取得し（つまりtr要素）、変数「tr」に代入
	var tr=obj.parentNode.parentNode;
	// 「tbody」の子ノード「tr」を削除
	tbody.removeChild(tr);
}

function searchAdminUser()
{
	window.open('about:blank', 'schAdmin', 'directories=no,menubar=no,width=1024,height=600,resizeble=no,status=no,scrollbars=yes');
	formSubmit('init', 'vpns_admin_search.php', 'schAdmin');
}