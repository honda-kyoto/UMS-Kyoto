          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th colspan="6">システム利用情報</th>
            </tr>
              <tr>
                <th width="100">統合ID</th>
                <td colspan="5">
<?php if ($mgr->getOutputData('login_id') == "") { ?>
                  ※未登録
<?php } else { ?>
                  <?php echo $mgr->getOutputData('login_id') ?>
<?php } ?>
                </td>
              </tr>
              <tr>
                <th>パスワード</th>
                <td colspan="5">
<?php if ($mgr->getOutputData('login_passwd') == "") { ?>
                  ※未登録
<?php } else { ?>
                  <?php echo str_repeat("*", strlen($mgr->getOutputData('login_passwd'))) ?>
<?php } ?>
                </td>
              </tr>
              <tr>
                <th>メール使用有無</th>
                <td colspan="5"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="mail_disused_flg" id="mail_disused_flg" value="1" <?php echo $mgr->getCheckData('mail_disused_flg', '1') ?> disabled /></td>
                      <td><label for="mail_disused_flg">メールを使用しない</label></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th>メール<br />アカウント</th>
                <td colspan="5"><?php echo $mgr->getOutputData('mail_acc') ?><?php echo USER_MAIL_DOMAIN ?></td>
              </tr>
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
                    <?php echo str_replace('<input', '<input disabled', $mgr->getRadioButtonList('user_type_id')) ?>
                </div></td>
              </tr>
              <tr>
                <th width="100">データ出力権限</th>
                <td colspan="3"><div class="CheckBoxTab">
                    <?php echo str_replace('<input', '<input disabled', $mgr->getCheckBoxList('user_role_id')) ?>
                  </div></td>
              </tr>
              <tr>
                <th width="100">連携不要項目</th>
                <td colspan="3"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="garoon_disused_flg" id="garoon_disused_flg" value="1" <?php echo $mgr->getCheckData('garoon_disused_flg', '1') ?> disabled /></td>
                      <td><label for="garoon_disused_flg">ガルーン</label></td>
                      <td>&nbsp;&nbsp;&nbsp;</td>
                      <td><input type="checkbox" name="mlist_disused_flg" id="mlist_disused_flg" value="1" <?php echo $mgr->getCheckData('mlist_disused_flg', '1') ?> disabled /></td>
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
                      <td><input type="checkbox" name="vdi_user_flg" id="vdi_user_flg" value="1" <?php echo $mgr->getCheckData('vdi_user_flg', '1') ?> disabled /></td>
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
                      <td><?php echo $mgr->getOutputData('start_date') ?></td>
                      <td> 　～　 </td>
                      <td><?php echo $mgr->getOutputData('end_date') ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
