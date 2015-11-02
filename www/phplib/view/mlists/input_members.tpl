        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td>
            <h3>■メンバー追加</h3>
            <table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th width="230">メールアドレス<span class="hissu">*</span></th>
                <th width="230">氏名</th>
<?php if ($mgr->sender_kbn == SENDER_KBN_LIMIT) { ?>
                <th width="60">送信</th>
                <th width="60">受信</th>
<?php } ?>
                <th width="80">追加</th>
              </tr>
              <tr>
                <td width="230"><input name="mail_addr" type="text" id="mail_addr" size="30" value="<?php echo $mgr->getRequestData('mail_addr') ?>"  /></td>
                <td width="230"><input name="member_name" type="text" id="member_name" size="30" value="<?php echo $mgr->getRequestData('member_name') ?>"  /></td>
<?php if ($mgr->sender_kbn == SENDER_KBN_LIMIT) { ?>
                <td width="60" align="center"><input type="checkbox" name="sender_flg" id="cender_flg" value="1" <?php echo $mgr->getCheckData('sender_flg', '1') ?>></td>
                <td width="60" align="center"><input type="checkbox" name="recipient_flg" id="recipient_flg" value="1" <?php echo $mgr->getCheckData('recipient_flg', '1') ?>></td>
<?php } ?>
                <td width="80" align="center">[<a href="javascript:;" onclick="addMember();">追加</a>]</td>
              </tr>
            </table>
<?php if (is_array(@$mgr->aryList)) { ?>
            <div class="resultBlock">
              <h3>■メンバー一覧</h3>
<?php   if (count($mgr->aryList) > 0) { ?>
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th width="230">メールアドレス<span class="hissu">*</span></th>
                  <th width="230">氏名</th>
<?php     if ($mgr->sender_kbn == SENDER_KBN_LIMIT) { ?>
                  <th width="60">送信</th>
                  <th width="60">受信</th>
<?php     } ?>
                  <th width="80">削除</th>
                </tr>
<?php     foreach ($mgr->aryList AS $data) { ?>
                <tr>
                  <td><?php echo $data['mail_addr'] ?></td>
                  <td><?php echo $data['member_name'] ?></td>
<?php if ($mgr->sender_kbn == SENDER_KBN_LIMIT) { ?>
                  <td align="center"><?php echo $data['sender_flg'] ?></td>
                  <td align="center"><?php echo $data['recipient_flg'] ?></td>
<?php } ?>
                  <td align="center">[<a href="javascript:;" onclick="deleteMember('<?php echo $data['mail_addr'] ?>');">削除</a>]</td>
                </tr>
<?php     } ?>
              </table>
<?php   } else { ?>
        <table cellspacing="0">
          <tr>
            <td align="left">現在登録されているデータはありません</td>
          </tr>
        </table>
<?php   } ?>
            </div>
<?php } ?>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
