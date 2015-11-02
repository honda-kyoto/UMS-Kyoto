<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>利用者管理ID通知書</title>
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
<div style="width:600px;font-size:12px;" align="right"><?php echo $mgr->getOutputData('print_time') ?></div>
<table cellpadding="2" cellspacing="0" style="width:600px;">
<tr>
  <td width="20" align="center">&nbsp;</td>
  <td width="20" align="center">&nbsp;</td>
  <td width="110" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:14px;font-weight:bold;" colspan="4" align="left"><?php echo $mgr->getRequestData('belong_name') ?></td>
  <td width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="20" align="center">&nbsp;</td>
  <td style="font-size:14px;font-weight:bold;" colspan="3" align="left"><?php echo $mgr->getRequestData('job_name') ?></td>
  <td width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="20" align="center">&nbsp;</td>
  <td width="20" align="center">&nbsp;</td>
  <td style="font-size:18px;font-weight:bold;" colspan="2" align="left"><?php echo $mgr->getRequestData('kanjiname') ?>　様</td>
  <td width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="20" align="center">&nbsp;</td>
  <td width="20" align="center">&nbsp;</td>
  <td width="110" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td width="50" align="center">&nbsp;</td>
</tr>

</table>
<div style="height: 20px;"></div>
<h2 style="font-size:20px;">利用者管理ID通知書</h2>
<div style="width:600px;">
<div style="width:380px;font-size:10px;float:left;margin-bottom:5px;background: #CCC;">※アルファベットの大文字・小文字は区別されます。ご注意ください。※</div>
<table cellpadding="2" cellspacing="0" style="width:600px;clear:left;">
<tr>
  <td style="height: 40px;background: #CCC;border: #000 dashed 1px;font-weight:bold;font-size:14px;" align="center" colspan="6">&nbsp;利用者管理&nbsp;ID</td>
</tr>
<tr>
  <td style="border-color:#000;border-style:hidden hidden dashed dashed;border-width:1px;height: 40px;" width="20" align="center">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;font-size:14px;" width="120">利用者管理ID</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;" width="20">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;font-size:18px;" colspan="2" align="left"><span class="id"><?php echo $mgr->getRequestData('login_id') ?></span></td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="50" align="center">&nbsp;</td>
</tr>
<tr>
  <td style="border-color:#000;border-style:hidden hidden dashed dashed;border-width:1px;height: 60px;" width="20" align="center">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;font-size:14px;" width="120">仮パスワード</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;" width="20">&nbsp;</td>
  <td style="border-color:#000;border-style:hidden hidden dashed hidden;border-width:1px;" colspan="2" align="left">
    <table>
      <tr>
        <td style="font-size:14px;"><span class="passwd_furigana"><?php echo $mgr->getRequestData('login_passwd_furigana')?></span></td>
      </tr>
      <tr>
        <td style="font-size:18px;"><span class="passwd"><?php echo $mgr->getRequestData('login_passwd')?></span></td>
      </tr>
    </table>
  </td>
  <td style="border-color:#000;border-style:hidden dashed dashed hidden;border-width:1px;" width="50" align="center">&nbsp;</td>
</tr>
</table>
<div style="height: 20px;"></div>
<table cellpadding="2" cellspacing="0" style="width:600px;">
<tr>
  <td style="height: 20px;background: #CCC;border: #000 dashed 1px;font-weight:bold;font-size:14px;" align="center">お願い</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  <span style="font-weight:bold;">【通知書を受け取ったら】</span>
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;利用者管理システムにログインし、<span style="background: #CCC;">仮パスワードの変更</span>を
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;行って下さい。
  </td>
</tr>

<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed dashed dashed;border-width:1px;font-size:13px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;※利用者管理システムURL　　　http://10.255.211.240
  </td>
</tr>
</table>
<div style="height: 20px;"></div>
<table cellpadding="2" cellspacing="0" style="width:600px;">
<tr>
  <td style="height: 20px;background: #CCC;border: #000 dashed 1px;font-weight:bold;font-size:14px;" align="center">利用者管理システム</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">&nbsp;</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  URL　　http://10.255.211.240
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">&nbsp;</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
   ※IDとパスワードについて
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  &nbsp;&nbsp;・利用者管理IDとパスワードを入力してください。
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed dashed dashed;border-width:1px;font-size:13px;" align="left">
  &nbsp;&nbsp;・利用者管理システムにて変更された場合は、変更後のパスワードを入力してください。
  </td>
</tr>
</table>
<div style="height: 20px;"></div>
<table cellpadding="2" cellspacing="0" style="width:600px;">
<tr>
  <td style="height: 20px;background: #CCC;border: #000 dashed 1px;font-weight:bold;font-size:14px;" align="center">ご案内</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  この統合ID / パスワードは、利用者管理システムを利用する上で必要となりますので
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  この通知書は安全な場所に保管してください。
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;" align="left">&nbsp;</td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  <span style="font-weight:bold;">【問い合わせ先】</span>
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed hidden;border-width:1px;font-size:13px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;医療情報企画部　利用者管理システム 担当
  </td>
</tr>
<tr>
  <td style="height: 20px;border-color:#000;border-style:hidden dashed dashed dashed;border-width:1px;font-size:13px;" align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-mail：reyousys@kuhp.kyoto-u.ac.jp　内線：4205　（平日8:30 ～ 17:00）
  </td>
</tr>
</table>
</div>
</center>
</body>
</html>
