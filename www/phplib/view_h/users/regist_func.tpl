<?php include("view_h/users/belong_func.tpl") ?>

jQuery(function (){
	jQuery('select#job_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#post_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#wardstatus').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#wardcode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#professionstatus').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#professioncode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#deptstatus').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#deptcode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#deptgroupcode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

<?php if (is_array($mgr->request['sub_belong_chg_id'])) { ?>
<?php   foreach ($mgr->request['sub_belong_chg_id'] AS $key => $dummy_id) { ?>

jQuery(function (){
	jQuery('select#sub_<?php echo $key ?>_belong_class_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_belong_div_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_belong_dep_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_belong_sec_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_belong_chg_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});
	jQuery('select#sub_<?php echo $key ?>_job_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_post_id').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});
});
<?php   } ?>
<?php } ?>

<?php if (is_array($mgr->request['sub_his_flg'])) { ?>
<?php   foreach ($mgr->request['sub_his_flg'] AS $key => $dummy_id) { ?>

	jQuery('select#sub_<?php echo $key ?>_wardstatus').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_wardcode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_professionstatus').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_professioncode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_deptstatus').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_deptcode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

	jQuery('select#sub_<?php echo $key ?>_deptgroupcode').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});
<?php   } ?>
<?php } ?>

});


function addBelongList(tbody_id)
{
	var keyStr = document.getElementById("maxBelongKey").value;
	var key = parseInt(keyStr) + 1;
	new Ajax.Updater("addBelongListJs", 'ajax/addBelongList.php',
					{
						asynchronous: true,
						evalScripts: true,
						onSuccess: function(request) {
							document.getElementById("maxBelongKey").value = key;
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'key='+encodeURIComponent(key)+'&tbody_id='+encodeURIComponent(tbody_id)
					});
}

function deleteBelongList(key, obj)
{
	var keyStr = document.getElementById("maxBelongKey").value;
	var maxKey = parseInt(keyStr);

	// 一つずつ上に詰める
	for (var i = key ; i < maxKey ; i++)
	{
		var next = i+1;
		document.getElementById("sub_" + i + "_belong_class_id").selectedIndex = document.getElementById("sub_" + next + "_belong_class_id").selectedIndex;
		document.getElementById("sub_" + i + "_belong_div_id").selectedIndex = document.getElementById("sub_" + next + "_belong_div_id").selectedIndex;
		document.getElementById("sub_" + i + "_belong_dep_id").selectedIndex = document.getElementById("sub_" + next + "_belong_dep_id").selectedIndex;
		document.getElementById("sub_" + i + "_belong_sec_id").selectedIndex = document.getElementById("sub_" + next + "_belong_sec_id").selectedIndex;
		document.getElementById("sub_" + i + "_belong_chg_id").selectedIndex = document.getElementById("sub_" + next + "_belong_chg_id").selectedIndex;
		document.getElementById("sub_" + i + "_job_id").selectedIndex = document.getElementById("sub_" + next + "_job_id").selectedIndex;
		document.getElementById("sub_" + i + "_post_id").selectedIndex = document.getElementById("sub_" + next + "_post_id").selectedIndex;
	}

	// tbody要素に指定したIDを取得し、変数「tbody」に代入
	var tbody=document.getElementById("subBelongList");

	// 一番下の大分類を取得
	obj = document.getElementById("sub_" + keyStr + "_belong_class_id");
	// objの親の親のノードを取得し（つまりtr要素）、変数「tr」に代入
	var tr=obj.parentNode.parentNode;
	// 「tbody」の子ノード「tr」を削除
	tbody.removeChild(tr);

	document.getElementById("maxBelongKey").value = maxKey - 1;
}

function addJobList(tbody_id)
{
	var keyStr = document.getElementById("maxJobKey").value;
	var key = parseInt(keyStr) + 1;
	new Ajax.Updater("addJobListJs", 'ajax/addJobList.php',
					{
						asynchronous: true,
						evalScripts: true,
						onSuccess: function(request) {
							document.getElementById("maxJobKey").value = key;
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'key='+encodeURIComponent(key)+'&tbody_id='+encodeURIComponent(tbody_id)
					});
}

function addPostList(tbody_id)
{
	var keyStr = document.getElementById("maxPostKey").value;
	var key = parseInt(keyStr) + 1;
	new Ajax.Updater("addPostListJs", 'ajax/addPostList.php',
					{
						asynchronous: true,
						evalScripts: true,
						onSuccess: function(request) {
							document.getElementById("maxPostKey").value = key;
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'key='+encodeURIComponent(key)+'&tbody_id='+encodeURIComponent(tbody_id)
					});
}

function makeLoginId()
{
	var eijisei = document.getElementById('eijisei').value;
	var eijimei = document.getElementById('eijimei').value;

	if (eijisei == "" || eijimei == "")
	{
		alert("氏名英字を全て入力してください");
		return;
	}

	new Ajax.Request('ajax/makeLoginId.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							document.getElementById('login_id').value = request.responseText;
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'eijisei='+encodeURIComponent(eijisei)+'&eijimei='+encodeURIComponent(eijimei)
					});
}

function makePassword(id)
{
	new Ajax.Request('ajax/makePassword.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							document.getElementById(id).value = request.responseText;
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						}
					});
}

