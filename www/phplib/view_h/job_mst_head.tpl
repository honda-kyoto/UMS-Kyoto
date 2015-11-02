<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/msts/dispnum_common.tpl") ?>

function insertJob()
{
	if (confirm("追加します。よろしいですか？"))
	{
		formSubmit('insert', 'job_mst.php');
	}
}

function updateJob(id)
{
	if (confirm("更新します。よろしいですか？"))
	{
		document.mainForm.job_id.value = id;
		formSubmit('update', 'job_mst.php');
	}
}

function deleteJob(id)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.job_id.value = id;
		formSubmit('delete', 'job_mst.php');
	}
}

function mstEditAll()
{
	if (confirm("全て更新します。よろしいですか？"))
	{
		formSubmit('editall', 'job_mst.php');
	}
}

//-->
</script>
