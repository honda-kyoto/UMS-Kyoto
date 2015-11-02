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
<meta http-equiv="x-ua-compatible" content="IE=10" >
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE10" >
<link rel="stylesheet" type="text/css" href="css/import.css" media="all" />
<!-- InstanceBeginEditable name="head" -->
<script src="js/common.js"></script>
<?php include($mgr->script_dir . "/".$mgr->header_file) ?>
<!-- InstanceEndEditable -->
</head>
<body>
<div id="header">
	<div class="inner">
		<h1><img src="image/logo.png" alt="利用者管理システム" width="235" height="42" /></h1>
	</div>
</div>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" name="mainForm" id="mainForm">
  <!-- InstanceBeginEditable name="subHeader" -->
  <div id="subHeader"><div class="inner fix">
<?php if ($mgr->getLoginData('LOGIN_USER_ID') != '') { ?>
    <div id="loginUser">ようこそ　<?php echo $mgr->getLoginData('LOGIN_USER_NAME') ?>さん（<?php echo $mgr->getLoginData('LOGIN_BELONG_NAME') ?>）　前回ログイン日時：<?php echo $mgr->getLoginData('LAST_LOGIN_TIME') ?></div>
    <ul id="subLink">
      <li><a href="logout.php"><img src="image/btn_logout.gif" title="ログアウト" width="100" height="26" /></a></li>
<?php   if (basename($_SERVER['SCRIPT_NAME']) != 'menu.php' && basename($_SERVER['SCRIPT_NAME']) != 'init_passwd.php' && basename($_SERVER['SCRIPT_NAME']) != 'init_mail.php' && basename($_SERVER['SCRIPT_NAME']) != 'mail_reissue.php') { ?>
      <li><a href="menu.php"><img src="image/btn_menu.gif" alt="メニューに戻る" width="120" height="26" /></a></li>
<?php   } ?>
    </ul>
<?php } ?>
  </div></div>
  <!-- InstanceEndEditable -->
  <div id="main"><div class="inner">
    <h2 class="title"><!-- InstanceBeginEditable name="pageTitle" --><?php echo $mgr->page_title ?><!-- InstanceEndEditable --></h2>
    <div class="contents"><!-- InstanceBeginEditable name="pageContents" -->
      <input type="hidden" name="mode" value="">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="errMsg" id="errMsgArea"><?php echo $mgr->errMsg ?></span></td>
        </tr>
      </table>
