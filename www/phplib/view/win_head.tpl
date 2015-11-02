<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/base.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $mgr->page_title ?> - 利用者管理</title>
<!-- InstanceEndEditable -->
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="content-script-type" content="text/javascript" />
<link rel="stylesheet" type="text/css" href="css/import.css" media="all" />
<!-- InstanceBeginEditable name="head" -->
<script src="js/common.js"></script>
<?php include("view_h/".$mgr->header_file) ?>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header">
	<div class="inner">
		<h1><img src="image/logo.png" alt="利用者管理システム" width="285" height="42" /></h1>
	</div>
</div>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" name="mainForm" id="mainForm">
  <!-- InstanceBeginEditable name="subHeader" -->
  <div id="subHeader"><div class="inner fix">
    <ul id="subLink">
      <li><input type="button" name="button" id="button" value="　　閉じる　　" onclick="window.close();" /></li>
    </ul>
  </div></div>
  <!-- InstanceEndEditable -->
  <div id="main"><div class="inner">
    <h2 class="title"><!-- InstanceBeginEditable name="pageTitle" --><?php echo $mgr->page_title ?><!-- InstanceEndEditable --></h2>
    <div class="contents"><!-- InstanceBeginEditable name="pageContents" -->
      <input type="hidden" name="mode" value="">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="errMsg"><?php echo $mgr->errMsg ?></span></td>
        </tr>
      </table>