function existsLoginId()
{
	var login_id = document.getElementById('login_id').value;
	var user_id = "";
	if (document.getElementById('user_id'))
	{
		user_id = document.getElementById('user_id').value;
	}

	if (login_id == "")
	{
		alert("統合IDを全て入力してください");
		return;
	}

	new Ajax.Request('ajax/existsLoginId.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							alert(request.responseText);
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'login_id='+encodeURIComponent(login_id)+'&user_id='+encodeURIComponent(user_id)
					});
}

function existsPbno()
{
	var pbno = document.getElementById('pbno').value;
	var user_id = "";
	if (document.getElementById('user_id'))
	{
		user_id = document.getElementById('user_id').value;
	}

	if (pbno == "" || pbno.length != 4)
	{
		alert("PHS番号を数字4桁で入力してください");
		return;
	}

	new Ajax.Request('ajax/existsPbno.php',
					{
						asynchronous: true,
						evalScripts: true,
						onComplete: function(request) {
							alert(request.responseText);
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'pbno='+encodeURIComponent(pbno)+'&user_id='+encodeURIComponent(user_id)
					});
}
function changeHisFlg(obj, prefix)
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

	if (document.getElementById('user_id'))
	{
		if (!obj.checked)
		{
			if (!confirm("この操作をすると電カルからデータが削除されます。よろしいですか？"))
			{
				obj.checked = !obj.checked;
				return;
			}
		}
	}


	var dsbld = !obj.checked;

	document.getElementById(pfx+'send_date').disabled = dsbld;
	document.getElementById(pfx+'staffcode').disabled = dsbld;
	document.getElementById(pfx+'kanjiname').disabled = dsbld;
	document.getElementById(pfx+'kananame').disabled = dsbld;
	if (document.getElementById(pfx+'password'))
	{
		document.getElementById(pfx+'password').disabled = dsbld;
	}
	else
	{
		document.getElementById(pfx+'password_btn').disabled = dsbld;
	}
	document.getElementById(pfx+'wardstatus').disabled = dsbld;
	document.getElementById(pfx+'wardcode').disabled = dsbld;
	document.getElementById(pfx+'professionstatus').disabled = dsbld;
	document.getElementById(pfx+'professioncode').disabled = dsbld;
	document.getElementById(pfx+'gradecode').disabled = dsbld;
	document.getElementById(pfx+'deptstatus').disabled = dsbld;
	document.getElementById(pfx+'deptcode').disabled = dsbld;
	document.getElementById(pfx+'deptgroupcode').disabled = dsbld;
	document.getElementById(pfx+'appcode').disabled = dsbld;
	document.getElementById(pfx+'validstartdate').disabled = dsbld;
	document.getElementById(pfx+'validenddate').disabled = dsbld;
	if (dsbld)
	{
		if (document.getElementById(pfx+'password'))
		{
			document.getElementById(pfx+'password_create').style.display = 'none';
			document.getElementById(pfx+'password_text').style.display = '';
		}
		document.getElementById(pfx+'appcode_create').style.display = 'none';
		document.getElementById(pfx+'appcode_text').style.display = '';
	}
	else
	{
		if (document.getElementById(pfx+'password'))
		{
			document.getElementById(pfx+'password_create').style.display = '';
			document.getElementById(pfx+'password_text').style.display = 'none';
		}
		document.getElementById(pfx+'appcode_create').style.display = '';
		document.getElementById(pfx+'appcode_text').style.display = 'none';
	}

	var isInit = document.getElementById(pfx+'his_init').value;

	if (obj.checked && isInit == '1')
	{
		document.getElementById(pfx+'kanjiname').value = document.getElementById('kanjisei').value + "　" + document.getElementById('kanjimei').value;
		document.getElementById(pfx+'kananame').value = document.getElementById('kanasei').value + "　" + document.getElementById('kanamei').value;
		document.getElementById(pfx+'his_init').value = "0";
	}

	document.getElementById(pfx+'validenddate').value = '2099/12/31';
}

