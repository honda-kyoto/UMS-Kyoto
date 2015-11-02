
$(document).ready(function() {

  $('a.mailsave').cluetip({splitTitle: '|'});
});


function addMailAcount()
{
	if (!confirm("メールアカウントを追加します。よろしいですか？"))
	{
		return;
	}
	formSubmit('mailAcountAdd');
}

function delMailAcount(no)
{
	if (!confirm("メールアカウントを削除します。よろしいですか？"))
	{
		return;
	}
	document.mainForm.list_no.value = no;
	formSubmit('mailAcountDelete');
}

function typeChange(obj)
{
	if (!confirm("転送設定を変更します。よろしいですか？"))
	{
		obj.checked = !obj.checked;
		return;
	}
	formSubmit('sendonTypeChange');
}

function addSendonAddr()
{
	if (!confirm("転送先を追加します。よろしいですか？"))
	{
		return;
	}
	formSubmit('sendonAddrAdd');
}

function delSendonAddr(no)
{
	if (!confirm("転送先を削除します。よろしいですか？"))
	{
		return;
	}
	document.mainForm.list_no.value = no;
	formSubmit('sendonAddrDelete');
}

function addOldmailAddr()
{
	if (!confirm("エイリアスを追加します。よろしいですか？"))
	{
		return;
	}
	formSubmit('oldmailAddrAdd');
}

function delOldmailAddr(no)
{
	if (!confirm("エイリアスを削除します。よろしいですか？"))
	{
		return;
	}
	document.mainForm.list_no.value = no;
	formSubmit('oldmailAddrDelete');
}

function changeMailReissueFlg()
{
	if (!confirm("メールアドレス再設定フラグを変更します。よろしいですか？"))
	{
		return;
	}
	formSubmit('mailReissue', 'users_detail.php');
}

function setMailAccValid()
{
	if (!confirm("アカウントを有効にします。よろしいですか？"))
	{
		return;
	}
	formSubmit('mailAccValid', 'users_detail.php');
}

function setMailAccInvalid()
{
	if (!confirm("アカウントを無効にします。よろしいですか？"))
	{
		return;
	}
	formSubmit('mailAccInvalid', 'users_detail.php');
}

function setMailAccExcCmt()
{
	if (document.getElementById('exception_note').value == "")
	{
		alert("例外コメントを入力してください");
		return;
	}
	if (!confirm("例外コメントを保存します。よろしいですか？"))
	{
		return;
	}
	formSubmit('mailAccExcCmt', 'users_detail.php');
}
