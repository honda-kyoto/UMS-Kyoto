<script src="js/prototype.js"></script>
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
<!--

<?php include("view_h/msts/dispnum_common.tpl") ?>

function insertBelongDiv()
{
	if (confirm("追加します。よろしいですか？"))
	{
		formSubmit('insert', 'belong_div_mst.php');
	}
}

function updateBelongDiv(id)
{
	if (confirm("更新します。よろしいですか？"))
	{
		document.mainForm.belong_div_id.value = id;
		formSubmit('update', 'belong_div_mst.php');
	}
}

function deleteBelongDiv(id)
{
	if (confirm("削除します。よろしいですか？"))
	{
		document.mainForm.belong_div_id.value = id;
		formSubmit('delete', 'belong_div_mst.php');
	}
}

function mstEditAll()
{
	if (confirm("全て更新します。よろしいですか？"))
	{
		formSubmit('editall', 'belong_div_mst.php');
	}
}

function goBelongDeptMst(id)
{
	document.mainForm.belong_div_id.value = id;
	formSubmit('init', 'belong_dep_mst.php');
}

function returnClass()
{
	formSubmit('return', 'belong_class_mst.php');
}

function onChangeSelect( instance )
{

    var value = jQuery( instance, 'option :selected').val();
    var name = jQuery( instance ).children(':selected').text();
    var org = jQuery("input#org_belong_class_id").val();
    var check_box = jQuery("input[name^=belong_div_checkbox]:checked").size();
    if(check_box < 1){
        alert("チェックボックスにチェックがありません。");
        jQuery( instance ).val( org );    
        return;
    }
    if( confirm("選択した所属部門を " + name + " に変更します。\nよろしいですか？") ){

	jQuery( instance ).val( value );
	formSubmit('parentChange', 'belong_div_mst.php');

    }else{

	jQuery( instance ).val( org );

    }
}



//-->
</script>