function wardstatusChange(status, prefix)
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

	new Ajax.Updater(pfx+"wardstatusJs",'ajax/wardstatusChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'wardstatus='+encodeURIComponent(status)+'&prefix='+encodeURIComponent(pfx)
					});
}

function professionstatusChange(status, prefix)
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

	new Ajax.Updater(pfx+"professionstatusJs",'ajax/professionstatusChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'professionstatus='+encodeURIComponent(status)+'&prefix='+encodeURIComponent(pfx)
					});
}

function deptstatusChange(status, prefix)
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

	new Ajax.Updater(pfx+"deptstatusJs",'ajax/deptstatusChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'deptstatus='+encodeURIComponent(status)+'&prefix='+encodeURIComponent(pfx)
					});
}

function deptcodeChange(code, prefix)
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

	new Ajax.Updater(pfx+"deptstatusJs",'ajax/deptcodeChange.php',
					{
						asynchronous: true,
						evalScripts: true,
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: function (request) {
							alert('読み込み中にエラーが発生しました');
						},
						parameters: 'deptcode='+encodeURIComponent(code)+'&prefix='+encodeURIComponent(pfx)
					});
}


function addHisDataList(tbody_id)
{
	if (!document.getElementById('his_flg').checked)
	{
		alert("電カル連携情報（メイン）から入力してください。");
		return;
	}

	var keyStr = document.getElementById("maxHisDataKey").value;
	var kanjiname = document.getElementById('kanjisei').value + "　" + document.getElementById('kanjimei').value;
	var kananame = document.getElementById('kanasei').value + "　" + document.getElementById('kanamei').value;

	if (keyStr == "")
	{
		keyStr = "0";
	}
	var key = parseInt(keyStr) + 1;
	new Ajax.Updater("addHisDataListJs", 'ajax/addHisDataList.php',
					{
						asynchronous: true,
						evalScripts: true,
						onSuccess: function(request) {
							document.getElementById("maxHisDataKey").value = key;
						},
						onFailure: function(request) {
							alert('読み込みに失敗しました');
						},
						onException: AjaxException,
						//function (request) {
						//	alert('読み込み中にエラーが発生しました');
						//	alert(request.message);
						//},
						parameters: 'key='+encodeURIComponent(key)+'&tbody_id='+encodeURIComponent(tbody_id)+'&kanjiname='+encodeURIComponent(kanjiname)+'&kananame='+encodeURIComponent(kananame)
					});
}
function AjaxException(xmlobj, e)
{
	// 例外が発生した時の処理
	alert('例外です：' + e.message);
}

function makeAppCode(prefix)
{
	var pfx;
	switch (arguments.length)
	{
		case 0:
			pfx = "";
			break;
		case 1:
			pfx = prefix;
			break;
		default:
			break;
	}

	var deptgroupcode = document.getElementById(pfx+'deptgroupcode').value;
	var pbno = document.getElementById('pbno').value;

	if (deptgroupcode == "" || pbno == "")
	{
		alert("PHS番号と診療科グループを設定してください。");
		return;
	}

	var appcode = deptgroupcode + pbno.substring(1);

	document.getElementById(pfx+'appcode').value = appcode;
}

function changeAccText(obj)
{
	document.getElementById('mail_acc').disabled = obj.checked;
}
