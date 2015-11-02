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

	jQuery('select#joukin_kbn').ieSelectWidth
	({
		containerClassName : 'select-container',
		overlayClassName : 'select-overlay'
	});

});

function sort(order)
{
	document.mainForm.order.value = order;
	formSubmit('sort');
}

function search()
{
	formSubmit('search');
}

function clearCond()
{
	document.getElementById('login_id').value = "";
	document.getElementById('staffcode').value = "";
	document.getElementById('kananame').value = "";
	document.getElementById('job_id').selectedIndex = 0;
	document.getElementById('post_id').selectedIndex = 0;
	document.getElementById('joukin_kbn').selectedIndex = 0;
	document.getElementById('belong_class_id').selectedIndex = 0;
	document.getElementById('belong_div_id').selectedIndex = 0;
	document.getElementById('belong_dep_id').selectedIndex = 0;
	document.getElementById('belong_sec_id').selectedIndex = 0;
	document.getElementById('belong_chg_id').selectedIndex = 0;

	var len = document.mainForm.elements.length; /* フォームの要素数 */
	for (var i = 0 ; i < len ; i++)
	{
		name = document.mainForm.elements[i].name;
		name = name.substring(0,12);

		if (name == "user_type_id")
		{
			document.mainForm.elements[i].checked = false;
		}
	}

	for (i = 0 ; i< document.mainForm.search_option.length ; i++)
	{
		if (document.mainForm.search_option[i].value == '2')
		{
			document.mainForm.search_option[i].checked = true;
		}
	}
}

function usersEdit(id)
{
	document.getElementById('user_id').value = id;
	formSubmit('init', 'users_detail.php');
//	formSubmit('input', 'users_edit.php');
}

function setAdminId(admin_id, admin_name)
{
	var keyStr = window.opener.document.getElementById("maxAdminListKey").value;
	var key = parseInt(keyStr) + 1;

	var tbody=window.opener.document.getElementById("adminList");

	var tr_tag = window.opener.document.createElement("tr");
	var td_tag1 = window.opener.document.createElement("td");
	var td_tag2 = window.opener.document.createElement("td");

	tbody.appendChild(tr_tag);
	tr_tag.appendChild(td_tag1);
	tr_tag.appendChild(td_tag2);

	td_tag1.style.padding = "2px";
	td_tag1.innerHTML = '<input type="hidden" name="admin_id['+key+']" id="admin_id_'+key+'" value="'+admin_id+'">'+admin_name+'</td>';
	td_tag2.innerHTML = '[<a href="javascript:;" onclick="removeAdminList(this);">削除</a>]';

	window.opener.document.getElementById("maxAdminListKey").value = key;

	window.close();
}

function setAppUserId(user_id, user_name)
{
	window.opener.document.getElementById('appUserName').innerHTML = user_name;
	window.opener.document.getElementById('app_user_id').value = user_id;
	window.close();
}


function setVlanAdminId(admin_id)
{
	if (!confirm("設定します。よろしいですか？"))
	{
		return;
	}

	window.opener.document.mainForm.user_id.value = admin_id;
	window.opener.document.mainForm.mode.value = 'add';
	window.opener.document.mainForm.target = "_self";
	window.opener.document.mainForm.action = "vlan_admin_list.php";
	window.opener.document.mainForm.submit();

	window.close();
}

//-->
</script>
