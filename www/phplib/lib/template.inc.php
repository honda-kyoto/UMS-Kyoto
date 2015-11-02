<?php 
/**********************************************************
* File         : template.inc.php
* Authors      : Mie Tsutsui
* Date         : 2008.06.21
* Last Update  : 2008.06.21
* Copyright    :
***********************************************************/

$cmnTpl = array();

$cmnTpl['B001'] = <<< HTML
<li>{0}</li>
HTML;

$cmnTpl['B002'] = <<< HTML
<ul class="errmsg">
{0}
</ul>
HTML;

$cmnTpl['B003'] = <<< HTML
<option value="{0}" {1}>{2}</option>
HTML;

$cmnTpl['B004'] = <<< HTML
<input type="checkbox" name="{0}[{1}]" value="1" {2}>{3}&nbsp;&nbsp;
HTML;

$cmnTpl['B005'] = <<< HTML
&lt;&lt; 
HTML;

$cmnTpl['B006'] = <<< HTML
&gt;&gt; 
HTML;

$cmnTpl['B007'] = <<< HTML
<a href="javascript:turnOver({0})">{1}</a> 
HTML;

$cmnTpl['B008'] = <<< HTML
<b>{1}</b> 
HTML;

$cmnTpl['B009'] = <<< HTML
{0}件中　{1}～{2}件表示 　{3}
HTML;

$cmnTpl['B010'] = <<< HTML
<table border="0" cellspacing="0" cellpadding="0">
{0}
</table>
HTML;

$cmnTpl['B011'] = <<< HTML
<tr>
{0}
</tr>
HTML;

$cmnTpl['B012'] = <<< HTML
<td><input type="checkbox" name="{0}[{1}]" value="1" id="{4}" {2}></td>
<td><label for="{4}">{3}</label>&nbsp;&nbsp;</td>
HTML;

$cmnTpl['B013'] = <<< HTML
<td colspan="{0}">&nbsp;</td>
HTML;

$cmnTpl['B014'] = <<< HTML
<td><input type="radio" name="{0}" value="{1}" id="{4}" {2}></td>
<td><label for="{4}">{3}</label>&nbsp;&nbsp;</td>
HTML;

// 戻るボタン
$cmnTpl['B101'] = <<< HTML
<input type="button" value="　　戻　　　る　　" onclick="goBack('{0}');" />
HTML;


?>