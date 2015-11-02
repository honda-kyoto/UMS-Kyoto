        <input type="hidden" name="list_no" id="list_no" value="">
        <table border="0" cellspacing="3" cellpadding="3">
<?php if ($mgr->getOutputData('mail_acc') != "") { ?>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td>■メールアドレス</td>
          </tr>
          <tr>
            <td><?php echo $mgr->getOutputData('mail_acc') ?><?php echo USER_MAIL_DOMAIN ?></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php } ?>

<!--
<?php if ($mgr->getOutputData('mail_acc') != "") { ?>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td>■メールアドレス</td>
          </tr>
          <tr>
            <td><?php echo $mgr->getOutputData('mail_acc') ?><?php echo USER_MAIL_DOMAIN ?></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php if (is_array($mgr->output['oldmail_list']) && count($mgr->output['oldmail_list']) > 0) { ?>
          <tr>
            <td>■その他のメールアドレス</td>
          </tr>
          <tr>
            <td><div class="inputComment">※下記アドレス宛のメールは全て上記のメールアドレスへ送信されます　　　　　</div></td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">その他のメールアドレス</th>
              </tr>
<?php   foreach ($mgr->output['oldmail_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('oldmail_list', $list_no) ?></td>
              </tr>
<?php   } ?>
            </table></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php } ?>
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
<?php   if (is_array($mgr->output['sendon_list'])) { ?>
<?php     foreach ($mgr->output['sendon_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('sendon_list', $list_no) ?></td>
                <td align="center">[<a href="javascript:;" onclick="delSendonAddr(<?php echo $list_no ?>);">削除</a>]</td>
              </tr>
<?php     } ?>
<?php   } ?>
            </table></td>
          </tr>
<?php } ?>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td>■パスワード変更</td>
          </tr>
          <tr>
            <td><span class="alartMsg">※数字、英字大文字、英字小文字のみ使用可能です。<br />
          ※数字、英字大文字、英字小文字を各1文字以上必ず使用してください。<br />
          ※6文字以上20文字以内で記入して下さい。</span></td>
          </tr>
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td>現在のパスワード</td>
                <td align="center">：</td>
                <td><input name="login_passwd" type="password" id="login_passwd" size="30" maxlength="20" /></td>
              </tr>
              <tr>
                <td>新しいパスワード</td>
                <td align="center">：</td>
                <td><input name="new_login_passwd" type="password" id="new_login_passwd" size="30" maxlength="20" /></td>
              </tr>
              <tr>
                <td>新しいパスワード（確認用）</td>
                <td align="center">：</td>
                <td><input name="new_login_passwd_conf" type="password" id="new_login_passwd_conf" size="30" maxlength="20" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><input type="button" value="　　設　　定　　" onclick="passwdChange();" /></td>
          </tr>
-->
        </table>
