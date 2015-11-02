<?php include("view/win_head.tpl") ?>
<?php if (is_array(@$mgr->aryAutoCond)) { ?>
              <h3>■登録済み抽出条件一覧</h3>
<?php   if (count($mgr->aryAutoCond) > 0) { ?>
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th>職種</th>
                  <th>役職</th>
                  <th>常勤／非常勤</th>
                  <th>所属</th>
                </tr>
<?php     foreach ($mgr->aryAutoCond AS $no => $data) { ?>
                <tr>
<?php       if ($data['is_all_user']) { ?>
                  <td colspan="4">
                    全ての利用者
                    <input type="hidden" name="is_all_user" id="is_all_user" value="1">
                  </td>
<?php       } else { ?>
                  <td><?php echo $data['job_name'] ?></td>
                  <td><?php echo $data['post_name'] ?></td>
                  <td><?php echo $data['joukin_name'] ?></td>
                  <td><?php echo $data['belong_name'] ?></td>
<?php       } ?>
                </tr>
<?php     } ?>
              </table>
<?php   } ?>
<?php } ?>

<?php if (is_array(@$mgr->aryList)) { ?>
      <div class="resultBlock">
        <h3>■メンバー一覧一覧</h3>
<?php   if (count($mgr->aryList) > 0) { ?>
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th>所属<span class="hissu">*</span></th>
                  <th width="230">メールアドレス<span class="hissu">*</span></th>
                  <th width="120">氏名</th>
<?php     if ($mgr->sender_kbn == SENDER_KBN_LIMIT) { ?>
                  <th width="60">送信</th>
                  <th width="60">受信</th>
<?php     } ?>
                </tr>
<?php     foreach ($mgr->aryList AS $data) { ?>
                <tr>
                  <td><?php echo $data['belong_name'] ?></td>
                  <td><?php echo $data['mail_addr'] ?></td>
                  <td><?php echo $data['member_name'] ?></td>
<?php if ($mgr->sender_kbn == SENDER_KBN_LIMIT) { ?>
                  <td align="center"><?php echo $data['sender_flg'] ?></td>
                  <td align="center"><?php echo $data['recipient_flg'] ?></td>
<?php } ?>
                </tr>
<?php     } ?>
              </table>
<?php   } else { ?>
        <table cellspacing="0">
          <tr>
            <td align="left">条件に一致するデータはありませんでした。</td>
          </tr>
        </table>
<?php   } ?>
      </div>
<?php } ?>
<?php include("view/foot.tpl") ?>

