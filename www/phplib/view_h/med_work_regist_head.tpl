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
	document.getElementById('kananame').value = "";

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





//-->
</script>
