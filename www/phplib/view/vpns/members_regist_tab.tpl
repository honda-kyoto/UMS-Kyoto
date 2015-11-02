              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th scope="col">ID</th>
                  <td scope="col"><?php echo $mgr->getRequestData('vpn_user_id') ?></td>
                </tr>
                <tr>
                  <th scope="col">パスワード<span class="hissu">*</span></th>
                  <td scope="col">
                    <input name="passwd" type="text" id="passwd" size="30" value="<?php echo $mgr->getRequestData('passwd') ?>" />
                    <span class="inputRule">(半角数字、半角英字大文字、半角英字小文字　全て混在　6～20文字)</span>
                  </td>
                </tr>
                <tr>
                  <th scope="col">有効期限<span class="hissu">*</span></th>
                  <td><input name="expiry_date" type="text" id="expiry_date" onchange="cal_expiry.getFormValue(); cal_expiry.hide();" onclick="cal_expiry.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('expiry_date') ?>" /><br /><div id="caldiv_expiry"></div></td>
                </tr>
                <tr>
                  <th scope="col">メールアドレス<span class="hissu">*</span></th>
                  <td scope="col"><input name="mail_addr" type="text" id="mail_addr" size="30" value="<?php echo $mgr->getRequestData('mail_addr') ?>" /></td>
                </tr>
                <tr>
                  <th scope="col">氏名</th>
                  <td scope="col"><input name="kanjiname" type="text" id="kanjiname" size="12" value="<?php echo $mgr->getRequestData('kanjiname') ?>" /></td>
                </tr>
                <tr>
                  <th scope="col">フリガナ</th>
                  <td scope="col"><input name="kananame" type="text" id="kananame" size="12" value="<?php echo $mgr->getRequestData('kananame') ?>" /></td>
                  
                </tr>
                <tr>
                  <th scope="col">会社</th>
                  <td><input name="company" type="text" id="company" size="20" value="<?php echo $mgr->getRequestData('company') ?>" /></td>
                </tr>
                <tr>
                  <th scope="col">連絡先</th>
                  <td><input name="contact" type="text" id="contact" size="20" value="<?php echo $mgr->getRequestData('contact') ?>" /></td>
                </tr>
                <tr>
                  <th scope="col">備考</th>
                  <td><textarea name="note" cols="40" rows="4" id="note">
<?php echo $mgr->getRequestData('note') ?></textarea></td>
                </tr>
              </table>
