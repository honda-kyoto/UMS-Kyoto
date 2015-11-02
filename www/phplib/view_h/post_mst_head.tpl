<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/msts/dispnum_common.tpl") ?>

function insertPost()
{
	if (confirm("追加します。よろしいですか？"))
	{
		formSubmit('insert', 'post_mst.php');
	}
}

function updatePost(id)
{
	if (confirm("更新します。よろしいですか？"))
	{
		document.mainForm.post_id.value = id;
		formSubmit('update', 'post_mst.php');
	}
}

function deletePost(id)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.post_id.value = id;
		formSubmit('delete', 'post_mst.php');
	}
}

function mstEditAll()
{
	if (confirm("全て更新します。よろしいですか？"))
	{
		formSubmit('editall', 'post_mst.php');
	}
}

//-->
</script>
