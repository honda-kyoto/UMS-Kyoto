<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>仮パスワード再発行証明書</title>
</head>

<body>
<center>
<h2>仮パスワード再発行証明書</h2>
<div style="height: 13px;"></div>
<div style="width:500px; border: #000 dashed 1px;">
<table cellpadding="2" cellspacing="0" border="0">
<tr>
  <td width="90" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
<td width="200" align="center" style="border-bottom: #000 solid 2px;"><?php echo $mgr->getOutputData('kanji_name') ?></td>
<td width="24" align="center" style="border-bottom: #000 solid 2px;">様</td>
<td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90"><?php echo $mgr->getOutputData('idname') ?></td>
  <td align="center">：</td>
  <td colspan="2" align="center"><?php echo $mgr->getOutputData('staffcode') ?></td>
  <td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90">仮パスワード</td>
  <td align="center">：</td>
  <td colspan="2" align="center"><?php echo $mgr->getOutputData('passwd') ?></td>
  <td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td width="90" align="center">&nbsp;</td>
  <td width="10" align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td width="100" align="center">&nbsp;</td>
</tr>
<tr>
  <td align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td colspan="3" align="center">発行年月日　　　<?php echo $mgr->getOutputData('print_time') ?></td>
  </tr>
<tr>
  <td align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
  <td colspan="3" align="center">&nbsp;</td>
</tr>
</table>
</div>
<div style="height: 40px;"></div>
<span><?php echo $mgr->getOutputData('kanji_name') ?>　様の仮パスワードの発行の依頼を受け、再発行しました。</span>
<div style="height: 20px;"></div>
<span>※受け取られた仮パスワードは、本日中に変更をお願いいたします。※</span>
<div style="height: 24px;"></div>
<h2>受領証</h2>
<div style="width:500px; height:290px; border: #000 dotted 1px;"><div style="margin-top:135px;">ご本人証明書</div></div>
<div style="height: 24px;"></div>
<span>受領年月日：　　　　　　　　年　　　月　　　日</span>
<div style="height: 24px;"></div>
<span>上記の内容に誤りが無いことを確認したので、仮パスワードを受領しました。</span>
<div style="height: 40px;"></div>
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td align="right">氏名</td>
    <td width="5" align="center">&nbsp;</td>
    <td width="300" align="center" style="border-bottom: #000 solid 2px;"></td>
    </tr>
</table>
</center>
</body>
</html>
