<?php if ($mgr->mlist_kbn == MLIST_KBN_AUTO) { ?>
<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
<!--
jQuery.noConflict();
//-->
</script>
<script src="js/jquery.ie-select-width.js"></script>
<?php } ?>
<script type="text/javascript">
<!--

function addMember()
{
	if (!confirm("追加します。よろしいですか？"))
	{
		return;
	}
	formSubmit('add');
}

function deleteMember(target_addr)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('target_addr').value = target_addr;
	formSubmit('delete');
}

function returnList()
{
	formSubmit('return', 'mlists_search.php');
}

<?php if ($mgr->mlist_kbn == MLIST_KBN_AUTO) { ?>
<?php   include("view_h/users/belong_func.tpl") ?>

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

function addAutoCond()
{
	var job_id = document.getElementById('job_id').value;
	var post_id = document.getElementById('post_id').value;
	var joukin_kbn = document.getElementById('joukin_kbn').value;
	var class_id = document.getElementById('belong_class_id').value;
	var div_id = document.getElementById('belong_div_id').value;
	var dep_id = document.getElementById('belong_dep_id').value;
	var sec_id = document.getElementById('belong_sec_id').value;
	var chg_id = document.getElementById('belong_chg_id').value;
	var msg = "";
	if (job_id == "" && post_id == "" && joukin_kbn == "" && class_id == "" && div_id == "" && dep_id == "" && sec_id == "" && chg_id == "")
	{
		msg = "この操作を行うと対象が全ての利用者となり\n既に抽出条件が登録されている場合全て削除されます。\nよろしいですか？";
	}
	else
	{
		msg = "追加します。よろしいですか？";
	}
	if (!confirm(msg))
	{
		return;
	}
	formSubmit('push', 'mlists_members.php');
}

function deleteAutoCond(no)
{
	if (!confirm("削除します。よろしいですか？"))
	{
		return;
	}
	document.getElementById('list_no').value = no;
	formSubmit('pop', 'mlists_members.php');
}

function cancelWork()
{
	if (!confirm("設定内容をすべて元に戻します。よろしいですか？"))
	{
		return;
	}
	formSubmit('cancel', 'mlists_members.php');
}

function commitWork()
{
	if (!confirm("設定を完了します。よろしいですか？"))
	{
		return;
	}
	formSubmit('commit', 'mlists_members.php');
}

function viewAutoMembers()
{
	window.open('about:blank', 'viewMem', 'directories=no,menubar=no,width=1024,height=600,resizeble=no,status=no,scrollbars=yes');
	formSubmit('view', 'mlist_auto_members.php', 'viewMem');
}

<?php } ?>

//-->
</script>
