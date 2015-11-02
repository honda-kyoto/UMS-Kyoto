        <table border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td><table width="700" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="4">基本情報</th>
              </tr>
              <tr>
                <th>氏名</th>
                <td><?php echo $mgr->getOutputData('kanjisei') ?>　<?php echo $mgr->getOutputData('kanjimei') ?>（<?php echo $mgr->getOutputData('kanasei') ?>　<?php echo $mgr->getOutputData('kanamei') ?>）</td>
                <th width="100">内線</th>
                <td><?php echo $mgr->getOutputData('naisen') ?></td>
              </tr>
              <tr>
                <th width="105">組織</th>
                <td colspan="3"><?php echo $mgr->getOutputData('belong_name') ?>　<?php echo $mgr->getOutputData('job_name') ?>　<?php echo $mgr->getOutputData('post_name') ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
          </tr>
          <tr>
            <td><hr /></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
          </tr>
        </table>
        <table border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td>■メール転送設定</td>
          </tr>
          <tr>
            <td><div class="CheckBoxTab">
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><input type="checkbox" name="sendon_type" id="sendon_type" value="1" <?php echo $mgr->getCheckData('sendon_type', '1') ?> onclick="typeChange(this);" disabled /></td>
                  <td><label for="sendon_type">転送時サーバーにメールを残す</label>[<a class="mailsave" href="#" title="サーバにメールを残すと|転送元メールアドレス宛てに送信されたメールが、転送元メールアドレス・転送先メールアドレスの両方で受信可能です。 ">？</a>]</td>
                </tr>
              </table>
            </div></td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">転送先メールアドレス</th>
              </tr>
<?php   if (is_array($mgr->output['sendon_list'])) { ?>
<?php     foreach ($mgr->output['sendon_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('sendon_list', $list_no) ?></td>
              </tr>
<?php     } ?>
<?php   } ?>
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
                </tr>
<?php   if (is_array($mgr->output['oldmail_list'])) { ?>
<?php     foreach ($mgr->output['oldmail_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('oldmail_list', $list_no) ?></td>
              </tr>
<?php     } ?>
<?php   } ?>
              </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><h3>■メールアドレス再設定フラグ</h3></td>
          </tr>
          <tr>
            <td><div class="CheckBoxTab">
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><input type="checkbox" name="mail_reissue_flg" id="mail_reissue_flg" value="1" <?php echo $mgr->getCheckData('mail_reissue_flg', '1') ?> onclick="changeMailReissueFlg();" disabled /></td>
                  <td><label for="mail_reissue_flg">ログイン時にメール再設定画面を表示する</label></td>
                </tr>
              </table>
            </div></td>
          </tr>
        </table>