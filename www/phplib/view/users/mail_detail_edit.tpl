        <input type="hidden" name="list_no" id="list_no" value="">
<?php include("view/users/detail_common_header.tpl") ?>
        <table border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td>■メール転送設定</td>
          </tr>
          <tr>
            <td><div class="CheckBoxTab">
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><input type="checkbox" name="sendon_type" id="sendon_type" value="1" <?php echo $mgr->getCheckData('sendon_type', '1') ?> onclick="typeChange(this);" /></td>
                  <td><label for="sendon_type">転送時サーバーにメールを残す</label>[<a class="mailsave" href="#" title="サーバにメールを残すと|転送元メールアドレス宛てに送信されたメールが、転送元メールアドレス・転送先メールアドレスの両方で受信可能です。 ">？</a>]</td>
                </tr>
              </table>
            </div></td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">転送先メールアドレス</th>
                <th width="80" scope="col">追加／削除</th>
              </tr>
              <tr>
                <td class="add"><input name="sendon_addr" type="text" id="sendon_addr" size="40" value="<?php echo $mgr->getRequestData('sendon_addr') ?>" /></td>
                <td class="add" align="center">[<a href="javascript:;" onclick="addSendonAddr();">追加</a>]</td>
              </tr>
<?php if (is_array($mgr->output['sendon_list'])) { ?>
<?php   foreach ($mgr->output['sendon_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('sendon_list', $list_no) ?></td>
                <td align="center">[<a href="javascript:;" onclick="delSendonAddr(<?php echo $list_no ?>);">削除</a>]</td>
              </tr>
<?php   } ?>
<?php } ?>
            </table></td>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td><h3>■メールエイリアス設定</h3></td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">エイリアスアドレス</th>
                <th width="80" scope="col">追加／削除</th>
                </tr>
              <tr>
                <td class="add"><input name="oldmail_addr" type="text" id="oldmail_addr" size="40" value="<?php echo $mgr->getRequestData('oldmail_addr') ?>" /></td>
                <td class="add" align="center">[<a href="javascript:;" onclick="addOldmailAddr();">追加</a>]</td>
              </tr>
<?php if (is_array($mgr->output['oldmail_list'])) { ?>
<?php   foreach ($mgr->output['oldmail_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('oldmail_list', $list_no) ?></td>
                <td align="center">[<a href="javascript:;" onclick="delOldmailAddr(<?php echo $list_no ?>);">削除</a>]</td>
              </tr>
<?php   } ?>
<?php } ?>
              </table></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td><h3>■メールアドレス再設定フラグ</h3></td>
          </tr>
          <tr>
            <td><div class="CheckBoxTab">
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><input type="checkbox" name="mail_reissue_flg" id="mail_reissue_flg" value="1" <?php echo $mgr->getCheckData('mail_reissue_flg', '1') ?> onclick="changeMailReissueFlg();" /></td>
                  <td><label for="mail_reissue_flg">ログイン時にメール再設定画面を表示する</label></td>
                </tr>
              </table>
            </div></td>
          </tr>
<?php if ($mgr->getRequestData('mail_over_flg') == "1") { ?>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td><h3>■期限切れユーザのメールアカウント無効化</h3></td>
          </tr>
          <tr>
            <td>
              <table border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th>登録アカウント</th>
                  <td colspan="2"><?php echo $mgr->getOutputData('user_mail_addr') ?>&nbsp;</td>
                </tr>
<?php   if ($mgr->getRequestData('invalid_flg') == '1') { ?>
                <tr>
                  <th>状態</th>
                  <td>無効</td>
                  <td>[<a href="javascript:;" onclick="setMailAccValid();">有効にする</a>]</td>
                </tr>
<?php   } else { ?>
                <tr>
                  <th>状態</th>
                  <td>有効</td>
                  <td>[<a href="javascript:;" onclick="setMailAccInvalid();">無効にする</a>]</td>
                </tr>
                <tr>
                  <th width="105">例外コメント</th>
                  <td><textarea name="exception_note" id="exception_note" style="width:160px; height:50px;">
<?php echo $mgr->getRequestData('exception_note') ?></textarea></td>
                  <td>[<a href="javascript:;" onclick="setMailAccExcCmt();">コメントを保存</a>]</td>
                </tr>
<?php   } ?>
              </table>
            </td>
          </tr>
<?php } ?>
        </table>