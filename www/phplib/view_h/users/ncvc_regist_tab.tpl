          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th colspan="6">システム利用情報　※本システムにログインする場合およびメール利用の場合必ず入力してください</th>
            </tr>
              <tr>
                <th>統合ID</th>
                <td colspan="5"><input name="login_id" type="text" id="login_id" size="18" maxlength="20" value="<?php echo $mgr->getRequestData('login_id') ?>" />
                [<a href="javascript:;" onclick="makeLoginId();">自動生成</a>]
                [<a href="javascript:;" onclick="existsLoginId();">重複チェック</a>]
                &nbsp;<span class="inputRule">(ヘボン式ローマ字で「姓．名．規定の英字2文字[<a class="login_id_str" href="#" title="規定の英字2文字|<?php echo join("　", $GLOBALS['rand_tow_chars']) ?>">？</a>]」　例：yamada.taro.hp)</span></td>
              </tr>
              <tr>
                <th>パスワード</th>
                <td colspan="5">
<?php if ($mgr->getRequestData('user_id') == "") { ?>
                  <input name="login_passwd" type="text" id="login_passwd" size="30" value="<?php echo $mgr->getRequestData('login_passwd') ?>" />
                  [<a href="javascript:;" onclick="makePassword('login_passwd');">自動生成</a>]
                  <span class="inputRule">(半角数字、半角英字大文字、半角英字小文字　全て混在　6～20文字)</span>
<?php } else { ?>
                  <span class="inputRule">※更新する場合のみ入力してください。</span><br />
                  <input name="login_passwd" type="text" id="login_passwd" size="30" value="<?php echo $mgr->getRequestData('login_passwd') ?>" />
                  [<a href="javascript:;" onclick="makePassword('login_passwd');">自動生成</a>]
                  <span class="inputRule">(半角数字、半角英字大文字、半角英字小文字　全て混在　6～20文字)</span>

<?php } ?>
                </td>
              </tr>
              <tr>
                <th>メール使用有無</th>
                <td colspan="5"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="mail_disused_flg" id="mail_disused_flg" value="1" <?php echo $mgr->getCheckData('mail_disused_flg', '1') ?> onClick="changeAccText(this);" /></td>
                      <td><label for="mail_disused_flg">メールを使用しない</label></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th>メール<br />アカウント</th>
                <td colspan="5"><input name="mail_acc" type="text" id="mail_acc" size="20" maxlength="30" value="<?php echo $mgr->getRequestData('mail_acc') ?>" <?php echo ($mgr->getRequestData('mail_disused_flg') == "1" ? "disabled" : "") ?> />
                  <?php echo USER_MAIL_DOMAIN ?><br /><span class="inputRule">(数字、英字大文字・小文字、-（ハイフン）、_（アンダースコア）、.（ピリオド）　3～30文字)</span></td>
              </tr>
<?php if ($mgr->getRequestData('user_id') != "") { ?>
              <tr>
                <th>各種通知書</th>
                <td colspan="5">
                  <input type="button" value="　　NCVCネット統合ID通知書発行　　" onclick="printNcvcID();">
                  　<input type="button" value="　　パスワード再発行証明書　　" onclick="printNcvcPassword();">
                </td>
              </tr>
<?php } ?>
          </table>
          <table width="100%" border="0" cellpadding="5" cellspacing="0">
            <tr>
              <td align="left"><img src="image/space.gif" width="1" height="5" /></td>
            </tr>
          </table>
          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="4">システム情報</th>
              </tr>
              <tr>
                <th width="100">利用者種別</th>
                <td colspan="3"><div class="CheckBoxTab">
                    <?php echo $mgr->getRadioButtonList('user_type_id') ?>
                </div></td>
              </tr>
              <tr>
                <th width="100">データ出力権限</th>
                <td colspan="3"><div class="CheckBoxTab">
                    <?php echo $mgr->getCheckBoxList('user_role_id') ?>
                  </div></td>
              </tr>
              <tr>
                <th width="100">連携不要項目</th>
                <td colspan="3"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="garoon_disused_flg" id="garoon_disused_flg" value="1" <?php echo $mgr->getCheckData('garoon_disused_flg', '1') ?> /></td>
                      <td><label for="garoon_disused_flg">ガルーン</label></td>
                      <td>&nbsp;&nbsp;&nbsp;</td>
                      <td><input type="checkbox" name="mlist_disused_flg" id="mlist_disused_flg" value="1" <?php echo $mgr->getCheckData('mlist_disused_flg', '1') ?> /></td>
                      <td><label for="mlist_disused_flg">動的配布リスト</label></td>
                      <td>&nbsp;<span class="inputRule">※連携が不要な場合のみチェックして下さい。</span></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th width="100">VDI利用</th>
                <td colspan="3"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="vdi_user_flg" id="vdi_user_flg" value="1" <?php echo $mgr->getCheckData('vdi_user_flg', '1') ?> /></td>
                      <td><label for="vdi_user_flg">仮想環境プリンタを利用する</label></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th width="100">利用期間<span class="hissu">＊</span></th>
                <td colspan="3">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input name="start_date" type="text" id="start_date" onchange="cal_sd.getFormValue(); cal_sd.hide();" onclick="cal_sd.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('start_date') ?>" /><br /><div id="caldiv_sd"></div></td>
                      <td> 　～　 </td>
                      <td><input name="end_date" type="text" id="end_date" onchange="cal_ed.getFormValue(); cal_ed.hide();" onclick="cal_ed.write();" size="12" maxlength="10" value="<?php echo $mgr->getRequestData('end_date') ?>" /><br /><div id="caldiv_ed"></div></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
