<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VPN接続サービス ID通知書</title>
<style type="text/css">
<!--
body {
    font-family: Century,"ＭＳ 明朝";
}

span.id,
span.passwd {
    font-family:"Courier New";
}
span.passwd_furigana {
	font-family:"ＭＳ Ｐ明朝";
}

@media print {
   body {
       /*強制的にCSSを有効にする*/
      -webkit-print-color-adjust: exact;
   }
}
-->
</style>
</head>

<body>
<center>
<?php if ($mgr->getOutputData('print_cnt') > 0) { ?>
<?php   for ($index = 0; $index < $mgr->getOutputData('print_cnt'); $index++) { ?>
<?php     if ($index != 0) { ?>
<div style="page-break-before: always;height: 13px;"></div>
<?php     } ?>
<div style="width:600px;font-size:12px;" align="right"><?php echo $mgr->getOutputData('print_time', $index) ?></div>
<table cellpadding="2" cellspacing="0" border="0" style="width:600px;">
<tr>
  <td width="20" align="center">&nbsp;</td>
  <td width="120" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:14px;font-weight:bold;" colspan="4" align="left"><?php echo $mgr->getOutputData('company', $index) ?></td>
  <td width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="40" align="center">&nbsp;</td>
  <td style="font-size:18px;font-weight:bold;" colspan="3" align="left"><?php echo $mgr->getOutputData('kanjiname', $index) ?>　様</td>
  <td width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="20" align="center">&nbsp;</td>
  <td width="120" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td align="center">&nbsp;</td>
</tr>
</table>
<div style="height: 100px;"></div>
<h2 style="font-size:20px;">VPN接続サービス ID通知書</h2>
<div style="width:600px;">

<div style="width:380px;font-size:10px;float:left;margin-bottom:5px;background: #CCC;">※アルファベットの大文字・小文字は区別されます。ご注意ください。※</div>
<table cellpadding="2" cellspacing="0" style="width:600px;clear:left;">
<tr>
  <td style="height: 40px;background: #CCC;border: #000 dashed 1px;font-weight:bold;" align="center" colspan="6">VPN&nbsp;接続サービス&nbsp;ID</td>
</tr>
<tr>
  <td style="border-color:#000;border-style:hidden hidden dashed dashed;border-width:1px;height: 40px;" width="20" align="center">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="120">ID</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;" width="20">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;font-size:18px;" colspan="2" align="left"><span class="id"><?php echo $mgr->getOutputData('vpn_user_id', $index) ?></span></td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td style="border-color:#000;border-style:hidden hidden dashed dashed;border-width:1px;height: 60px;" width="20" align="center">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="120">パスワード</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;" width="20">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;" colspan="2" align="left">
    <table>
      <tr>
        <td style="font-size:12px;"><span class="passwd_furigana"><?php echo $mgr->getOutputData('password_furigana', $index)?></span></td>
      </tr>
      <tr>
        <td style="font-size:18px;"><span class="passwd"><?php echo $mgr->getOutputData('password', $index)?></span></td>
      </tr>
    </table>
  </td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td style="border-color:#000;border-style:hidden hidden dashed dashed;border-width:1px;height: 40px;" width="20" align="center">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="120">有効期限</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;" width="20">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;font-size:18px;" colspan="2" align="left"><?php echo $mgr->getOutputData('limit_period', $index)?></td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="50" align="center">&nbsp;</td>
</tr>
</table>

<div style="height: 30px;"></div>
<table cellpadding="2" cellspacing="0" style="width:600px;">
<tr>
  <td style="height: 40px;background: #CCC;border: #000 dashed 1px;font-weight:bold;" align="center">VPN&nbsp;接続用&nbsp;URL</td>
</tr>
<tr>
  <td style="height: 40px;border-color:#000;border-style:hidden dashed dashed dashed;border-width:1px;font-size:18pt;" align="center">
   https://xxxx
  </td>
</tr>
</table>

<div style="height: 30px;"></div>
<table cellpadding="2" cellspacing="0" style="width:600px;">
<tr>
  <td style="height: 20px;background: #CCC;border: #000 dashed 1px;font-weight:bold;" align="center">マニュアル</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;" align="left">
  VPN接続手順についてはポータルサイトをご覧下さい。 
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed dashed dashed;border-width:1px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;ポータルサイト：http://xxxx
  </td>
</tr>
</table>

<div style="height: 30px;"></div>
<table cellpadding="2" cellspacing="0" style="width:600px;">
<tr>
  <td style="height: 20px;background: #CCC;border: #000 dashed 1px;font-weight:bold;" align="center">ご案内</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;" align="left">
  この通知書は安全な場所に保管してください。
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;" align="left">
  <span style="font-weight:bold;">【問い合わせ先】</span>
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;xxxx
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed dashed dashed;border-width:1px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-mail：xxxx@kuhp.kyoto-u.ac.jp　内線：xxxx（平日9:00 ～ 18:00）
  </td>
</tr>
</table>
</div>
<?php   } ?>
<?php } ?>
</center>
</body>
</html>
