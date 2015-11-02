        <table border="0" cellspacing="3" cellpadding="3">
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td>■旧メールアドレス</td>
          </tr>
          <tr>
            <td><div class="inputComment">※下記アドレス宛のメールは全て基本情報画面に記載のメールアドレスへ送信されます</div></td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">転送先メールアドレス</th>
              </tr>
<?php if (is_array($mgr->output['oldmail_list'])) { ?>
<?php   foreach ($mgr->output['oldmail_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('oldmail_list', $list_no) ?></td>
              </tr>
<?php   } ?>
<?php } ?>
            </table></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
        </table>
