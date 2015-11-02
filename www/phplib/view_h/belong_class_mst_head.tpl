<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/msts/dispnum_common.tpl") ?>

function insertBelongClass()
{
	if (confirm("追加します。よろしいですか？"))
	{
		formSubmit('insert', 'belong_class_mst.php');
	}
}

function updateBelongClass(id)
{
	if (confirm("更新します。よろしいですか？"))
	{
		document.mainForm.belong_class_id.value = id;
		formSubmit('update', 'belong_class_mst.php');
	}
}

function deleteBelongClass(id)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.belong_class_id.value = id;
		formSubmit('delete', 'belong_class_mst.php');
	}
}

function mstEditAll()
{
	if (confirm("全て更新します。よろしいですか？"))
	{
		formSubmit('editall', 'belong_class_mst.php');
	}
}

function goBelongDivMst(id)
{
	document.mainForm.belong_class_id.value = id;
	formSubmit('init', 'belong_div_mst.php');
}

//-->
</script>
