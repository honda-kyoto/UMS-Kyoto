<?php include("view/head.tpl") ?>
      <div id="tabs1">
        <ul>
          <li><a href="javascript:;" onclick="formSubmit('init');"><span <?php echo ($mgr->display_mode == 'base') ? 'class="curtab"' : "" ?>>基本情報</span></a></li>
<!--
          <li><a href="javascript:;" onclick="formSubmit('wireless');"><span <?php echo ($mgr->display_mode == 'wireless') ? 'class="curtab"' : "" ?>>無線LAN設定</span></a></li>
-->
<?php if ($mgr->getOutputData('joukin_kbn') == JOUKIN_KBN_FULLTIME || $mgr->getOutputData('joukin_kbn') == JOUKIN_KBN_PARTTIME) { ?>
          <li><a href="javascript:;" onclick="formSubmit('salary');"><span <?php echo ($mgr->display_mode == 'salary') ? 'class="curtab"' : "" ?>>給与明細</span></a></li>
<?php } ?>
        </ul>
      </div>
      <div style="clear:both;"></div>
      <div id="mypageBox">
<?php
switch ($mgr->display_mode)
{
	case 'wireless':
		include ("view/mypage/wireless.tpl");
		break;
	case 'oldmail':
		include ("view/mypage/oldmail.tpl");
		break;
	case 'salary':
		include ("view/mypage/salary.tpl");
		break;
	default:
		include ("view/mypage/base.tpl");
		break;
}
?>
      </div>
<?php include("view/foot.tpl") ?>
