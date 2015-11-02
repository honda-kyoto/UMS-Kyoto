<?php include("view/head.tpl") ?>
        <input type="hidden" name="user_role_id" id="user_role_id" value="">
        <input type="hidden" name="file_name" id="file_name" value="">
        <h3>■出力条件</h3>
        <table width="100%"border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td><div class="inputComment">(▲)開始日は必須項目です。</div></td>
          </tr>
        </table>
        <table border="0" cellpadding="2" cellspacing="0" class="searchTab" id="searchTab">
          <tr>
            <th width="150">パスワード変更期間<span class="hissu">▲</span></th>
            <td>
              <div class="CheckBoxTab">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><input name="start_date" type="text" id="start_date" onchange="cal_sd.getFormValue(); cal_sd.hide();" onclick="cal_sd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('start_date') ?>" /><br /><div id="caldiv_sd"></div></td>
                    <td>&nbsp;&nbsp;&nbsp;～&nbsp;&nbsp;&nbsp; </td>
                    <td><input name="end_date" type="text" id="end_date" onchange="cal_ed.getFormValue(); cal_ed.hide();" onclick="cal_ed.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('end_date') ?>" /><br /><div id="caldiv_ed"></div></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>終了日が未入力の場合は、現在までを出力します。</td>
                  </tr>
                 </table>
               </div>
            </td>
          </tr>
        </table>
        <div class="searchBlock">
          <input type="button" value="　　　データ出力　　　" onclick="outputData()">
        </div>
        <table border="0" cellpadding="2" cellspacing="0" class="AjaxTableDisable" id="nowLoad">
          <tr>
            <td><img src="./image/loading.gif" alt="データ生成中です・・・" /></td>
          </tr>
          <tr>
            <td>データ生成中です・・・</td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
